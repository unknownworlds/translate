<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

	protected $table = 'log';

	protected $fillable = [ 'project_id', 'user_id', 'log_type', 'text' ];

	public function project() {
		return $this->belongsTo( 'App\Project' );
	}

}
