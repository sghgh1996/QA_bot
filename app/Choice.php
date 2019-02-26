<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'rank_count', 'rank_snippet', 'question_id',
    ];
}
