<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $history = $request->session()->get('chatbot_history', []);
        $messages = [
            [
                'role' => 'system',
                'content' => 'أنت مساعد YallaJO السياحي الرسمي. أجب بالعربية الواضحة وباختصار، ولا تخترع معلومات أو أسعار أو مواعيد غير مؤكدة.',
            ],
            ...$history,
            [
                'role' => 'user',
                'content' => $validated['message'],
            ],
        ];

        $response = Http::timeout(120)->post(
            config('ollama.url') . '/api/chat',
            [
                'model' => config('ollama.model'),
                'stream' => false,
                'messages' => $messages,
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'تعذر الاتصال بمساعد YallaJO.',
            ], 503);
        }

        $reply = $response->json('message.content');
        if (!is_string($reply) || trim($reply) === '') {
            return response()->json([
                'error' => 'لم يصل رد واضح من المساعد. حاول مرة أخرى.',
            ], 502);
        }

        $history[] = ['role' => 'user', 'content' => $validated['message']];
        $history[] = ['role' => 'assistant', 'content' => $reply];
        $request->session()->put('chatbot_history', array_slice($history, -12));

        return response()->json(['reply' => $reply]);
    }
}