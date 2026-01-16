<?php

namespace App\Services\Telegram\Commands;


use App\Models\TelegramUser;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class AdditionalRequestCommand extends UserCommand
{
    protected $name = 'additional';
    protected $description = 'Additional request';

    public function execute(): ServerResponse
    {
        $chat_id = $this->getMessage()->getFrom()->getId();

        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Кастомный запрос!',
        ]);

        return Request::answerCallbackQuery([
            'show_alert' => false,
        ]);
    }
}
