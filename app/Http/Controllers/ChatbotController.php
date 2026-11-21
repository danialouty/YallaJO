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

        $response = Http::timeout(120)->post(
            config('ollama.url') . '/api/chat',
            [
                'model' => config('ollama.model'),
                'stream' => false,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد YallaJO السياحي. أجب بالعربية الأردنية الرسمية وباختصار ومساعدة.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $validated['message'],
                    ],
                ],
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'تعذر الاتصال بمساعد YallaJO.',
            ], 503);
        }

        return response()->json([
            'reply' => $response->json('message.content'),
        ]);
    }
}