<?php

namespace App\Console\Commands;

use App\Services\Telegram\BotService;
use Illuminate\Console\Command;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class TelegramBotCommand extends Command
{
    protected $signature = 'telegram:bot {--webhook} {--unset-webhook}';

    protected $description = 'Run Telegram bot in polling or webhook mode';

    /**
     * @throws TelegramException
     */
    public function handle(BotService $botService): void
    {
        $telegram = $botService->getTelegram();

        $commands = [
            ['command' => 'start', 'description' => 'Запуск бота'],
            ['command' => 'additional', 'description' => 'Дополнительный запрос'],
        ];
        Request::setMyCommands([
            'commands' => json_encode($commands),
        ]);

        if ($this->option('unset-webhook')) {
            $result = $telegram->deleteWebhook();
            $this->info('Webhook deleted successfully'.$result->getDescription());
        } else {
            if ($this->option('webhook')) {
                $result = $telegram->setWebhook(config('telegram.webhook.url'));
                $this->info('Webhook mode activated!'.$result->getDescription());
            } else {
                $updates = $telegram->useGetUpdatesWithoutDatabase();
                file_put_contents(public_path('storage').'/updates.txt', print_r($updates, true));

                $this->info('Polling mode activated!');
                while (true) {
                    $telegram->handleGetUpdates([]);
                }
            }
        }
    }
}
