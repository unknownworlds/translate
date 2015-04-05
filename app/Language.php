<?php namespace App;

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
