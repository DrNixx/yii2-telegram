<?php
namespace onix\telegram\tests\unit\entities;

use onix\telegram\entities\games\CallbackGame;
use onix\telegram\entities\InlineKeyboardButton;
use onix\telegram\exceptions\TelegramException;

class InlineKeyboardButtonTest extends \Codeception\Test\Unit
{
    private static $errText = 'You must use only one of these fields: url, login_url, callback_data, switch_inline_query,' .
                ' switch_inline_query_current_chat, callback_game, pay';

    public function testInlineKeyboardButtonNoTextFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('Text cannot be blank.');
        new InlineKeyboardButton([]);
    }

    public function testInlineKeyboardButtonNoParameterFail()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage(self::$errText);
        new InlineKeyboardButton(['text' => 'message']);
    }

    public function testInlineKeyboardButtonTooManyParametersFail()
    {
        $test_params = [
            [
                'text'          => 'message',
                'url'           => 'url_value',
                'callback_data' => 'callback_data_value',
            ],
            [
                'text'                => 'message',
                'url'                 => 'url_value',
                'switch_inline_query' => 'switch_inline_query_value',
            ],
            [
                'text'                => 'message',
                'callback_data'       => 'callback_data_value',
                'switch_inline_query' => 'switch_inline_query_value',
            ],
            [
                'text'                             => 'message',
                'callback_data'                    => 'callback_data_value',
                'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value',
            ],
            [
                'text'          => 'message',
                'callback_data' => 'callback_data_value',
                'callback_game' => new CallbackGame([]),
            ],
            [
                'text'          => 'message',
                'callback_data' => 'callback_data_value',
                'pay'           => true,
            ],
        ];

        foreach ($test_params as $params) {
            $kb = new InlineKeyboardButton($params);
            $valid = array_slice($params, -1, 1);
            $invalid = array_slice($params, -2, 1);
            foreach ($valid as $key => $value) {
                $this->assertSame($value, $kb->$key);
            }

            foreach ($invalid as $key => $value) {
                $this->assertEmpty($kb->$key);
            }
        }
    }

    public function testInlineKeyboardButtonSuccess()
    {
        new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        new InlineKeyboardButton(['text' => 'message', 'callback_data' => 'callback_data_value']);
        new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']);
        //new InlineKeyboardButton(['text' => 'message', 'switch_inline_query' => '']); // Allow empty string.
        new InlineKeyboardButton([
            'text' => 'message', 'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value'
        ]);
        new InlineKeyboardButton([
            'text' => 'message', 'switch_inline_query_current_chat' => ''
        ]); // Allow empty string.
        new InlineKeyboardButton(['text' => 'message', 'callback_game' => new CallbackGame([])]);
        new InlineKeyboardButton(['text' => 'message', 'pay' => true]);
        $this->assertTrue(true);
    }

    public function testInlineKeyboardButtonCouldBe()
    {
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'url' => 'url_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'callback_data' => 'callback_data_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query' => 'switch_inline_query_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value']
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'callback_game' => new CallbackGame([])]
        ));
        $this->assertTrue(InlineKeyboardButton::couldBe(
            ['text' => 'message', 'pay' => true]
        ));

        $this->assertFalse(InlineKeyboardButton::couldBe(['no_text' => 'message']));
        $this->assertFalse(InlineKeyboardButton::couldBe(['text' => 'message']));
        $this->assertFalse(InlineKeyboardButton::couldBe(['url' => 'url_value']));
        $this->assertFalse(InlineKeyboardButton::couldBe(
            ['callback_data' => 'callback_data_value']
        ));
        $this->assertFalse(InlineKeyboardButton::couldBe(
            ['switch_inline_query' => 'switch_inline_query_value']
        ));
        $this->assertFalse(InlineKeyboardButton::couldBe(['callback_game' => new CallbackGame([])]));
        $this->assertFalse(InlineKeyboardButton::couldBe(['pay' => true]));

        $this->assertFalse(InlineKeyboardButton::couldBe([
            'url'                              => 'url_value',
            'callback_data'                    => 'callback_data_value',
            'switch_inline_query'              => 'switch_inline_query_value',
            'switch_inline_query_current_chat' => 'switch_inline_query_current_chat_value',
            'callback_game'                    => new CallbackGame([]),
            'pay'                              => true,
        ]));
    }

    public function testInlineKeyboardButtonParameterSetting()
    {
        $button = new InlineKeyboardButton(['text' => 'message', 'url' => 'url_value']);
        $this->assertSame('url_value', $button->url);
        $this->assertEmpty($button->callbackData);
        $this->assertEmpty($button->switchInlineQuery);
        $this->assertEmpty($button->switchInlineQueryCurrentChat);
        $this->assertEmpty($button->callbackGame);
        $this->assertEmpty($button->pay);

        $button->callbackData = 'callback_data_value';
        $this->assertEmpty($button->url);
        $this->assertSame('callback_data_value', $button->callbackData);
        $this->assertEmpty($button->switchInlineQuery);
        $this->assertEmpty($button->switchInlineQueryCurrentChat);
        $this->assertEmpty($button->callbackGame);
        $this->assertEmpty($button->pay);

        $button->switchInlineQuery = 'switch_inline_query_value';
        $this->assertEmpty($button->url);
        $this->assertEmpty($button->callbackData);
        $this->assertSame('switch_inline_query_value', $button->switchInlineQuery);
        $this->assertEmpty($button->switchInlineQueryCurrentChat);
        $this->assertEmpty($button->callbackGame);
        $this->assertEmpty($button->pay);

        $button->switchInlineQueryCurrentChat = 'switch_inline_query_current_chat_value';
        $this->assertEmpty($button->url);
        $this->assertEmpty($button->callbackData);
        $this->assertEmpty($button->switchInlineQuery);
        $this->assertSame('switch_inline_query_current_chat_value', $button->switchInlineQueryCurrentChat);
        $this->assertEmpty($button->callbackGame);
        $this->assertEmpty($button->pay);

        $button->callbackGame = ($callback_game = new CallbackGame([]));
        $this->assertEmpty($button->url);
        $this->assertEmpty($button->callbackData);
        $this->assertEmpty($button->switchInlineQuery);
        $this->assertEmpty($button->switchInlineQueryCurrentChat);
        $this->assertSame($callback_game, $button->callbackGame);
        $this->assertEmpty($button->pay);

        $button->pay = true;
        $this->assertEmpty($button->url);
        $this->assertEmpty($button->callbackData);
        $this->assertEmpty($button->switchInlineQuery);
        $this->assertEmpty($button->switchInlineQueryCurrentChat);
        $this->assertEmpty($button->callbackGame);
        $this->assertTrue($button->pay);
    }
}
