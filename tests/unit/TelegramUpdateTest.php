<?php

namespace onix\telegram\tests\unit;

use onix\telegram\models\Message;
use onix\telegram\models\TelegramUpdate;
use onix\telegram\Telegram;
use onix\telegram\tests\fixtures\UpdatesFixture;
use onix\telegram\tests\UnitTester;

class TelegramUpdateTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    public function _before(): void
    {
        $this->tester->haveFixtures([
            'updates' => [
                'class' => UpdatesFixture::class
            ]
        ]);
    }

    public function getTelegram(): Telegram
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        return $tg;
    }

    private function loadData(string $name): string
    {
        $dir = codecept_data_dir();
        return file_get_contents( "{$dir}updates/{$name}");
    }

    private function runHandler(string $name): ?TelegramUpdate
    {
        $tg = $this->getTelegram();
        $data = $this->loadData("{$name}.json");
        $result = $tg->handle($data);
        if ($result) {
            $updateId = $tg->getLastUpdateId();
            verify($updateId)->notNull();
            return $this->tester->grabRecord(TelegramUpdate::class, ['_id' => $updateId]);
        }

        return null;
    }

    private function getMessage(TelegramUpdate $update): Message
    {
        $message = $this->tester->grabRecord(
            Message::class,
            ['chatId' => $update->chatId, 'id' => $update->messageId]
        );

        verify($message)->notNull();

        return $message;
    }

    public function testHandleMessage()
    {
        $update = $this->runHandler('message');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->text)->notEmpty();
    }

    public function testHandleMessageWithReply()
    {
        $update = $this->runHandler('message_reply');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->replyToMessage)->notEmpty();
    }

    public function testHandleMessageWithEntities()
    {
        $update = $this->runHandler('entities');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->entities)->notNull();
    }

    public function testHandleChannelChatCreated()
    {
        $update = $this->runHandler('channel_chat_created');
        verify($update)->instanceOf(TelegramUpdate::class);
    }

    public function testHandleChannelPost()
    {
        $update = $this->runHandler('channel_post');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->channelPostId)->isInt();
    }

    public function testHandleMessageAutoDeleteTimerChanged()
    {
        $update = $this->runHandler('message_auto_delete_timer_changed');
        verify($update)->instanceOf(TelegramUpdate::class);
    }

    public function testHandleAudio()
    {
        $update = $this->runHandler('audio');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->audio)->notNull();
    }

    public function testHandleAnimation()
    {
        $update = $this->runHandler('animation');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->animation)->notNull();
    }

    public function testHandleDocument()
    {
        $update = $this->runHandler('document');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->document)->notNull();
    }

    public function testHandleVideo()
    {
        $update = $this->runHandler('video');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->video)->notNull();
    }

    public function testHandleCallbackQuery()
    {
        $update = $this->runHandler('callback_query');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->callbackQueryId)->isString();
    }

    public function testHandleCallbackQueryComplex()
    {
        $update = $this->runHandler('callback_query_complex');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->callbackQueryId)->isString();
    }

    public function testHandleChatMember()
    {
        $update = $this->runHandler('chat_member');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->chatMemberId)->isObject();
    }

    public function testHandleMyChatMember()
    {
        $update = $this->runHandler('my_chat_member');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->myChatMemberId)->isObject();
    }

    public function testHandleNewChatMember()
    {
        $update = $this->runHandler('new_chat_members');
        verify($update)->instanceOf(TelegramUpdate::class);
        $message = $this->getMessage($update);
        verify($message->newChatMembers)->notNull();
    }

    public function testHandleChatJoinRequest()
    {
        $update = $this->runHandler('chat_join_request');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->chatJoinRequestId)->isObject();
    }

    public function testHandleChosenInlineResult()
    {
        $update = $this->runHandler('chosen_inline_result');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->chosenInlineResultId)->isString();
    }

    public function testHandleEditedMessage()
    {
        $update = $this->runHandler('edited_message');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->editedMessageId)->isObject();
    }

    public function testHandleEditedChannelPost()
    {
        $update = $this->runHandler('edited_channel_post');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->editedChannelPostId)->isObject();
    }

    public function testHandleInlineQuery()
    {
        $update = $this->runHandler('inline_query');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->inlineQueryId)->isInt();
    }

    public function testHandlePoll()
    {
        $update = $this->runHandler('poll');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->pollId)->isString();
    }

    public function testHandlePollAnswer()
    {
        $update = $this->runHandler('poll_answer');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->pollAnswerId)->isObject();
    }

    public function testHandlePreCheckoutQuery()
    {
        $update = $this->runHandler('pre_checkout_query');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->preCheckoutQueryId)->isString();
    }

    public function testHandleShippingQuery()
    {
        $update = $this->runHandler('shipping_query');
        verify($update)->instanceOf(TelegramUpdate::class);
        verify($update->shippingQueryId)->isString();
    }
}