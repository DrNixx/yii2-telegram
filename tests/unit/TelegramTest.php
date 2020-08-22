<?php

use onix\telegram\exceptions\TelegramException;
use onix\telegram\Telegram;
use yii\base\InvalidConfigException;

class TelegramTest extends \Codeception\Test\Unit
{
    public function testNewInstanceWithoutApiKeyParam()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('API KEY not defined!');
        new Telegram([]);
    }

    public function testNewInstanceWithInvalidApiKeyParam()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Invalid API KEY defined!');
        new Telegram(['api_key' => 'invalid-api-key-format']);
    }

    public function testEnableAdmins()
    {
        /** @var Telegram $tg */
        $tg = Yii::$app->telegram;

        $this->assertEmpty($tg->getAdminList());

        // Single
        $tg->enableAdmin(1);
        $this->assertCount(1, $tg->getAdminList());

        // Multiple
        $tg->enableAdmins([2, 3]);
        $this->assertCount(3, $tg->getAdminList());

        // Already added
        $tg->enableAdmin(2);
        $this->assertCount(3, $tg->getAdminList());

        // Integer as a string
        $tg->enableAdmin('4');
        $this->assertCount(3, $tg->getAdminList());

        // Random string
        $tg->enableAdmin('a string?');
        $this->assertCount(3, $tg->getAdminList());
    }

    public function testGetCommandsList()
    {
        /** @var Telegram $tg */
        $tg = Yii::$app->telegram;

        $commands = $tg->getCommandsList();
        $this->assertIsArray($commands);
        $this->assertNotCount(0, $commands);
    }
}
