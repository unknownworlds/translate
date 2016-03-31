<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016. 
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software. 
 */

namespace App\Http\Controllers;

use Auth;

class ThemesController extends Controller {

	public function __construct() {
		$this->middleware( 'auth' );
	}

	public function index( $name ) {

		switch ( $name ) {
			case 'dark':
				$theme = 'dark';
				break;
			default:
				$theme = 'light';
		}

		Auth::user()->update( [ 'theme' => $theme ] );

		return redirect()->back();
	}

}
