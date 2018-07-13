<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model {

	use SoftDeletes;

	protected $fillable = [ 'name', 'file_handler', 'api_key' ];

	protected $dates = ['deleted_at'];

}
