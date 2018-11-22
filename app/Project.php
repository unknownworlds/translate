<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model {

	use SoftDeletes;

	protected $fillable = [ 'name', 'data_input_handler', 'data_output_handler', 'api_key', 'output_template' ];

	protected $dates = [ 'deleted_at' ];

}
