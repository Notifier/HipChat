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
        $this->channel = new HipChatChannel($_ENV['HIPCHAT_APIKEY']);
    }

    public function tearDown()
    {
        unset($this->channel);
    }

    public function testIsHandlingFail()
    {
        $message = new Message(new Type());
        $recipient = new Recipient();

        $this->assertFalse($this->channel->isHandling($message, $recipient));
    }

    public function testIsHandlingSuccess()
    {
        $message = new Message(new Type());
        $message->hipchat_message = 'test';
        $recipient = new Recipient();
        $recipient->hipchat_room = 'test';

        $this->assertTrue($this->channel->isHandling($message, $recipient));
    }

    public function testSendRoom()
    {
        $message = new Message(new Type());
        $message->hipchat_message = 'test';
        $recipient = new Recipient();
        $recipient->hipchat_room = $_ENV['HIPCHAT_ROOM_ID'];

        $this->channel->send($message, $recipient);
    }

    public function testSendUser()
    {
        $message = new Message(new Type());
        $message->hipchat_message = 'test';
        $recipient = new Recipient();
        $recipient->hipchat_user = $_ENV['HIPCHAT_USER'];

        $this->channel->send($message, $recipient);
    }
}
