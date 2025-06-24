<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserWord extends Pivot
{
    protected $table = 'user_words';
}
