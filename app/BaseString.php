<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseString extends Model {

	protected $fillable = [ 'project_id', 'key', 'text' ];

}
