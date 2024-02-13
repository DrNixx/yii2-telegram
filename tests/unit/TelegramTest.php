<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\unit;

use Codeception\Test\Unit;
use onix\telegram\Telegram;
use onix\telegram\tests\fixtures\UpdatesFixture;
use yii\base\InvalidConfigException;

class TelegramTest extends Unit
{
    public function _fixtures()
    {
        return [
            'updates' => [
                'class' => UpdatesFixture::class
            ]
        ];
    }

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
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

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
        $this->assertCount(4, $tg->getAdminList());

        // Random string
        $tg->enableAdmin('a string?');
        $this->assertCount(4, $tg->getAdminList());
    }

    public function testGetCommandsList()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        $commands = $tg->getCommandsList();
        verify($commands)->isArray();
        verify($commands)->arrayNotCount(0);
    }
}
