<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle( $request, Closure $next ) {
		// Disable CSRF check on following routes
		$skip = array(
			'api/strings/translation-file'
		);

		foreach ( $skip as $key => $route ) {
			if ( $request->is( $route ) ) {
				return parent::addCookieToResponse( $request, $next( $request ) );
			}
		}

		// handle all other routes
		return parent::handle( $request, $next );
	}

}
