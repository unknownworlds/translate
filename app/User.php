<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * App\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role' )->withTimestamps([] $roles
 */
class User extends Model implements AuthenticatableContract,
	AuthorizableContract,
	CanResetPasswordContract {

	use Authenticatable, Authorizable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 'email', 'password', 'theme' ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [ 'password', 'remember_token' ];

	/**
	 * @return mixed
	 */
	public function roles() {
		return $this->belongsToMany( 'App\Role' )->withTimestamps();
	}

	/**
	 * Does the user have a particular role?
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public function hasRole( $name ) {
		foreach ( $this->roles as $role ) {
			if ( $role->name == $name ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Assign a role to the user
	 *
	 * @param $role
	 *
	 * @return mixed
	 */
	public function assignRole( $role ) {
		return $this->roles()->attach( $role );
	}

	/**
	 * Remove a role from a user
	 *
	 * @param $role
	 *
	 * @return mixed
	 */
	public function removeRole( $role ) {
		return $this->roles()->detach( $role );
	}

	public function translatedStrings() {
		return $this->hasMany( 'App\TransaltedString' );
	}

	public function getSocialiteUser( $provider, $userData ) {
		$user = $this->where( 'oauth_provider', $provider )->where( 'oauth_id', $userData->id )->first();

		if ( $user === null ) {
			$user = new User();
		}

		$user->oauth_provider = $provider;
		$user->oauth_id       = $userData->id;
		$user->name           = ( ! empty( $userData->name ) ) ? $userData->name : $userData->nickname;
		$user->email          = $userData->email;
		$user->save();

		return $user;
	}

	/**
	 * Set the password to be hashed when saved
	 *
	 * @param $password
	 */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = \Hash::make($password);
	}

}
