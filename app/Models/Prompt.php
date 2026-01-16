<?php

declare(strict_types=1);

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model
{
	protected $table =  'prompt';

    protected $fillable = [
		'systemname',
		'prompt',
		'negative_prompt',
    ];
}
