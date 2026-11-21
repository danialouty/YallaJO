<?php

namespace App\Services;

use App\Models\Adventure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use UnexpectedValueException;

class OllamaAdventureDayGenerator
{
    /**
     * Generate an Arabic itinerary using only the saved adventure details.
     *
     * @return array<int, array{title: string, description: string}>
     */
    public function generate(Adventure $adventure, int $dayCount): array
    {
        $response = Http::acceptJson()
            ->connectTimeout(5)
            ->timeout((int) config('ollama.timeout', 120))
            ->post(rtrim(config('ollama.url'), '/') . '/api/chat', [
                'model' => config('ollama.model'),
                'stream' => false,
                'format' => 'json',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'أنت مساعد لتخطيط برامج المغامرات في الأردن. أعد برنامجاً واقعياً باللغة العربية اعتماداً حصراً على بيانات المغامرة المقدمة. لا تخترع معلومات عن الطقس أو المواعيد أو الخدمات أو التصاريح. أعد JSON فقط بالشكل {"days":[{"title":"...","description":"..."}]}. يجب أن يحتوي days على العدد المطلوب تماماً، مع عنوان ووصف واضحين لكل يوم.',
                    ],
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'adventure' => $adventure->only([
                                'title',
                                'description',
                                'category',
                                'location',
                                'difficulty_level',
                                'duration_hours',
                                'requirements',
                            ]),
                            'day_count' => $dayCount,
                        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                    ],
                ],
            ]);

        $response->throw();

        $content = $response->json('message.content');
        if (! is_string($content) || trim($content) === '') {
            throw new UnexpectedValueException('Ollama returned an empty response.');
        }

        $result = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        $days = $result['days'] ?? null;

        if (! is_array($days) || count($days) !== $dayCount) {
            throw new UnexpectedValueException('Ollama returned an unexpected number of days.');
        }

        return array_values(array_map(function ($day) {
            if (! is_array($day) || ! is_string($day['title'] ?? null) || ! is_string($day['description'] ?? null)) {
                throw new UnexpectedValueException('Ollama returned an invalid day.');
            }

            $title = Str::limit(trim($day['title']), 255, '');
            $description = trim($day['description']);

            if ($title === '' || $description === '') {
                throw new UnexpectedValueException('Ollama returned an incomplete day.');
            }

            return ['title' => $title, 'description' => $description];
        }, $days));
    }
}
