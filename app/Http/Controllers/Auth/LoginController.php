<?php
/**
 * Copyright (c) Unknown Worlds Entertainment, 2016.
 * Created by Lukas Nowaczek <lukas@unknownworlds.com> <@lnowaczek>
 * Visit http://unknownworlds.com/
 * This file is a part of proprietary software.
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialiteWrapper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function redirectToProvider( $provider, SocialiteWrapper $socialiteWrapper ) {
        return $socialiteWrapper->redirectToProvider( $provider );
    }

    public function handleProviderCallback( $provider, SocialiteWrapper $socialiteWrapper ) {
        try {
            return $socialiteWrapper->handleProviderCallback( $provider );
        } catch ( QueryException $e ) {
            return redirect( '/auth/login' )->withErrors( 'Looks like the email you are trying to use is taken.' );
        } catch ( ConnectException $e ) {
            return redirect( '/auth/login' )->withErrors( 'Cannot communicate with selected login provider. Try again please.' );
        }
    }
}
