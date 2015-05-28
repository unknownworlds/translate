<?php namespace App\Http\Controllers;

class PagesController extends BaseApiController {

	public function __construct() {
	}

	public function index( $name ) {
		return view( 'pages/' . $name );
	}

}
