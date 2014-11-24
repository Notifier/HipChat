<?php
/**
 * This file is part of the NotifierSwiftMailer package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notifier\Tests;

use Notifier\HipChat\HipChatChannel;
use Notifier\HipChat\HipChatMessage;
use Notifier\HipChat\HipChatRecipient;
use Notifier\Message\Message;
use Notifier\Recipient\Recipient;
use Notifier\Tests\Stubs\Type;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class ChannelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HipChatChannel
     */
    protected $channel;

    public function setUp()
    {
        $this->channel = new HipChatChannel(getenv('HIPCHAT_APIKEY'));
    }

    public function tearDown()
    {
        unset($this->channel);
    }

    public function testIsHandlingFail()
    {
        $message = new Message(new Type());
        $recipient = new Recipient(1);

        $this->assertFalse($this->channel->isHandling($message, $recipient));
    }

    public function testIsHandlingSuccess()
    {
        $message = new Message(new Type());

        $hipchatMessage = new HipChatMessage();
        $hipchatMessage->setMessage('test message');
        $hipchatMessage->setRoom('test');

        $message->setFormattedMessage($hipchatMessage);

        $recipient = new Recipient(1);

        $this->assertTrue($this->channel->isHandling($message, $recipient));
    }

    public function testSendRoom()
    {
        $message = new Message(new Type());

        $hipchatMessage = new HipChatMessage();
        $hipchatMessage->setMessage('test message');
        $hipchatMessage->setRoom(getenv('HIPCHAT_ROOM_ID'));
        $message->setFormattedMessage($hipchatMessage);

        $recipient = new Recipient(1);

        $hipchatRecipient = new HipChatRecipient();
        $hipchatRecipient->setRoom(getenv('HIPCHAT_ROOM_ID'));
        $recipient->addFormattedRecipient($hipchatRecipient);

        $this->channel->send($message, $recipient);
    }

    public function testSendUser()
    {
        $message = new Message(new Type());

        $hipchatMessage = new HipChatMessage();
        $hipchatMessage->setMessage('test message');
        $message->setFormattedMessage($hipchatMessage);

        $recipient = new Recipient(1);
        $hipchatRecipient = new HipChatRecipient();
        $hipchatRecipient->setUser(getenv('HIPCHAT_USER'));
        $recipient->addFormattedRecipient($hipchatRecipient);

        $this->channel->send($message, $recipient);
    }
}
