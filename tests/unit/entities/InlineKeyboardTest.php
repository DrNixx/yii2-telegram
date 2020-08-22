<?php
namespace tests\unit\entities;

use onix\telegram\entities\InlineKeyboard;
use onix\telegram\entities\InlineKeyboardButton;
use onix\telegram\exceptions\TelegramException;

class InlineKeyboardTest extends \Codeception\Test\Unit
{
    private function getRandomButton($text)
    {
        $random_params = ['url', 'callback_data', 'switch_inline_query', 'switch_inline_query_current_chat', 'pay'];
        $param         = $random_params[array_rand($random_params, 1)];
        $data          = [
            'text' => $text,
            $param => 'random_param',
        ];

        return new InlineKeyboardButton($data);
    }

    public function testInlineKeyboardDataMalformedField()
    {
        $kb = new InlineKeyboard(['inline_keyboard' => 'wrong']);
        expect('not parse invalid keyboard field', $kb->inlineKeyboard)->count(0);
    }

    public function testInlineKeyboardDataMalformedSubfield()
    {
        $kb = new InlineKeyboard(['inline_keyboard' => ['wrong']]);
        expect('not parse invalid keyboard subfield', $kb->inlineKeyboard)->count(0);
    }

    public function testInlineKeyboardSingleButtonSingleRow()
    {
        $inline_keyboard = (new InlineKeyboard(
            $this->getRandomButton('Button Text 1')
        ))->inlineKeyboard;
        self::assertSame('Button Text 1', $inline_keyboard[0][0]->text);

        $inline_keyboard = (new InlineKeyboard(
            [$this->getRandomButton('Button Text 2')]
        ))->inlineKeyboard;
        self::assertSame('Button Text 2', $inline_keyboard[0][0]->text);
    }

    public function testInlineKeyboardSingleButtonMultipleRows()
    {
        $keyboard = (new InlineKeyboard(
            $this->getRandomButton('Button Text 1'),
            $this->getRandomButton('Button Text 2'),
            $this->getRandomButton('Button Text 3')
        ))->inlineKeyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);
        self::assertSame('Button Text 2', $keyboard[1][0]->text);
        self::assertSame('Button Text 3', $keyboard[2][0]->text);

        $keyboard = (new InlineKeyboard(
            [$this->getRandomButton('Button Text 4')],
            [$this->getRandomButton('Button Text 5')],
            [$this->getRandomButton('Button Text 6')]
        ))->inlineKeyboard;
        self::assertSame('Button Text 4', $keyboard[0][0]->text);
        self::assertSame('Button Text 5', $keyboard[1][0]->text);
        self::assertSame('Button Text 6', $keyboard[2][0]->text);
    }

    public function testInlineKeyboardMultipleButtonsSingleRow()
    {
        $keyboard = (new InlineKeyboard([
            $this->getRandomButton('Button Text 1'),
            $this->getRandomButton('Button Text 2'),
        ]))->inlineKeyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);
        self::assertSame('Button Text 2', $keyboard[0][1]->text);
    }

    public function testInlineKeyboardMultipleButtonsMultipleRows()
    {
        $keyboard = (new InlineKeyboard(
            [
                $this->getRandomButton('Button Text 1'),
                $this->getRandomButton('Button Text 2'),
            ],
            [
                $this->getRandomButton('Button Text 3'),
                $this->getRandomButton('Button Text 4'),
            ]
        ))->inlineKeyboard;

        self::assertSame('Button Text 1', $keyboard[0][0]->text);
        self::assertSame('Button Text 2', $keyboard[0][1]->text);
        self::assertSame('Button Text 3', $keyboard[1][0]->text);
        self::assertSame('Button Text 4', $keyboard[1][1]->text);
    }

    public function testInlineKeyboardAddRows()
    {
        $keyboard_obj = new InlineKeyboard([]);

        $keyboard_obj->addRow($this->getRandomButton('Button Text 1'));
        $keyboard = $keyboard_obj->inlineKeyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);

        $keyboard_obj->addRow(
            $this->getRandomButton('Button Text 2'),
            $this->getRandomButton('Button Text 3')
        );
        $keyboard = $keyboard_obj->inlineKeyboard;
        self::assertSame('Button Text 2', $keyboard[1][0]->text);
        self::assertSame('Button Text 3', $keyboard[1][1]->text);

        $keyboard_obj->addRow($this->getRandomButton('Button Text 4'));
        $keyboard = $keyboard_obj->inlineKeyboard;
        self::assertSame('Button Text 4', $keyboard[2][0]->text);
    }
}
