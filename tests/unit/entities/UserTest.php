<?php
namespace tests\unit\entities;

use Codeception\Test\Unit;
use onix\telegram\entities\User;

class UserTest extends Unit
{
    public function testInstance()
    {
        $user = new User(['id' => 1]);
        self::assertInstanceOf(User::class, $user);
    }

    public function testGetId()
    {
        $user = new User(['id' => 123]);
        self::assertEquals(123, $user->id);
    }

    public function testTryMention()
    {
        // Username
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'jtaylor']);
        self::assertEquals('@jtaylor', $user->tryMention());

        // First name.
        $user = new User(['id' => 1, 'first_name' => 'John']);
        self::assertEquals('John', $user->tryMention());

        // First and Last name.
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor']);
        self::assertEquals('John Taylor', $user->tryMention());
    }

    public function testEscapeMarkdown()
    {
        // Username.
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'j_taylor']);
        self::assertEquals('@j_taylor', $user->tryMention());
        self::assertEquals('@j\_taylor', $user->tryMention(true));

        // First name.
        $user = new User(['id' => 1, 'first_name' => 'John[']);
        self::assertEquals('John[', $user->tryMention());
        self::assertEquals('John\[', $user->tryMention(true));

        // First and Last name.
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => '`Taylor`']);
        self::assertEquals('John `Taylor`', $user->tryMention());
        self::assertEquals('John \`Taylor\`', $user->tryMention(true));
    }

    public function testGetProperties()
    {
        // Username.
        $user = new User(['id' => 1, 'username' => 'name_phpunit']);
        self::assertEquals('name_phpunit', $user->username);

        // First name.
        $user = new User(['id' => 1, 'first_name' => 'name_phpunit']);
        self::assertEquals('name_phpunit', $user->firstName);

        // Last name.
        $user = new User(['id' => 1, 'last_name' => 'name_phpunit']);
        self::assertEquals('name_phpunit', $user->lastName);
    }
}
