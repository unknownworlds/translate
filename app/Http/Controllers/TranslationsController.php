<?php namespace App\Http\Controllers;

use App\BaseString;
use App\Http\Requests\StringAdminRequest;
use App\Http\Requests\StringRequest;
use App\Http\Requests\StringVoteRequest;
use App\Language;
use App\Log;
use App\Project;
use App\TranslatedString;
use App\User;
use App\Vote;
use Auth;
use Request;

class TranslationsController extends BaseApiController {

	public function __construct() {
		$this->middleware( 'auth' );
	}

	public function index() {
		$projects  = Project::orderBy( 'name' )->lists( 'name', 'id' );
		$languages = Language::orderBy( 'name' )->lists( 'name', 'id' );

		return view( 'translations/index', compact( 'projects', 'languages' ) );
	}

	public function baseStrings() {
		$baseStrings = BaseString::where( 'project_id', '=', Request::get( 'project_id' ) )->get( [
			'id',
			'key',
			'text'
		] );

		return $this->respond( $baseStrings );
	}

	public function strings() {
		$baseStrings = TranslatedString::where( 'project_id', '=', Request::get( 'project_id' ) )->where( 'language_id', '=', Request::get( 'language_id' ) )->get();

		return $this->respond( $baseStrings );
	}

	public function checkPrivileges() {
		$language = Language::findOrFail( Request::get( 'language_id' ) );

		$result = Auth::user()->hasRole( $language->name . ' admin' ) || Auth::user()->hasRole( 'Root' );

		return $this->respond( $result );
	}

	public function store( StringRequest $request ) {
		$input            = Request::all();
		$input['user_id'] = Auth::user()->id;

		$duplicate = TranslatedString::where( [
			'project_id' => Request::get( 'project_id' ),
			'language_id' => Request::get( 'language_id' ),
			'base_string_id' => Request::get( 'base_string_id' ),
			'text' => Request::get( 'text' ),
		] )->first();

		if ( $duplicate ) {
			return $this->respondValidationFailed( 'Translation already exist!' );
		}

		$string = TranslatedString::create( $input );

		$baseString = BaseString::findOrFail( Request::get( 'base_string_id' ) )->key;
		Log::create( [
			'project_id' => Request::get( 'project_id' ),
			'user_id'    => Auth::user()->id,
			'text'       => Auth::user()->name . ' translated ' . $baseString . ' to ' . Request::get( 'text' )
		] );

		return $this->respond( $string );
	}

	public function vote( StringVoteRequest $request ) {
		$string = TranslatedString::findOrFail( Request::get( 'string_id' ) );

		$vote = Vote::firstOrNew( [
			'string_id' => Request::get( 'string_id' ),
			'user_id'   => Auth::user()->id
		] );

		if ( $vote->id ) {
			return $this->respondValidationFailed( 'Already voted!' );
		}

		$vote->save();

		$voteType = ( Request::get( 'vote' ) == 1 ) ? 'up_votes' : 'down_votes';
		$string->update( [ $voteType => $string[ $voteType ] + 1 ] );

		return $this->respond( 'Vote saved.' );
	}

	public function accept( StringAdminRequest $request ) {
		$string = TranslatedString::where( [
			'id'          => Request::get( 'string_id' ),
			'language_id' => Request::get( 'language_id' )
		] )->firstOrFail();

		TranslatedString::where( [
			'base_string_id' => Request::get( 'base_string_id' ),
			'language_id'    => Request::get( 'language_id' )
		] )->update( [
			'is_accepted' => false
		] );

		$string->update( [ 'is_accepted' => true ] );

		return $this->respond( 'String accepted.' );
	}

	public function trash( StringAdminRequest $request ) {
		$string = TranslatedString::where( [
			'id'          => Request::get( 'string_id' ),
			'language_id' => Request::get( 'language_id' )
		] )->firstOrFail();

		$string->delete();

		return $this->respond( 'String deleted.' );
	}

	public function users() {

		$users = TranslatedString::join( 'users', 'users.id', '=', 'translated_strings.user_id' )
		                         ->selectRaw( 'count(*) AS count, user_id, name' )
		                         ->where( 'project_id', '=', Request::get( 'project_id' ) )
		                         ->where( 'language_id', '=', Request::get( 'language_id' ) )
		                         ->groupBy( 'translated_strings.user_id' )
		                         ->limit( 25 )
		                         ->get();

		return $this->respond( $users );
	}

	public function admins() {

		$language = Language::where( 'id', '=', Request::get( 'language_id' ) )->first();

		$admins = User::whereHas( 'roles', function ( $q ) use ( $language ) {
			$q->where( 'name', '=', $language->name . ' admin' );
		} )->get( [ 'id', 'name' ] );

		return $this->respond( $admins );
	}

}
