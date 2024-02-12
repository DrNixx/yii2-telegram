<?php
namespace onix\telegram\tests\unit\entities;

use onix\telegram\entities\Keyboard;
use onix\telegram\entities\KeyboardButton;

class KeyboardTest extends \Codeception\Test\Unit
{
    public function testKeyboardDataMalformedField()
    {
        $kb = new Keyboard(['keyboard' => ['wrong']]);
        expect($kb->keyboard)->arrayToHaveCount(0, 'not parse invalid keyboard field');
    }

    public function testKeyboardDataMalformedSubfield()
    {
        $kb = new Keyboard(['keyboard' => ['wrong']]);
        expect($kb->keyboard)->arrayToHaveCount(0, 'not parse invalid keyboard subfield');
    }

    public function testKeyboardSingleButtonSingleRow()
    {
        $keyboard = (new Keyboard('Button Text 1'))->keyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);

        $keyboard = (new Keyboard(['Button Text 2']))->keyboard;
        self::assertSame('Button Text 2', $keyboard[0][0]->text);
    }

    public function testKeyboardSingleButtonMultipleRows()
    {
        $keyboard = (new Keyboard(
            'Button Text 1',
            'Button Text 2',
            'Button Text 3'
        ))->keyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);
        self::assertSame('Button Text 2', $keyboard[1][0]->text);
        self::assertSame('Button Text 3', $keyboard[2][0]->text);

        $keyboard = (new Keyboard(
            ['Button Text 4'],
            ['Button Text 5'],
            ['Button Text 6']
        ))->keyboard;
        self::assertSame('Button Text 4', $keyboard[0][0]->text);
        self::assertSame('Button Text 5', $keyboard[1][0]->text);
        self::assertSame('Button Text 6', $keyboard[2][0]->text);
    }

    public function testKeyboardMultipleButtonsSingleRow()
    {
        $keyboard = (new Keyboard(['Button Text 1', 'Button Text 2']))->keyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);
        self::assertSame('Button Text 2', $keyboard[0][1]->text);
    }

    public function testKeyboardMultipleButtonsMultipleRows()
    {
        $keyboard = (new Keyboard(
            ['Button Text 1', 'Button Text 2'],
            ['Button Text 3', 'Button Text 4']
        ))->keyboard;

        self::assertSame('Button Text 1', $keyboard[0][0]->text);
        self::assertSame('Button Text 2', $keyboard[0][1]->text);
        self::assertSame('Button Text 3', $keyboard[1][0]->text);
        self::assertSame('Button Text 4', $keyboard[1][1]->text);
    }

    public function testKeyboardWithButtonObjects()
    {
        $keyboard = (new Keyboard(
            new KeyboardButton('Button Text 1')
        ))->keyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);

        $keyboard = (new Keyboard(
            new KeyboardButton('Button Text 2'),
            new KeyboardButton('Button Text 3')
        ))->keyboard;
        self::assertSame('Button Text 2', $keyboard[0][0]->text);
        self::assertSame('Button Text 3', $keyboard[1][0]->text);

        $keyboard = (new Keyboard(
            [new KeyboardButton('Button Text 4')],
            [new KeyboardButton('Button Text 5'), new KeyboardButton('Button Text 6')]
        ))->keyboard;
        self::assertSame('Button Text 4', $keyboard[0][0]->text);
        self::assertSame('Button Text 5', $keyboard[1][0]->text);
        self::assertSame('Button Text 6', $keyboard[1][1]->text);
    }

    public function testKeyboardWithDataArray()
    {
        $resize_keyboard   = (bool) mt_rand(0, 1);
        $one_time_keyboard = (bool) mt_rand(0, 1);
        $selective         = (bool) mt_rand(0, 1);

        $keyboard_obj = new Keyboard([
            'resize_keyboard'   => $resize_keyboard,
            'one_time_keyboard' => $one_time_keyboard,
            'selective'         => $selective,
            'keyboard'          => [['Button Text 1']],
        ]);

        $keyboard = $keyboard_obj->keyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);

        self::assertSame($resize_keyboard, $keyboard_obj->resizeKeyboard);
        self::assertSame($one_time_keyboard, $keyboard_obj->oneTimeKeyboard);
        self::assertSame($selective, $keyboard_obj->selective);
    }

    public function testPredefinedKeyboards()
    {
        $keyboard_remove = Keyboard::remove();
        self::assertTrue($keyboard_remove->removeKeyboard);

        $keyboard_force_reply = Keyboard::forceReply();
        self::assertTrue($keyboard_force_reply->forceReply);
    }

    public function testKeyboardMethods()
    {
        $keyboard_obj = new Keyboard([]);

        self::assertEmpty($keyboard_obj->oneTimeKeyboard);
        self::assertEmpty($keyboard_obj->resizeKeyboard);
        self::assertEmpty($keyboard_obj->selective);

        $keyboard_obj->oneTimeKeyboard = true;
        self::assertTrue($keyboard_obj->oneTimeKeyboard);
        $keyboard_obj->oneTimeKeyboard = false;
        self::assertFalse($keyboard_obj->oneTimeKeyboard);

        $keyboard_obj->resizeKeyboard = true;
        self::assertTrue($keyboard_obj->resizeKeyboard);
        $keyboard_obj->resizeKeyboard = false;
        self::assertFalse($keyboard_obj->resizeKeyboard);

        $keyboard_obj->selective = true;
        self::assertTrue($keyboard_obj->selective);
        $keyboard_obj->selective = false;
        self::assertFalse($keyboard_obj->selective);
    }

    public function testKeyboardAddRows()
    {
        $keyboard_obj = new Keyboard([]);

        $keyboard_obj->addRow('Button Text 1');
        $keyboard = $keyboard_obj->keyboard;
        self::assertSame('Button Text 1', $keyboard[0][0]->text);

        $keyboard_obj->addRow('Button Text 2', 'Button Text 3');
        $keyboard = $keyboard_obj->keyboard;
        self::assertSame('Button Text 2', $keyboard[1][0]->text);
        self::assertSame('Button Text 3', $keyboard[1][1]->text);

        $keyboard_obj->addRow(['text' => 'Button Text 4']);
        $keyboard = $keyboard_obj->keyboard;
        self::assertSame('Button Text 4', $keyboard[2][0]->text);
    }
}
