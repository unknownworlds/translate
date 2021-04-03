<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    protected $table = 'log';

    protected $fillable = ['project_id', 'user_id', 'language_id', 'log_type', 'text'];

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

}
