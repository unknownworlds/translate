<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminWhiteboard extends Model
{
    protected $fillable = ['project_id', 'language_id', 'user_id', 'text'];

    public function user()
    {
        return $this->belongsTo('App\User')->select(['id', 'name']);
    }
}
