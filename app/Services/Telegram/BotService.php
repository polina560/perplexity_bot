<?php

namespace App\Services\Telegram;

use App\Models\TelegramUser;
use App\Services\GenApiService;
use Illuminate\Container\Attributes\Singleton;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

#[Singleton]
class BotService
{
    protected Telegram $telegram;

    /**
     * @throws TelegramException
     */
    public function __construct()
    {
        $this->telegram = new Telegram(
            config('telegram.api_key'),
            config('telegram.bot_username')
        );

        $this->configure();
    }

    protected function configure(): void
    {
        $this->telegram->addCommandsPaths(
            config('telegram.commands.paths')
        );
    }

    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }

    public function newPost()
    {
        $text = app(GenApiService::class)->perplexityRequest();
        TelegramUser::each(function (TelegramUser $telegramUser) use ($text){
            Request::sendMessage([
                'chat_id' => $telegramUser->chat_id,
                'text' => $text,
                'parse_mode' => 'MarkdownV2',
            ]);
        });

        return Request::answerCallbackQuery([
            'show_alert' => false,
        ]);
    }
}
