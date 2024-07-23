<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Nette\NotImplementedException;

abstract class ConversationService
{
    public function __construct(
        private readonly ChatService        $chatService,
        private readonly AchievementService $achievementService
    )
    {
    }

    protected const EXTRACTION_URL = '/onboarding/extract';

    /**
     * The question groups for the conversation.
     */
    protected const QUESTION_GROUPS = [];

    /**
     * The data definitions to extract from conversation.
     */
    protected const EXTRACTION_DATA = [];

    protected function getExtractedData(Chat $chat): array
    {
        return $chat->extra_attributes->extracted ?? [];
    }

    protected function getAvailableProperties(Chat $chat): array
    {
        return array_map(fn($data) => $data, array_diff_key(static::EXTRACTION_DATA, $this->getExtractedData($chat)));
    }

    protected function saveChatData(Chat $chat, array $data): void
    {
        $extracted_data = array_merge($chat->extra_attributes->extracted ?? [], $data ?? []);
        $chat->extra_attributes->extracted = $extracted_data;
        $chat->save();
    }

    protected function saveAnswerData(ChatMessage $answer, array $extracted): void
    {
        $answer->extra_attributes->extracted = $extracted;
        $answer->save();
    }

    public function getQuestionCount(): int
    {
        return count(static::EXTRACTION_DATA);
    }

    public function getRequiredData(): array
    {
        return collect(array_map(fn($group) => $group['data'] ?? [], array_filter(static::QUESTION_GROUPS, fn($group) => $group['required'] ?? false)))
            ->flatten()
            ->unique()
            ->toArray();
    }

    public function getProgress(User $user): int
    {
        if (!$chat = $this->getUserChat($user)) {
            return 0;
        }
        $required_data = array_intersect(
            array_keys($this->getExtractedData($chat)),
            $this->getRequiredData()
        );
        return count($required_data) / count($this->getRequiredData()) * 100;
    }

    protected function arrayToKeyValues(string $prefix, array $array): array
    {
        $values = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $values = array_merge($values, $this->arrayToKeyValues($prefix . '.' . $key, $value));
            } else {
                $values["{{$prefix}.{$key}}"] = $value;
            }
        }
        return $values;
    }

    private function replaceTemplate(string $text, ?User $user = null, ?Chat $chat = null): string
    {
        $replacements = [
            '{app_name}' => config('app.name'),
        ];
        if ($user) {
            $replacements = array_merge(
                $replacements,
                $this->arrayToKeyValues('user', $user->toArray())
            );
        }
        if ($chat) {
            $replacements = array_merge(
                $replacements,
                $this->arrayToKeyValues('extracted', $chat->extra_attributes->extracted ?? [])
            );
        }
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    /**
     * Create a new chat for the conversation with initial data
     * @param User $user
     * @param array $data
     * @return Chat
     */
    abstract protected function createChat(User $user, array $data = []): Chat;

    /**
     * Get conversation chat for the user
     * @param User $user
     * @return Chat|null
     */
    abstract protected function getUserChat(User $user): ?Chat;

    /**
     * Get the chat for the conversation
     * @throws RequestException
     */
    protected function getChat(): Chat
    {
        if (!$chat = $this->getUserChat(auth()->user())) {
            $chat = $this->createChat(auth()->user());
        }
        if ($chat->chatMessages()->count() === 0) {
            $this->generateNextQuestion($chat);
        }
        return $chat;
    }

    /**
     * Get chat history formatted for the AI processing
     * @param Chat $chat
     * @return array
     */
    protected function getChatHistory(Chat $chat): array
    {
        return $chat->chatMessages()->notSystem()->oldest()->get()->map(function ($message) {
            return [
                'agent' => $message->user_id ? 'human' : 'ai',
                'text' => $message->user_id === null
                    ? ('Message: ' . $message->extra_attributes->subtitle . ' Question: ' . $message->content)
                    : $message->content,
            ];
        })->toArray();
    }

    private function createQuestion(Chat $chat, string $groupKey, string $subtitle, string $content, string $type = 'text', $options = null, $optionsKey = null): ChatMessage
    {
        $group = static::QUESTION_GROUPS[$groupKey];
        return $chat->chatMessages()->create([
            'type' => $type,
            'content' => $content,
            'user_id' => null,
            'extra_attributes' => array_filter(
                [
                    'group' => $groupKey,
                    'title' => isset($group['title']) ? $this->replaceTemplate($group['title'], chat: $chat) : null,
                    'subtitle' => $this->replaceTemplate($subtitle, chat: $chat),
                    'options' => $options,
                    'options_key' => $optionsKey,
                ],
                fn($value) => $value !== null
            ),
        ]);
    }

    /**
     * Function to save the extracted data
     * @param array $data
     * @return void
     */
    abstract function finishConversation(array $data): void;

    protected function getExtractionRequestBody(ChatMessage $question, ChatMessage $answer):array
    {
        return [
            'question' => $question->content,
            'message' => $answer->content,
            'data' => $this->getExtractedData($question->chat),
            'available_properties' => $this->getAvailableProperties($question->chat),
        ];
    }

    /**
     * Clean JSON, remove null values, empty string, 'null', 'N/A', empty arrays
     * @param array $json
     * @return array
     */
    private function cleanJson(array $json): array
    {
        $cleaned = [];
        foreach ($json as $key => $value) {
            if (is_array($value)) {
                $arr = $this->cleanJson($value);
                if (!empty($arr)) {
                    $cleaned[$key] = $arr;
                }
            } else {
                if (is_string($value)) {
                    $trim = trim($value);
                    if (in_array(strtolower($trim), ['null', 'n/a', '', '[]'])) {
                        continue;
                    }
                    $cleaned[$key] = $trim;
                } else if ($value === null) {
                    continue;
                } else {
                    // if it's not a string and null, it's a number or boolean
                    $cleaned[$key] = $value;
                }
            }
        }
        Log::info('cleaned', $cleaned);
        return $cleaned;
    }

    private function sendExtractionRequest(ChatMessage $question, ChatMessage $answer)
    {
        $request = Http::post(config('app.processing_url') . static::EXTRACTION_URL, $this->getExtractionRequestBody($question, $answer));

        $request->throw();

        Log::info('response', [$request->json()]);

        return $this->cleanJson($request->json());
    }

    /**
     * Extract data from the user's last message
     * @param User $user
     * @return array
     * @throws RequestException
     */
    public function extractData(User $user): array
    {
        $chat = $this->getChat();
        $extracted_data = $this->getExtractedData($chat);

        $question = $this->getPreviousQuestion($user);
        $answer = $this->getPreviousAnswer($user);

        if (!$answer)
            throw new InvalidArgumentException('User answer needs to be saved before extraction');

        // if the question was multiple choice, and we have key for options
        // we don't need to extract data
        if ($question->type === 'multiple_choice' &&
            isset($question->extra_attributes['options_key']) &&
            isset($answer->extra_attributes['options'])
        ) {
            $extracted = [
                $question->extra_attributes['options_key'] => $answer->extra_attributes['options'],
            ];
        } else {
            $extracted = $this->sendExtractionRequest($question, $answer);
        }


        $this->saveAnswerData($answer, $extracted);
        $this->saveChatData($chat, $extracted);
        $this->achievementService->updateProgress($user);
        return $extracted_data;
    }

    /**
     * @throws RequestException
     */
    protected function generateNextQuestion(Chat $chat): ChatMessage
    {
        $extracted_data = $this->getExtractedData($chat);

        if ($chat->chatMessages()->count() === 0) {
            $initial = $this->getInitialQuestion();
            # generate first question
            return $this->createQuestion(
                $chat,
                $initial['group'],
                $initial['subtitle'],
                $initial['question']
            );
        }

        $lastQuestion = $chat->chatMessages()->latest()->whereNull('user_id')->first();

        // Get last question group
        $lastGroup = static::QUESTION_GROUPS[$lastQuestion->extra_attributes['group']];


        $answeredAllQuestionsInGroup = !isset($lastGroup['data']) || empty(array_diff($lastGroup['data'], array_keys($extracted_data)));

        if (!$answeredAllQuestionsInGroup) {
            # if group has a fail condition
            if (isset($lastGroup['fail'])) {
                # ask user to type the data
                return $this->createQuestion(
                    $chat,
                    $lastQuestion->extra_attributes['group'],
                    $lastGroup['subtitle'] ?? $lastQuestion->extra_attributes['subtitle'],
                    $lastGroup['fail'],
                    'system'
                );
            }
        }

        // Get next group
        $groupIndex = array_search($lastQuestion->extra_attributes['group'], array_keys(static::QUESTION_GROUPS));
        $nextGroupIndex = $groupIndex + ($answeredAllQuestionsInGroup ? 1 : 0);

        // Finish the conversation
        if (!isset(array_keys(static::QUESTION_GROUPS)[$nextGroupIndex])) {
            $this->finishConversation($extracted_data);
            return $this->createQuestion($chat, $lastQuestion->extra_attributes['group'], '', 'finish', 'system');
        }

        $nextGroupKey = array_keys(static::QUESTION_GROUPS)[$nextGroupIndex];
        $nextGroup = static::QUESTION_GROUPS[$nextGroupKey];

        // If there are no more groups
        if ($nextGroup === null) {
            throw new NotImplementedException('This should not happen');
        }

        // Check if next group has a pre-defined question
        if (isset($nextGroup['question'])) {
            # check if that question wasn't already asked
            if (!$chat->chatMessages()->notSystem()->whereNull('user_id')->where('content', $nextGroup['question'])->exists()) {
                # ask the question
                return $this->createQuestion(
                    $chat,
                    $nextGroupKey,
                    $nextGroup['subtitle'],
                    $nextGroup['question'],
                );
            }
            # otherwise, generate a question based on the data
        }

        # get data for the question
        if (!isset($nextGroup['data'])) {
            throw new NotImplementedException('This should not happen, maybe :)');
        }

        $groupKeys = collect($nextGroup['data'])
            ->filter(fn($key) => !array_key_exists($key, $extracted_data));
        $group = $groupKeys
            ->map(fn($key) => static::EXTRACTION_DATA[$key])
            ->values();

        $request = Http::post(config('app.processing_url') . '/onboarding/question', [
            'type' => $nextGroup['type'] ?? 'text',
            'history' => $this->getChatHistory($chat),
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
            $nextGroup['subtitle'] ?? $request->json()['message'],
            $request->json()['question'],
            $nextGroup['type'] ?? 'text',
            $request->json()['options'] ?? null,
            count($groupKeys) === 1 ? $groupKeys->first() : null
        );
    }

    /**
     * @throws RequestException
     */
    public function getNextQuestion(): ChatMessage
    {
        $chat = $this->getChat();
        return $this->generateNextQuestion($chat);
    }

    protected function getInitialQuestion(): array
    {
        $group = array_key_first(static::QUESTION_GROUPS);
        return static::QUESTION_GROUPS[$group] + ['group' => $group];
    }

    /**
     * @throws RequestException
     */
    public function getPreviousQuestion(User $user): ChatMessage
    {
        // if user has a chat, get the last question
        // otherwise, return the initial question
        if ($chat = $this->getUserChat($user)) {
            // get last question for the user not from the system unless its finish
            $questions = $chat->chatMessages()->whereNull('user_id')->where(fn($query) => $query
                ->notSystem()
                ->orWhere(
                    fn($query) => $query->where('type', 'system')->where('content', 'finish')
                ))->latest()->get();
            if ($questions->count() === 0) {
                return $this->generateNextQuestion($chat);
            }
            return $questions->first();
        } else {
            // if there is no chat, return initial question
            $initial = $this->getInitialQuestion();
            return ChatMessage::make([
                'content' => $this->replaceTemplate($initial['question'], user: $user),
                'type' => 'text',
                'extra_attributes' => [
                    'title' => $this->replaceTemplate($initial['title'] ?? '', user: $user),
                    'subtitle' => $this->replaceTemplate($initial['subtitle'] ?? '', user: $user)
                ]
            ]);
        }
    }

    public function getPreviousAnswer(User $user): ?ChatMessage
    {
        if ($chat = $this->getUserChat($user)) {
            return $chat->chatMessages()->latest()->whereNotNull('user_id')->first();
        }
        return null;
    }

    /**
     * @throws RequestException
     */
    public function addMessage(UploadedFile $audio = null, string $content = null, array $options = null): ChatMessage
    {
        if (!$content && !$audio && !$options) {
            throw new InvalidArgumentException('Either content or audio or options must be provided');
        }

        $user = auth()->user();

        $chat = $this->getChat();

        // create message
        return $this->chatService->create($chat, $user, [
            'content' => $content ?? ($options ? implode(', ', $options) : null),
            'audio' => $audio,
        ], [
            'options' => $options,
            'extracted' => null,
        ]);
    }
}
