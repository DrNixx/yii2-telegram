<?php
namespace tests\unit;

use onix\telegram\Conversation;
use onix\telegram\exceptions\TelegramException;
use tests\unit\fixtures\ConversationFixture;
use yii\BaseYii;

class ConversationTest extends \Codeception\Test\Unit
{
    public function _fixtures()
    {
        return [
            'conversation' => [
                'class' => ConversationFixture::class
            ]
        ];
    }

    public function testConversationThatDoesntExistPropertiesSetCorrectly()
    {
        $conversation = new Conversation(['user_id' => 123, 'chat_id' => 456]);
        $this->assertSame(123, $conversation->userId);
        $this->assertSame(456, $conversation->chatId);
        $this->assertNull($conversation->commandText);
    }

    public function testConversationThatExistsPropertiesSetCorrectly()
    {
        $this->tester->grabFixture('conversation');
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $this->assertSame(2, $conversation->userId);
        $this->assertSame(200, $conversation->chatId);
        $this->assertSame('command', $conversation->commandText);
    }

    public function testConversationThatDoesntExistWithoutCommand()
    {
        $conversation = new Conversation(['user_id' => 123, 'chat_id' => 456]);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->commandText);
    }

    public function testConversationThatDoesntExistWithCommand()
    {
        $this->expectException(TelegramException::class);
        new Conversation(['user_id' => 123, 'chat_id' => 456, 'command' => 'command']);
    }

    public function testNewConversationThatWillExistWithCommand()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $this->assertTrue($conversation->exists());
        $this->assertEquals('command', $conversation->commandText);
    }

    public function testStopConversation()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $this->assertTrue($conversation->exists());

        $conversation->stop();

        $conversation2 = new Conversation(['user_id' => 2, 'chat_id' => 200]);
        $this->assertFalse($conversation2->exists());
    }

    public function testCancelConversation()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $this->assertTrue($conversation->exists());

        $conversation->cancel();

        $conversation2 = new Conversation(['user_id' => 2, 'chat_id' => 200]);
        $this->assertFalse($conversation2->exists());
    }

    public function testUpdateConversationNotes()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $conversation->notes = 'newnote';

        $conversation->update();

        $conversation2 = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $this->assertSame('newnote', $conversation2->notes);

        $conversation3 = new Conversation(['user_id' => 2, 'chat_id' => 200]);
        $this->assertSame('newnote', $conversation3->notes);
    }
}
