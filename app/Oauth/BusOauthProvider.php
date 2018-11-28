<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 20.03.2018
 * Time: 17:12
 */

/*
 *
 */

namespace App\Oauth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class BusOauthProvider extends AbstractProvider implements ProviderInterface {
	/**
	 * Get the authentication URL for the provider.
	 *
	 * @param  string $state
	 *
	 * @return string
	 */
	protected function getAuthUrl( $state ) {
		return $this->buildAuthUrlFromBase( $this->getUrl( 'oauth/authorize' ), $state );
	}

	/**
	 * Get the token URL for the provider.
	 *
	 * @return string
	 */
	protected function getTokenUrl() {
		return $this->getUrl( 'oauth/token' );
	}

	/**
	 * Get the raw user for the given access token.
	 *
	 * @param  string $token
	 *
	 * @return array
	 */
	protected function getUserByToken( $token ) {
		$response = $this->getHttpClient()->get( $this->getUrl( 'api/user' ), [
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
			],
		] );

		return json_decode( $response->getBody(), true );
	}

	/**
	 * Get the POST fields for the token request.
	 *
	 * @param  string  $code
	 * @return array
	 */
	protected function getTokenFields( $code ) {
		return array_merge( parent::getTokenFields( $code ), [
			'grant_type' => 'authorization_code',
		] );
	}

	/**
	 * Map the raw user array to a Socialite User instance.
	 *
	 * @param  array $user
	 *
	 * @return \Laravel\Socialite\Two\User
	 */
	protected function mapUserToObject( array $user ) {
		return ( new User )->setRaw( $user )->map( [
			'id'    => $user['id'],
			'email' => $user['email'],
			'name'  => $user['name'],
//			'display_name' => $user['display_name'],
//			'avatar'   => ! empty( $user['images'] ) ? $user['images'][0]['url'] : null,
		] );
	}

	/**
	 * @param $location
	 *
	 * @return string
	 */
	private function getUrl( $location ) {
		return env( 'BUS_HOST' ) . '/' . $location;
	}
}