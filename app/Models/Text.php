<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MoonShine\ChangeLog\Traits\HasChangeLog;

class Text extends Model
{
    use HasChangeLog;

    protected $fillable = [
        'key',
        'value',
    ];
}
