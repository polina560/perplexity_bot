<?php

declare(strict_types=1);

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
	protected $table =  'telegram_user';

    protected $fillable = [
		'username',
		'chat_id',
    ];
}
