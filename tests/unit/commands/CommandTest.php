<?php
namespace tests\unit\commands;

use Codeception\Test\Unit;
use onix\telegram\commands\Command;
use onix\telegram\entities\Update;
use onix\telegram\Telegram;
use Yii;

class CommandTest extends Unit
{
    /**
     * Return a simple fake Update object
     *
     * @param array | null $data Pass custom data array if needed
     *
     * @return Update
     */
    private static function getFakeUpdateObject($data = null)
    {
        $data = $data ?: [
            'update_id' => mt_rand(),
            'message'   => [
                'message_id' => mt_rand(),
                'chat'       => [
                    'id' => mt_rand(),
                ],
                'date'       => time(),
            ],
        ];
        return new Update($data);
    }

    /**
     * Set the value of a private/protected property of an object
     *
     * @param object $object   Object that contains the property
     * @param string $property Name of the property who's value we want to set
     * @param mixed  $value    The value to set to the property
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public static function setObjectProperty($object, $property, $value)
    {
        $ref_object   = new \ReflectionObject($object);
        /** @noinspection PhpUnhandledExceptionInspection */
        $ref_property = $ref_object->getProperty($property);
        $ref_property->setAccessible(true);
        $ref_property->setValue($object, $value);
    }

    public function setUp(): void
    {
        /*

        $this->telegram = Yii::$app->telegram;
        $this->command_stub = $this->getMockForAbstractClass(Command::class, ['telegram' => $this->telegram]);

        //Create separate command object that contain a command config
        $this->telegram_with_config = Yii::$app->telegram;
        $this->telegram_with_config->setCommandConfig('command_name', ['config_key' => 'config_value']);
        $this->command_stub_with_config = $this->getMockBuilder(Command::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        //Set a name for the object property so that the constructor can set the config correctly
        TestHelpers::setObjectProperty($this->command_stub_with_config, 'name', 'command_name');
        $this->command_stub_with_config->__construct($this->telegram_with_config);
        */
    }

    public function testCommandHasCorrectDefaultTelegramObject()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = Yii::$app->telegram;

        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class, []);

        $this->assertSame($tg, $command_stub->telegram);
    }

    public function testCommandHasCorrectTelegramObject()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = Yii::$app->telegram;

        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class, [['telegram' => $tg]]);

        $this->assertSame($tg, $command_stub->telegram);
    }

    public function testDefaultCommandName()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertEmpty($command_stub->name);
    }

    public function testDefaultCommandDescription()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertEquals('Command description', $command_stub->description);
    }

    public function testDefaultCommandUsage()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertEquals('Command usage', $command_stub->getUsage());
    }

    public function testDefaultCommandVersion()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertEquals('1.0.0', $command_stub->version);
    }

    public function testDefaultCommandIsEnabled()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);
        $this->assertTrue($command_stub->isEnabled());
    }

    public function testDefaultCommandShownInHelp()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertTrue($command_stub->showInHelp());
    }

    public function testDefaultCommandEmptyConfig()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertSame([], $command_stub->config);
    }

    public function testDefaultCommandUpdateNull()
    {
        /** @var Command $command_stub */
        $command_stub = $this->getMockForAbstractClass(Command::class);

        $this->assertNull($command_stub->update);
    }

    public function testCommandSetUpdateAndMessage()
    {
        /** @var Command $command_stub */
        $stub = $this->getMockForAbstractClass(Command::class);

        $this->assertSame($stub, $stub->setUpdate());
        $this->assertEquals(null, $stub->update);
        $this->assertEquals(null, $stub->message);

        $this->assertSame($stub, $stub->setUpdate(null));
        $this->assertEquals(null, $stub->update);
        $this->assertEquals(null, $stub->message);

        $update  = self::getFakeUpdateObject();
        $message = $update->message;
        $stub->setUpdate($update);
        $this->assertEquals($update, $stub->update);
        $this->assertEquals($message, $stub->message);
    }

    /**
     * @return Command
     */
    private function getCommandStubWithConfig()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = Yii::$app->telegram;
        $tg->setCommandConfig('command_name', ['config_key' => 'config_value']);

        /** @var Command $stub */
        $stub = $this->getMockBuilder(Command::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        //Set a name for the object property so that the constructor can set the config correctly
        self::setObjectProperty($stub, 'name', 'command_name');
        $stub->__construct();

        return $stub;
    }

    public function testCommandWithConfigNotEmptyConfig()
    {
        try {
            $stub = $this->getCommandStubWithConfig();
            $this->assertNotEmpty($stub->config);
        } finally {
            /** @noinspection PhpUndefinedFieldInspection */
            Yii::$app->telegram->clearCommandsConfig();
        }
    }

    public function testCommandWithConfigCorrectConfig()
    {
        try {
            $stub = $this->getCommandStubWithConfig();

            $this->assertEquals(['config_key' => 'config_value'], $stub->config);
            $this->assertEquals(['config_key' => 'config_value'], $stub->getConfig(null));
            $this->assertEquals('config_value', $stub->getConfig('config_key'));
            $this->assertEquals(null, $stub->getConfig('not_config_key'));
        } finally {
            /** @noinspection PhpUndefinedFieldInspection */
            Yii::$app->telegram->clearCommandsConfig();
        }
    }
}
