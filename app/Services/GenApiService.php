<?php

namespace App\Services;

use App\Models\Prompt;
use App\Models\Topic;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use JsonException;

class GenApiService
{
    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function perplexityRequest()
    {
        $content = Prompt::where('systemname', 'main')->firstOrFail();
        $topics = Topic::all();
        $str = '';
        foreach ($topics as $topic) {
            $str .= $topic->topic.', ';
        }

        $response = Http::withToken(Config::string('genapi.api_key'))
            ->post(Config::string('genapi.perplexity_url'), [
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $content->prompt,
                    ],
                    [
                        'role' => 'system',
                        'content' => 'Не используй как основные следующие темы (можно: упоминать их косвенно, описывать
                            в другом ключе, с другими параметрами; например, можно использовать автора из списка
                            как основного, но рассказ должен быть про другие аспекты с ним связанные): '.$str,
                    ],
                ],
                //                'max_tokens' => 1550,
                //                'temperature' => 0.5,
                //                'top_p' => 0.9,
                //                'top_k' => 0,
                //                'presence_penalty' => 0,
                //                'frequency_penalty' => 1,
                //                'response_format' => '{"type":"text"}',
                'model' => 'sonar',
            ]);

        $status = $response->json('status');
        if (!$response->successful() || $status === 'error') {
            return $status;
        }

        $request_id = $response->json('request_id');

        $attempt = 0;
        do {
            $text = Http::withToken(Config::string('genapi.api_key'))
                ->get(Config::string('genapi.response').$request_id);
            sleep(1);
            $attempt++;
        } while ($text->json('status') === 'processing' && $attempt < 120);


        $title = explode("\n", json_decode(json_encode($text->json(), JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR)->result[0]->choices[0]->message->content, 2);
        $title = str_replace(['#', '##'], ['', ''], $title[0]);
        Topic::create(['topic' => $title]);

        return $this->escapeMarkdownV2(json_decode(json_encode($text->json(), JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR)->result[0]->choices[0]->message->content
        );
    }

    public function escapeMarkdownV2(string $text): string
    {
        $specialChars = ['_', '[', ']', '(', ')', '~', '`', '>', '+', '-', '=', '|', '{', '}', '.', '!', '#'];
        $text = str_replace(['**', '##', '# '], ['*', '#', '#'], $text);
        $escapedChars = array_map(static fn($char) => '\\'.$char, $specialChars);

        return str_replace($specialChars, $escapedChars, $text);
    }
}
