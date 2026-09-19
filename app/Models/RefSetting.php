<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefSetting extends Model
{
    protected $fillable = [
        'name',
        'type',
        'value',
    ];

    public $timestamps = false;
}
