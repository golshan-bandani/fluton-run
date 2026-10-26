<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['difficulty', 'question', 'options', 'answer', 'explanation'];
    protected $casts    = ['options' => 'array'];
}
