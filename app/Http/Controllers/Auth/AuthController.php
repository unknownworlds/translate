<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialiteWrapper;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\User;
use Validator;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 */
	public function __construct() {
		$this->middleware( 'guest', [ 'except' => 'getLogout' ] );
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator( array $data ) {
		return Validator::make( $data, [
			'name'     => 'required|max:255',
			'email'    => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		] );
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 *
	 * @return User
	 */
	public function create( array $data ) {
		return User::create( [
			'name'     => $data['name'],
			'email'    => $data['email'],
			'password' => bcrypt( $data['password'] ),
		] );
	}

	public function getSocialLogin( $provider, SocialiteWrapper $socialiteWrapper ) {
		return $socialiteWrapper->redirectToProvider( $provider );
	}

	public function getSocialCallback( $provider, SocialiteWrapper $socialiteWrapper) {
		return $socialiteWrapper->handleProviderCallback( $provider );
	}

}
