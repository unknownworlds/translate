<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {

	protected $table = 'log';

	protected $fillable = [ 'project_id', 'user_id', 'log_type', 'text' ];

	public function project() {
		return $this->belongsTo( 'App\Project' );
	}

}
