<?php

use App\Services\GenApiService;
use App\Services\Telegram\BotService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

if (app()->environment('local')) {
    Schedule::command('telescope:prune')->daily();
}

Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:30');

Artisan::command('send:newPost', function (BotService $botService): void {
    $botService->newPost();
});

Schedule::command('send:newPost')->daily()->at('12:00');
//Schedule::command('send:newPost')->everyMinute();
