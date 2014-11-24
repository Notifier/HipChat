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
use Notifier\Message\MessageInterface;
use Notifier\Processor\ProcessorInterface;
use Notifier\Recipient\RecipientInterface;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class HipChatChannel implements ChannelInterface
{
    /**
     * @var string
     */
    private $oAuthToken;

    /**
     * @var string
     */
    private $messageColor;

    /**
     * @var bool
     */
    private $messageNotify;

    /**
     * @var string
     */
    private $messageFormat;

    public function __construct($oAuthToken)
    {
        $this->oAuthToken = $oAuthToken;
        $this->messageColor = Message::COLOR_YELLOW;
        $this->messageNotify = false;
        $this->messageFormat = Message::FORMAT_HTML;
    }

    /**
     * @return string
     */
    public static function getChannelName()
    {
        return 'hipchat';
    }

    /**
     * Test if the channel can send the message given the supplied parameters.
     *
     * @param  MessageInterface   $message
     * @param  RecipientInterface $recipient
     * @return bool
     */
    public function isHandling(MessageInterface $message, RecipientInterface $recipient)
    {
        $formattedMessage = $message->getFormattedMessage($this->getChannelName());

        return !is_null($formattedMessage);
    }

    /**
     * Send the message.
     *
     * @param  MessageInterface   $message
     * @param  RecipientInterface $recipient
     * @return bool
     */
    public function send(MessageInterface $message, RecipientInterface $recipient)
    {
        $client = new Client(new OAuth2($this->oAuthToken));

        $hipChatMessage = $message->getFormattedMessage($this->getChannelName());
        $hipChatRecipient = $recipient->getFormattedRecipient($this->getChannelName());

        if ($hipChatRecipient->getUser()) {
            $this->sendUserNotification($client, $hipChatMessage, $hipChatRecipient);
        } else {
            $this->sendRoomNotification($client, $hipChatMessage, $hipChatRecipient);
        }
    }

    /**
     * @param Client           $client
     * @param HipChatMessage   $message
     * @param HipChatRecipient $recipient
     */
    private function sendUserNotification(Client $client, HipChatMessage $message, HipChatRecipient $recipient)
    {
        $userApi = new UserAPI($client);
        $userApi->privateMessageUser($recipient->getUser(), $message->getMessage());
    }

    /**
     * @param Client             $client
     * @param MessageInterface   $message
     * @param RecipientInterface $recipient
     */
    private function sendRoomNotification(Client $client, HipChatMessage $message, HipChatRecipient $recipient)
    {
        $hcMessage = new Message();
        $hcMessage->setMessage($message->getMessage());
//        $hcMessage->setColor(isset($message->hipchat_color) ? $message->hipchat_color : $this->getMessageColor());
//        $hcMessage->setMessageFormat(isset($message->hipchat_format) ? $message->hipchat_format : $this->getMessageFormat());
//        $hcMessage->setNotify(isset($message->hipchat_notify) ? $message->hipchat_notify : $this->getMessageNotify());

        $roomApi = new RoomAPI($client);
        $roomApi->sendRoomNotification($recipient->getRoom(), $hcMessage);
    }

    /**
     * @return string
     */
    public function getMessageColor()
    {
        return $this->messageColor;
    }

    /**
     * @param string $messageColor
     */
    public function setMessageColor($messageColor)
    {
        $this->messageColor = $messageColor;
    }

    /**
     * @return string
     */
    public function getMessageFormat()
    {
        return $this->messageFormat;
    }

    /**
     * @param string $messageFormat
     */
    public function setMessageFormat($messageFormat)
    {
        $this->messageFormat = $messageFormat;
    }

    /**
     * @return boolean
     */
    public function getMessageNotify()
    {
        return $this->messageNotify;
    }

    /**
     * @param boolean $messageNotify
     */
    public function setMessageNotify($messageNotify)
    {
        $this->messageNotify = $messageNotify;
    }
}
