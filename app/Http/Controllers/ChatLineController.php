<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatLineController extends Controller
{
    public function index()
    {
        return ChatMessage::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'content' => ['required'],
        ]);

        return ChatMessage::create($data);
    }

    public function show(ChatMessage $chatLine)
    {
        return $chatLine;
    }

    public function update(Request $request, ChatMessage $chatLine)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'content' => ['required'],
        ]);

        $chatLine->update($data);

        return $chatLine;
    }

    public function destroy(ChatMessage $chatLine)
    {
        $chatLine->delete();

        return response()->json();
    }
}
