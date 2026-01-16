<?php
return [
    'api_key' => env('TELEGRAM_BOT_TOKEN'),
    'bot_username' => env('TELEGRAM_BOT_USERNAME'),
    'webhook' => [
        'url' => env('TELEGRAM_WEBHOOK_URL'),
    ],
    'cat_id' => env('TELEGRAM_CHAT_ID'),
    'commands' => [
        'paths' => [
            app_path('Services/Telegram/Commands'),
        ],
    ],
];
?>
