<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 2015-09-16
 * Time: 22:54
 */

namespace App\Services;

use App\User;
use Auth;
use Socialite;


class SocialiteWrapper {
	/**
	 * @var UserRepository
	 */
	private $users;

	/**
	 * @param UserRepository|User $users
	 */
	public function __construct( User $users ) {
		$this->users = $users;
	}

	public function handleProviderCallback( $provider ) {
		$user = $this->users->getSocialiteUser( $provider, $this->getUser( $provider ) );
		Auth::login( $user, true );

		return redirect( '/' );
	}

	/**
	 * @param $provider
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function redirectToProvider( $provider = null ) {
		$this->checkProviderExistence( $provider );

		return Socialite::driver( $provider )->redirect();
	}

	/**
	 * @param $provider
	 *
	 * @return \Laravel\Socialite\Contracts\User
	 */
	private function getUser( $provider = null ) {
		$this->checkProviderExistence( $provider );

		return Socialite::driver( $provider )->user();
	}

	/**
	 * @param $provider
	 * TODO: Extract to constructor?
	 */
	public function checkProviderExistence( $provider ) {
		if ( ! config( "services.$provider" ) ) {
			abort( '404' );
		}
	}

}