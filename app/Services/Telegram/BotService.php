<?php

namespace App\Services\Telegram;

use Illuminate\Container\Attributes\Singleton;
use Longman\TelegramBot\Exception\TelegramException;
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

}
