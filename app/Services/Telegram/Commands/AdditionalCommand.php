<?php

namespace App\Services\Telegram\Commands;

use App\Models\Prompt;
use App\Services\GenApiService;
use Illuminate\Http\Client\ConnectionException;
use JsonException;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class AdditionalCommand extends UserCommand
{
    protected $name = 'additional';

    protected $description = 'Additional request';

    /**
     * @throws TelegramException
     * @throws ConnectionException
     * @throws JsonException
     */
    public function execute(): ServerResponse
    {
        $chat_id = $this->getMessage()->getFrom()->getId();

        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Подождите 15 секунд',
        ]);

//        $text = app(GenApiService::class)->perplexityRequest();
        $content = Prompt::where('systemname', 'main')->firstOrFail();
//        $text = $this->escapeMarkdownV2(json_decode($content->negative_prompt, false)->result[0]->choices[0]->message->content)

        $tmp = '**Иван Константинович Айвазовский \(1817–1900\)** \— выдающийся русский художник\-маринист, чьи полотна мастерски передают динамику моря, световые эффекты и драматические бури, сделав его одним из самых узнаваемых мастеров XIX века в изобразительном искусстве\.\[5\]';

        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => $this->escapeMarkdownV2(json_decode($content->negative_prompt, false)->result[0]->choices[0]->message->content),
//            'text' => $tmp,
            'parse_mode' => 'MarkdownV2'
        ]);

        return Request::answerCallbackQuery([
            'show_alert' => false,
        ]);
    }

    public function escapeMarkdownV2(string $text): string
    {
        $specialChars = ['_', '[', ']', '(', ')', '~', '`', '>', '+', '-', '=', '|', '{', '}', '.', '!', '#'];
        $text = str_replace(array('**', '##', '# '), array('*', '#', '#'), $text);
        $escapedChars = array_map(fn($char) => '\\' . $char, $specialChars);
        return str_replace($specialChars, $escapedChars, $text);
    }
}
