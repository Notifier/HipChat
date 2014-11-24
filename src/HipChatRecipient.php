<?php
/**
 * This file is part of the NotifierMail package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notifier\HipChat;

use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
use GorkaLaucirica\HipchatAPIv2Client\API\UserAPI;
use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\Model\Message;
use Notifier\Channel\ChannelInterface;
use Notifier\Message\FormattedMessageInterface;
use Notifier\Message\MessageInterface;
use Notifier\Processor\ProcessorInterface;
use Notifier\Recipient\FormattedRecipientInterface;
use Notifier\Recipient\RecipientInterface;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class HipChatRecipient implements FormattedRecipientInterface
{
    /**
     * @var int
     */
    private $room;

    /**
     * @var string
     */
    private $user;

    /**
     * @return string
     */
    public function getChannelName()
    {
        return 'hipchat';
    }

    /**
     * @return int
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param int $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
