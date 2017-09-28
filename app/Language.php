<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {

	protected $fillable = [ 'name', 'locale', 'is_rtl' ];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'is_rtl' => 'boolean',
	];

	public function scopeSortedByName( $query ) {
		$query->orderBy( 'name', 'asc' );
	}

}
