<?php

namespace App\Models;

use MoonShine\Laravel\Models\MoonshineUser as User;
use MoonShine\Permissions\Traits\HasMoonShinePermissions;
use MoonShine\TwoFactor\Traits\TwoFactorAuthenticatable;

class MoonshineUser extends User
{
    use HasMoonShinePermissions;
    use TwoFactorAuthenticatable;
}
