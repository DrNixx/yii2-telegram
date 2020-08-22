<?php
namespace tests\unit\entities;

use onix\telegram\entities\Message;

class MessageTest extends \Codeception\Test\Unit
{
    /**
     * Data template of a user.
     *
     * @var array
     */
    private static $user_template = [
        'id'         => 1,
        'first_name' => 'first',
        'last_name'  => 'last',
        'username'   => 'user',
    ];

    /**
     * Data template of a chat.
     *
     * @var array
     */
    private static $chat_template = [
        'id'                             => 1,
        'first_name'                     => 'first',
        'last_name'                      => 'last',
        'username'                       => 'name',
        'type'                           => 'private',
        'all_members_are_administrators' => false,
    ];

    public static function getFakeMessageObject(array $message_data = [], array $user_data = [], array $chat_data = [])
    {
        ($message_data === null) && $message_data = [];
        ($user_data === null) && $user_data = [];
        ($chat_data === null) && $chat_data = [];

        return new Message($message_data + [
                'message_id' => mt_rand(),
                'from'       => $user_data + self::$user_template,
                'chat'       => $chat_data + self::$chat_template,
                'date'       => time(),
                'text'       => 'dummy',
            ]);
    }

    public function testTextAndCommandRecognise()
    {
        // /command
        $message = self::getFakeMessageObject(['text' => '/help']);
        self::assertEquals('/help', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals('/help', $message->messageText);
        self::assertEquals('/help', $message->text);
        self::assertEquals('', $message->getMessageText(true));

        // text
        $message = self::getFakeMessageObject(['text' => 'some text']);
        self::assertNull($message->fullCommand);
        self::assertNull($message->command);
        self::assertEquals('some text', $message->text);
        self::assertEquals('some text', $message->getMessageText(true));

        // /command@bot
        $message = self::getFakeMessageObject(['text' => '/help@testbot']);
        self::assertEquals('/help@testbot', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals('/help@testbot', $message->messageText);
        self::assertEquals('', $message->getMessageText(true));

        // /commmad text
        $message = self::getFakeMessageObject(['text' => '/help some text']);
        self::assertEquals('/help', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals('/help some text', $message->messageText);
        self::assertEquals('some text', $message->getMessageText(true));

        // /command@bot some text
        $message = self::getFakeMessageObject(['text' => '/help@testbot some text']);
        self::assertEquals('/help@testbot', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals('/help@testbot some text', $message->messageText);
        self::assertEquals('some text', $message->getMessageText(true));

        // /commmad\n text
        $message = self::getFakeMessageObject(['text' => "/help\n some text"]);
        self::assertEquals('/help', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals("/help\n some text", $message->messageText);
        self::assertEquals(' some text', $message->getMessageText(true));

        // /command@bot\nsome text
        $message = self::getFakeMessageObject(['text' => "/help@testbot\nsome text"]);
        self::assertEquals('/help@testbot', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals("/help@testbot\nsome text", $message->messageText);
        self::assertEquals('some text', $message->getMessageText(true));

        // /command@bot \nsome text
        $message = self::getFakeMessageObject(['text' => "/help@testbot \nsome text"]);
        self::assertEquals('/help@testbot', $message->fullCommand);
        self::assertEquals('help', $message->command);
        self::assertEquals("/help@testbot \nsome text", $message->messageText);
        self::assertEquals("\nsome text", $message->getMessageText(true));
    }

    public function testGetType()
    {
        $message = self::getFakeMessageObject(['text' => null]);
        self::assertSame('message', $message->getType());

        $message = self::getFakeMessageObject(['text' => '/help']);
        self::assertSame('command', $message->getType());

        $message = self::getFakeMessageObject(['text' => 'some text']);
        self::assertSame('text', $message->getType());
    }
}
