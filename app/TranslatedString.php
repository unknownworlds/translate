<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslatedString extends Model {

	use SoftDeletes;

	protected $fillable = [
		'project_id', 'language_id', 'base_string_id', 'user_id', 'text', 'up_votes', 'down_votes', 'is_accepted',
        'deleted_at', 'accepted_by', 'deleted_by'
    ];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [ 'deleted_at' ];

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function language() {
		return $this->belongsTo('App\Language');
	}

	public function project() {
		return $this->belongsTo('App\Project');
	}
}
