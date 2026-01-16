<?php

namespace App\Services\Telegram\Commands;


use App\Models\TelegramUser;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'Start command';

    public function execute(): ServerResponse
    {
        $chat_id = $this->getMessage()->getFrom()->getId();
        $username = $this->getMessage()->getFrom()->getUsername();

        TelegramUser::firstOrCreate(['chat_id' => $chat_id], ['chat_id' => $chat_id, 'username' => $username]);

        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Бот запущен!',
        ]);

        return Request::answerCallbackQuery([
            'show_alert' => false,
        ]);
    }
}
