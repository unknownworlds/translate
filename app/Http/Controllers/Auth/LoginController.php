<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialiteWrapper;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Database\QueryException;
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
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function redirectToProvider($provider, SocialiteWrapper $socialiteWrapper)
    {
        return $socialiteWrapper->redirectToProvider($provider);
    }

    public function handleProviderCallback($provider, SocialiteWrapper $socialiteWrapper)
    {
        try {
            return $socialiteWrapper->handleProviderCallback($provider);
        } catch (QueryException $e) {
            Bugsnag::notifyException($e);
            return redirect('/login')->withErrors('Looks like the email you are trying to use is taken. This might happen 
            if you used our own account system instead of an external login provider.');
        } catch (ConnectException $e) {
            Bugsnag::notifyException($e);
            return redirect('/login')->withErrors('Cannot communicate with selected login provider. Try again please.');
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            return redirect('/login')->withErrors('Unknown error. Try again please.');
        }
    }
}
