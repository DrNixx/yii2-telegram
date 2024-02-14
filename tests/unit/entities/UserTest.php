<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\unit\entities;

use Codeception\Test\Unit;
use onix\telegram\entities\User;
use onix\telegram\models\User as UserRepo;
use onix\telegram\Storage;
use onix\telegram\tests\fixtures\UserFixture;
use onix\telegram\tests\UnitTester;

class UserTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    public function testInstance()
    {
        $user = new User(['id' => 1]);
        verify($user)->instanceOf(User::class);
    }

    public function testGetId()
    {
        $user = new User(['id' => 123]);
        verify($user->id)->equals(123);
    }

    public function testTryMention()
    {
        // Username
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'jtaylor']);
        verify($user->tryMention())->equals('@jtaylor');

        // First name.
        $user = new User(['id' => 1, 'first_name' => 'John']);
        verify($user->tryMention())->equals('John');

        // First and Last name.
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor']);
        verify($user->tryMention())->equals('John Taylor');
    }

    public function testEscapeMarkdown()
    {
        // Username.
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'j_taylor']);
        verify($user->tryMention())->equals('@j_taylor');
        verify($user->tryMention(true))->equals('@j\_taylor');

        // First name.
        $user = new User(['id' => 1, 'first_name' => 'John[']);
        verify($user->tryMention())->equals('John[');
        verify($user->tryMention(true))->equals('John\[');

        // First and Last name.
        $user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => '`Taylor`']);
        verify($user->tryMention())->equals('John `Taylor`');
        verify($user->tryMention(true))->equals('John \`Taylor\`');
    }

    public function testGetProperties()
    {
        // Username.
        $user = new User(['id' => 1, 'username' => 'name_phpunit']);
        verify($user->username)->equals('name_phpunit');

        // First name.
        $user = new User(['id' => 1, 'first_name' => 'name_phpunit']);
        verify($user->firstName)->equals('name_phpunit');

        // Last name.
        $user = new User(['id' => 1, 'last_name' => 'name_phpunit']);
        verify($user->lastName)->equals('name_phpunit');
    }

    public function testSaveDb()
    {
        $user = new User(['id' => 3, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'j_taylor']);
        verify($user)->instanceOf(User::class);

        Storage::userUpsert($user);
        $this->tester->seeRecord(UserRepo::class, ['_id' => 3]);
    }
}
