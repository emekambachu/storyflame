<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Nette\NotImplementedException;

class OnboardingService
{
	public function __construct(
		private readonly ChatService          $chatService,
		private readonly TranscriptionService $transcriptionService,
	)
	{
	}

	// TODO: move this to achievement service
	const ACHIEVEMENTS = [
		'icebreaker' => [
			'name',
			'level',
			'genre_focus',
			'writing_medium',
		],
		'recommendation_ready' => [
			'media',
			'characters',
			'audience',
			'themes',
		],
		'process' => [
			'productivity',
			'writing_process',
			'stage_preference',
			'revision_style',
		],
		'collaborator' => [
			'collaboration_style',
			'adaptability',
		],
		'sculptor' => [
			'world_engagement',
			'narrative_focus',
		],
		'growth_guru' => [
			'motivation',
			'education',
			'stage',
		],
	];

	const QUESTION_GROUPS = [
		# get to know user
		'intro' => [
			'title' => 'Getting to know you',
			'subtitle' => 'Hello, welcome to {app_name}!',
			# pre-defined question
			'question' => 'What is your name?',
			# user needs to provide this data to proceed
			'data' => ['name'],
			# if user fails to provide this data, they will be asked to type it
			'fail' => 'prompt_repeat',
			'required' => true,
		],
		# try to make user talk and extract as many as we can
		'big_picture' => [
			'title' => 'Getting to know you',
			'subtitle' => 'Nice to meet up, {name}!',
			# pre-defined question
			'question' => 'Tell us about yourself as a writer.',
			# user needs to provide any data to proceed
			'required' => true,
		],
		# based on what we know about user so far
		# create a multiple choice question to get more specific data
		'focus' => [
			'title' => 'Know your focus',
			'subtitle' => 'Great to know more about you, {name}!',
			# generated question
			# user needs to choose one of the following
			'type' => 'multiple_choice',
			'data' => ['genre_focus'],
			'required' => true,
		],
		'writing' => [
			'title' => 'Your writing process',
			'subtitle' => 'Interesting, {name}!',
			# generated question
			# user needs to choose one of the following
			'type' => 'multiple_choice',
			'data' => ['writing_medium'],
			'required' => true,
		],
		# ask user about his favorites
		'big_five' => [
			'title' => 'Your favorites',
			'subtitle' => 'Really interesting, {name}!',
			'data' => ['media', 'characters', 'audience', 'themes'],
			'required' => true,
		],
		#tbd
	];

	const EXTRACTION_DATA = [
		'name' => [
			'type' => 'text',
			'name' => 'Name',
			'question' => 'What is your name?',
			'description' => 'Name of the user',
		],
		'bio' => [
			'type' => 'text',
			'name' => 'Bio',
			'question' => 'Tell us about yourself as a writer.',
			'description' => 'Some information about the user.',
		],

		'level' => [
			'type' => 'text',
			'name' => 'Skill Level',
			'examples' => ['beginner', 'intermediate', 'advanced', 'professional'],
			'description' => 'Skill level of the user',
		],
		'genre_focus' => [
			'type' => 'array',
			'name' => 'Genre Focus',
			'examples' => ['fantasy', 'sci-fi', 'horror', 'romance', 'mystery', 'thriller', 'comedy', 'drama'],
			'description' => 'Genre focus of the user',
		],
		'writing_medium' => [
			'type' => 'array',
			'name' => 'Writing Medium',
			'examples' => ['TV', 'Film', 'Novelist', 'Short Story Writer', 'Poet', 'Playwright', 'Screenwriter', 'Copywriter'],
			'description' => 'Writing medium of the user',
		],

		'media' => [
			'type' => 'array',
			'name' => 'Influential Media',
			'description' => 'Media that influences the user',
		],
		'characters' => [
			'type' => 'array',
			'name' => 'Influential Characters',
			'description' => 'Characters that influence the user',
		],
		'audience' => [
			'type' => 'array',
			'name' => 'Target Audience',
			'description' => 'Target audience of the user',
		],
		'themes' => [
			'type' => 'array',
			'name' => 'Themes',
			'examples' => ['Identity', 'Love', 'Loss', 'Coming of age', 'Social issues', 'Politics', 'History'],
			'description' => 'Themes or subjects that the user is interested in',
		],

		'productivity' => [
			'type' => 'text',
			'name' => 'Productivity',
			'examples' => ['high-output', 'steady', 'sporadic', 'procrastinator'],
			'description' => 'Productivity style of the user',
		],
		'writing_process' => [
			'type' => 'text',
			'name' => 'Writing Process',
			'examples' => ['plotter', 'pantser', 'plantser'],
			'description' => 'Writing process of the user',
		],
		'stage_preference' => [
			'type' => 'text',
			'name' => 'Writing Stage Preference',
			'examples' => ['starter', 'polisher', 'finisher'],
			'description' => 'Writing stage preference of the user',
		],
		'revision_style' => [
			'type' => 'text',
			'name' => 'Revision Style',
			'examples' => ['iterative', 'holistic', 'collaborative', 'intuitive'],
			'description' => 'Revision style of the user',
		],

		'collaboration_style' => [
			'type' => 'text',
			'name' => 'Collaboration Style',
			'examples' => ['solo', 'collaborator', 'ghostwriter', 'partner'],
			'description' => 'Collaboration style of the user',
		],
		'adaptability' => [
			'type' => 'text',
			'name' => 'Adaptability',
			'examples' => ['versatile', 'specialized', 'open to feedback', 'resistant to change'],
			'description' => 'Adaptability of the user',
		],

		'world_engagement' => [
			'type' => 'text',
			'name' => 'World Engagement',
			'examples' => ['builder', 'inhabitor', 'explorer', 'observer'],
			'description' => 'World engagement of the user',
		],
		'narrative_focus' => [
			'type' => 'text',
			'name' => 'Narrative Focus',
			'examples' => ['plot-driven', 'character-centric', 'dialogue-oriented', 'theme-focused', 'atmosphere-builder'],
			'description' => 'Narrative focus of the user',
		],

		'motivation' => [
			'type' => 'text',
			'name' => 'Motivation',
			'examples' => ['passion-driven', 'career-focused', 'therapeutic', 'hobby'],
			'description' => 'Motivation of the user',
		],
		'education' => [
			'type' => 'text',
			'name' => 'Educational Background',
			'examples' => ['self-taught', 'creative writing degree', 'literature degree', 'MFA', 'PhD'],
			'description' => 'Educational background of the user',
		],
		'stage' => [
			'type' => 'text',
			'name' => 'Career Stage',
			'examples' => ['aspiring', 'emerging', 'established', 'bestselling', 'professional'],
			'description' => 'Career stage of the user',
		]
	];


	public function getQuestionCount(): int
	{
		return count(self::EXTRACTION_DATA);
	}

	public function getRequiredData(): array
	{
		return collect(array_map(fn($group) => $group['data'] ?? [], array_filter(self::QUESTION_GROUPS, fn($group) => $group['required'] ?? false)))
			->flatten()
			->unique()
			->toArray();
	}

	public function getProgress(): int
	{
		$required_data = array_intersect(
			array_keys(auth()->user()->extra_attributes->onboarding ?? []),
			$this->getRequiredData()
		);
		return count($required_data) / count($this->getRequiredData()) * 100;
	}

	public function getChat(): Chat
	{
		$user = auth()->user();

		// if user has no onboarding chat, create one
		if (!$user->chats()->where('type', 'onboarding')->exists()) {
			$chat = $user->chats()->create([
				'type' => 'onboarding',
			]);
		} else {
			$chat = $user->chats()->where('type', 'onboarding')->first();
		}

		// if chat has no messages, create an intro message
		if ($chat->chatMessages()->count() === 0) {
			$this->generateNextQuestion($chat);
		}

		return $chat;
	}

	public function addMessage(UploadedFile $audio = null, $content = null): ChatMessage
	{
		if (!$content && !$audio) {
			throw new \InvalidArgumentException('Either content or audio must be provided');
		}

		$user = auth()->user();

		$chat = $this->getChat();

		// create message
		return $this->chatService->create($chat, $user, [
			'content' => $content,
			'audio' => $audio,
		]);
	}

	public function extractData(): array
	{
		$user = auth()->user();
		$user_data = $user->extra_attributes->onboarding ?? [];

		// get keys that are not in user data
		$available_properties = array_map(fn($data) => $data, array_diff_key(self::EXTRACTION_DATA, $user_data));

		$chat = $this->getChat();
		$question = $chat->chatMessages()->latest()->whereNull('user_id')->first();
		$answer = $chat->chatMessages()->latest()->whereNotNull('user_id')->first();

		$request = Http::post(config('app.processing_url') . '/onboarding/extract', [
			'question' => $question->content,
			'message' => $answer->content,
			'data' => $user_data,
			'available_properties' => $available_properties,
		]);

		$request->throw();

		# filter out null values
		$extracted = array_filter(array_map(
			fn($value) => $value === null || $value === '' || $value === 'null' || $value === 'N/A' ? null : $value,
			$request->json()
		), fn($value) => $value !== null && $value !== '');

		$answer->extra_attributes->extracted = $extracted;
		$answer->save();

		$user->extra_attributes->onboarding = array_merge($user_data, $extracted);
		$user->save();

		return $user->extra_attributes->onboarding;
	}

	private function replaceTemplate(string $text): string
	{
		$user = auth()->user();
		$replacements = [
			'{app_name}' => config('app.name'),
		];
		foreach ($user->extra_attributes->onboarding ?? [] as $key => $value) {
			$replacements["{{$key}}"] = is_array($value) ? implode(', ', $value) : $value;
		}
		return str_replace(array_keys($replacements), array_values($replacements), $text);
	}

	private function createQuestion(Chat $chat, string $groupKey, string $content, string $type = 'text', $options = null): ChatMessage
	{
		$group = self::QUESTION_GROUPS[$groupKey];
		return $chat->chatMessages()->create([
			'type' => $type,
			'content' => $content,
			'user_id' => null,
			'extra_attributes' => [
				'group' => $groupKey,
				'title' => isset($group['title']) ? $this->replaceTemplate($group['title']) : null,
				'subtitle' => isset($group['subtitle']) ? $this->replaceTemplate($group['subtitle']) : null,
				'options' => $options,
			],
		]);
	}

	private function generateNextQuestion(Chat $chat): ChatMessage
	{
		$user_data = auth()->user()->extra_attributes->onboarding ?? [];

		if ($chat->chatMessages()->count() === 0) {
			# generate first question
			return $this->createQuestion($chat, 'intro', self::QUESTION_GROUPS['intro']['question']);
		}

		$lastQuestion = $chat->chatMessages()->latest()->whereNull('user_id')->first();

		# get last question group
		$lastGroup = self::QUESTION_GROUPS[$lastQuestion->extra_attributes['group']];


		$answeredAllQuestionsInGroup = !isset($lastGroup['data']) || empty(array_diff($lastGroup['data'], array_keys($user_data)));

		if (!$answeredAllQuestionsInGroup) {
			# if group has a fail condition
			if (isset($lastGroup['fail'])) {
				# ask user to type the data
				return $this->createQuestion(
					$chat,
					$lastQuestion->extra_attributes['group'],
					$lastGroup['fail'],
					'system'
				);
			}
		}

		# get next group
		$groupIndex = array_search($lastQuestion->extra_attributes['group'], array_keys(self::QUESTION_GROUPS));
		$nextGroupIndex = $groupIndex + ($answeredAllQuestionsInGroup ? 1 : 0);

		if (!isset(array_keys(self::QUESTION_GROUPS)[$nextGroupIndex])) {
			return $this->createQuestion($chat, $lastQuestion->extra_attributes['group'], 'finish', 'system');
		}

		$nextGroupKey = array_keys(self::QUESTION_GROUPS)[$nextGroupIndex];
		$nextGroup = self::QUESTION_GROUPS[$nextGroupKey];

		# if there are no more groups
		if ($nextGroup === null) {
			throw new NotImplementedException('This should not happen');
		}

		# check if next group has a pre-defined question
		if (isset($nextGroup['question'])) {
			# check if that question wasn't already asked
			if (!$chat->chatMessages()->notSystem()->whereNull('user_id')->where('content', $nextGroup['question'])->exists()) {
				# ask the question
				return $this->createQuestion(
					$chat,
					$nextGroupKey,
					$nextGroup['question'],
				);
			}
			# otherwise, generate a question based on the data
		}

		# get data for the question
		if (!isset($nextGroup['data'])) {
			throw new NotImplementedException('This should not happen, maybe :)');
		}

		$group = collect($nextGroup['data'])
			->filter(fn($key) => !array_key_exists($key, $user_data))
			->map(fn($key) => self::EXTRACTION_DATA[$key])
			->values();

		$request = Http::post(config('app.processing_url') . '/onboarding/question', [
			'type' => $nextGroup['type'] ?? 'text',
			'history' => $chat->chatMessages()->notSystem()->oldest()->get()->map(fn($message) => [
				'agent' => $message->user_id ? 'user' : 'assistant',
				'text' => $message->content,
			])->toArray(),
			'data' => $group->map(fn($data) => [
				'name' => $data['name'],
				'description' => $data['description'],
				'examples' => $data['examples'] ?? null,
			])->toArray(),
		]);

		$request->throw();

		return $this->createQuestion(
			$chat,
			$nextGroupKey,
			$request->json()['question'],
			$nextGroup['type'] ?? 'text',
			$request->json()['options'] ?? null
		);
	}

	public function getNextQuestion(): ChatMessage
	{
		$chat = $this->getChat();
		return $this->generateNextQuestion($chat);
	}

	public function getLastQuestion(): ChatMessage
	{
		$chat = $this->getChat();

		$questions = $chat->chatMessages()->notSystem()->latest()->whereNull('user_id')->get();
		if ($questions->count() === 0) {
			return $this->generateNextQuestion($chat);
		}
		return $questions->first();
	}
}
