<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class StringRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return ! Auth::guest();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'project_id'     => 'required',
			'language_id'    => 'required',
			'base_string_id' => 'required',
			'text'           => 'required|min:1'
		];
	}

}
