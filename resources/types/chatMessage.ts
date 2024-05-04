export type ChatMessage = {
	id: string;
	content: string;
	type: 'text' | 'system' | 'multiple_choice'
	title: string | null;
	subtitle: string | null;
	options: string[] | null;
}
