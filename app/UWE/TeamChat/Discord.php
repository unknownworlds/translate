<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 2015-12-22
 * Time: 01:13
 */

namespace App\UWE\TeamChat;

use Exception;
use RestCord\DiscordClient;

class Discord implements TeamChatInterface {
	private $client;

	public function __construct() {
		$this->client = new DiscordClient( [ 'token' => env('DISCORD_TOKEN') ] );
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
	 * @return array
	 */
	public function message( $room, $postAs = null, $message, $notify = false, $color = 'green' ) {
		$request = $this->client->channel->createMessage( [
			'channel.id' => $room,
			'content'    => $message
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
	 */
	public function postFeedback( $room, $postAs = null, $message, $feedback, $notify = false, $color = 'yellow' ) {
		$screenshot = null;

		if ( $feedback->screenshot ) {
			$screenshot = [
				'url' => 'https://s3.amazonaws.com/project1c-unknownworlds-com/feedback-ticket-images/' . $feedback->id . '.jpg'
			];
		}

		$request = $this->client->channel->createMessage( [
			'channel.id' => $room,
			'content'    => $message,
			'embed'      => [
				'image'  => $screenshot,
				'fields' => [
					[
						'name'  => 'CS#',
						'value' => $feedback->version_number
					]
				]
			]
		] );

		return $request;

	}
}
