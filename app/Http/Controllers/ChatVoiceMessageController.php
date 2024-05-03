<?php

namespace App\Http\Controllers;

use App\Models\ChatVoiceMessage;
use Illuminate\Http\Request;

class ChatVoiceMessageController extends Controller
{
    public function index()
    {
        return ChatVoiceMessage::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'filename' => ['required'],
            'transcription' => ['nullable'],
            'chat_message_id' => ['required', 'exists:chat_messages'],
        ]);

        return ChatVoiceMessage::create($data);
    }

    public function show(ChatVoiceMessage $chatVoiceMessage)
    {
        return $chatVoiceMessage;
    }

    public function update(Request $request, ChatVoiceMessage $chatVoiceMessage)
    {
        $data = $request->validate([
            'filename' => ['required'],
            'transcription' => ['nullable'],
            'chat_message_id' => ['required', 'exists:chat_messages'],
        ]);

        $chatVoiceMessage->update($data);

        return $chatVoiceMessage;
    }

    public function destroy(ChatVoiceMessage $chatVoiceMessage)
    {
        $chatVoiceMessage->delete();

        return response()->json();
    }
}
