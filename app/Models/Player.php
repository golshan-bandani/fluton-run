<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['name', 'token', 'best_score', 'cleared', 'banned'];
    protected $hidden   = ['token'];
    protected $casts    = ['banned' => 'boolean'];
}
