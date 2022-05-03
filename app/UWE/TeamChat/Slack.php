<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 2015-12-22
 * Time: 01:13
 */

namespace App\UWE\TeamChat;

use Exception;
use GuzzleHttp\Client;

class Slack implements TeamChatInterface {
	private $client, $token;

	public function __construct() {
		$this->token  = env('SLACK_TOKEN');
		$this->client = new Client();
	}

	/**
	 * Send a message to a given room
	 *
	 * @param string $room
	 * @param string $postAs
	 * @param string $message
	 * @param bool $notify
	 * @param string $color
	 *
	 * @return bool
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function message( $room, $postAs = null, $message, $notify = false, $color = 'green' ) {
		$request = $this->client
			->request( 'POST', 'https://slack.com/api/chat.postMessage', [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->token
				],
				'json'    => [
					'channel' => $room,
					'text'    => $message,
				]
			] );


		return $request;
	}

	/**
	 * @param $room
	 * @param $postAs
	 * @param $message
	 * @param $feedback
	 * @param bool $notify
	 * @param string $color
	 *
	 * @return mixed
	 *
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function postFeedback( $room, $postAs = null, $message, $feedback, $notify = false, $color = 'yellow' ) {

		$actions = [];
		if ( $feedback->screenshot ) {
			$screenshot = 'https://s3.amazonaws.com/project1c-unknownworlds-com/feedback-ticket-images/' . $feedback->id . '.jpg';

			$actions = [
				[
					'name' => 'view-screenshot',
					'type' => 'button',
					'text' => 'View screenshot',
					'url'  => $screenshot
				]
			];
		}

		$request = $this->client
			->request( 'POST', 'https://slack.com/api/chat.postMessage', [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->token
				],
				'json'    => [
					'channel'     => $room,
					'text'        => $message,
					'attachments' => [
						[
							'fallback' => $message,
							'fields'   => [
								[
									'title' => 'CS#',
									'value' => $feedback->version_number,
									'short' => false
								]
							],
							'actions'  => $actions
						]
					]
				]
			] );

		return $request;

	}
}
