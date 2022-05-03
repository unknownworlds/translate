<?php

namespace App\UWE\TeamChat;

interface TeamChatInterface
{
    /**
     * Send a message to a given room
     *
     * @param string $room
     * @param string $postAs
     * @param string $message
     * @param bool $notify
     * @param string $color
     * @return bool
     */
    public function message($room, $postAs, $message, $notify = false, $color = 'yellow');

    /**
     * @param $room
     * @param $postAs
     * @param $message
     * @param $feedback
     * @param bool $notify
     * @param string $color
     * @return mixed
     */
    public function postFeedback($room, $postAs, $message, $feedback, $notify = false, $color = 'yellow');
}
