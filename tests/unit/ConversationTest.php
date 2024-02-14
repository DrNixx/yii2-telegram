<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\unit;

use Codeception\Test\Unit;
use onix\telegram\Conversation;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\models\Conversation as ConversationRepo;
use onix\telegram\tests\fixtures\ConversationFixture;
use onix\telegram\tests\UnitTester;

class ConversationTest extends Unit
{
    public function _fixtures()
    {
        return [
            'conversation' => [
                'class' => ConversationFixture::class
            ]
        ];
    }

    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => ConversationFixture::class,
                'dataFile' => codecept_data_dir() . 'conversation.php'
            ],
        ]);
    }

    public function testConversationThatDoesntExistPropertiesSetCorrectly()
    {
        $conversation = new Conversation(['user_id' => 123, 'chat_id' => 456]);
        verify($conversation->userId)->same(123);
        verify($conversation->chatId)->same(456);
        verify($conversation->commandText)->null();

        $this->tester->dontSeeRecord(ConversationRepo::class, ['userId' => 123, 'chatId' => 456]);
    }

    public function testConversationThatExistsPropertiesSetCorrectly()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        verify($conversation->userId)->same(2);
        verify($conversation->chatId)->same(200);
        verify($conversation->command)->same('command');

        $this->tester->seeRecord(ConversationRepo::class, ['userId' => 2, 'chatId' => 200]);
    }

    public function testConversationThatDoesntExistWithoutCommand()
    {
        $conversation = new Conversation(['user_id' => 123, 'chat_id' => 456]);
        verify($conversation->exists())->false();
        verify($conversation->commandText)->null();
    }

    public function testConversationThatDoesntExistWithCommand()
    {
        $this->expectException(TelegramException::class);
        new Conversation(['user_id' => 123, 'chat_id' => 456, 'command' => 'command']);
    }

    public function testNewConversationThatWillExistWithCommand()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        verify($conversation->exists())->true();
        verify($conversation->commandText)->equals('command');
    }

    public function testStopConversation()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        verify($conversation->exists())->true();
        $conversation->stop();

        $conversation2 = new Conversation(['user_id' => 2, 'chat_id' => 200]);
        verify($conversation2->exists())->false();
    }

    public function testCancelConversation()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        verify($conversation->exists())->true();

        $conversation->cancel();

        $conversation2 = new Conversation(['user_id' => 2, 'chat_id' => 200]);
        verify($conversation2->exists())->false();
    }

    public function testUpdateConversationNotes()
    {
        $conversation = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        $conversation->notes = 'newnote';
        $conversation->update();

        $conversation2 = new Conversation(['user_id' => 2, 'chat_id' => 200, 'command' => 'command']);
        verify($conversation2->notes)->same('newnote');

        $conversation3 = new Conversation(['user_id' => 2, 'chat_id' => 200]);
        verify($conversation3->notes)->same('newnote');
    }
}
