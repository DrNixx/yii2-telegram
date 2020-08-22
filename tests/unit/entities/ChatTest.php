<?php
namespace tests\unit\entities;

use onix\telegram\entities\Chat;

class ChatTest extends \Codeception\Test\Unit
{
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

    private static function getFakeChatObject(array $data = [])
    {
        ($data === null) && $data = [];

        return new Chat($data + self::$chat_template);
    }

    public function testChatType()
    {
        $chat = self::getFakeChatObject();
        self::assertEquals('private', $chat->type);

        $chat = self::getFakeChatObject(['id' => -123, 'type' => null]);
        self::assertEquals('group', $chat->type);

        $chat = self::getFakeChatObject(['id' => -123, 'type' => 'supergroup']);
        self::assertEquals('supergroup', $chat->type);

        $chat = self::getFakeChatObject(['id' => -123, 'type' => 'channel']);
        self::assertEquals('channel', $chat->type);
    }

    public function testIsChatType()
    {
        $chat = self::getFakeChatObject();
        self::assertTrue($chat->isPrivateChat());

        $chat = self::getFakeChatObject(['id' => -123, 'type' => null]);
        self::assertTrue($chat->isGroupChat());

        $chat = self::getFakeChatObject(['id' => -123, 'type' => 'supergroup']);
        self::assertTrue($chat->isSuperGroup());

        $chat = self::getFakeChatObject(['id' => -123, 'type' => 'channel']);
        self::assertTrue($chat->isChannel());
    }

    public function testTryMention()
    {
        // Username.
        $chat = self::getFakeChatObject([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Taylor',
            'username' => 'jtaylor'
        ]);
        self::assertEquals('@jtaylor', $chat->tryMention());

        // First self.
        $chat = self::getFakeChatObject(['id' => 1, 'first_name' => 'John', 'last_name' => null, 'username' => null]);
        self::assertEquals('John', $chat->tryMention());

        // First self Last name.
        $chat = self::getFakeChatObject([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Taylor',
            'username' => null
        ]);

        self::assertEquals('John Taylor', $chat->tryMention());

        // Non-self chat should return title.
        $chat = self::getFakeChatObject(['id' => -123, 'type' => null, 'title' => 'My group chat']);
        self::assertSame('My group chat', $chat->tryMention());
    }
}
