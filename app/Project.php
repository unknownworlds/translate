<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

	protected $fillable = [ 'name', 'file_handler', 'api_key' ];

}
