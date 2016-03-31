<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {

	protected $fillable = [ 'name', 'locale' ];

//	public function setNameAttribute($name) {
//		$this->name = 'Yarr.'
//	}

	public function scopeSortedByName( $query ) {
		$query->orderBy( 'name', 'asc' );
	}

}
