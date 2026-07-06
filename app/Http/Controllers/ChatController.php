<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $chats = Auth::user()->chats()->orderBy('updated_at', 'desc')->get();
        return view('chat.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }

        $chats = Auth::user()->chats()->orderBy('updated_at', 'desc')->get();
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        return view('chat.index', compact('chats', 'chat', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'chat_id' => 'nullable|exists:chats,id',
            'model' => 'nullable|string'
        ]);

        $user = Auth::user();
        $chatId = $request->input('chat_id');
        $chat = null;

        if ($chatId) {
            $chat = Chat::where('id', $chatId)->where('user_id', $user->id)->firstOrFail();
        } else {
            // Create a new chat and generate a title from the first message
            $title = substr($request->input('message'), 0, 30) . '...';
            $chat = $user->chats()->create(['title' => $title]);
        }

        // Save user message
        $chat->messages()->create([
            'role' => 'user',
            'message' => $request->input('message'),
        ]);

        // Build message history for the AI
        $history = $chat->messages()->orderBy('created_at', 'asc')->get()->map(function ($msg) {
            return [
                'role' => $msg->role,
                'content' => $msg->message,
            ];
        })->toArray();

        // Call AI Service
        try {
            $model = $request->input('model', 'poolside/laguna-xs-2.1:free');
            $aiResponse = $this->aiService->sendMessage($history, $model);
            
            // Save AI message
            $assistantMessage = $chat->messages()->create([
                'role' => 'assistant',
                'message' => $aiResponse,
            ]);

            $chat->touch(); // Update the updated_at timestamp

            return response()->json([
                'success' => true,
                'chat_id' => $chat->id,
                'chat_title' => $chat->title,
                'message' => $assistantMessage->message,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Chat $chat)
    {
        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['title' => 'required|string|max:255']);
        $chat->update(['title' => $request->input('title')]);

        return redirect()->back()->with('success', 'Chat renamed successfully.');
    }

    public function destroy(Chat $chat)
    {
        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }

        $chat->delete();

        return redirect()->route('chat.index')->with('success', 'Chat deleted successfully.');
    }
}
