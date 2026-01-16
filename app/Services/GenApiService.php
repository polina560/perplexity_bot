<?php

namespace App\Services;

use App\Models\Prompt;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class GenApiService
{
    /**
     * @throws ConnectionException
     * @throws \JsonException
     */
    public function perplexityRequest()
    {
        $content = Prompt::where('systemname', 'main')->firstOrFail();
        $response = Http::withToken(Config::string('genapi.api_key'))
            ->post(Config::string('genapi.perplexity_url'), [
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => 'Напиши небольшое исследование-обзор какого-либо деятеля, произведения или события из сферы искусства. Опирайся только на подтвержденные источники, сделай обзор довольно полным и интересным. Приведи примеры статей, работ и исследований, посвященных этой теме, а также обязательно приведи визуальные примеры по данной теме (это могут быть файлы изображений, ссылки и т.п.)',
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

//        sleep(10);
        $attempt = 0;
        do {
            $text = Http::withToken(Config::string('genapi.api_key'))
                ->get(Config::string('genapi.response') . $request_id);
            sleep(1);
            $attempt++;
        } while ($text->json('status') === 'processing' && $attempt < 120);

        $content->negative_prompt = json_encode($text->json(), JSON_THROW_ON_ERROR);
        $content->save();

//        return $text->json();

        $proc_text = $this->escapeMarkdownV2(json_decode($content->negative_prompt, false)->result[0]->choices[0]->message->content);

        return $proc_text;
    }

    public function escapeMarkdownV2(string $text): string
    {
        $specialChars = ['_', '[', ']', '(', ')', '~', '`', '>', '+', '-', '=', '|', '{', '}', '.', '!'];
        $escapedChars = array_map(fn($char) => '\\\\' . $char, $specialChars);
        return str_replace($specialChars, $escapedChars, $text);
    }
}
