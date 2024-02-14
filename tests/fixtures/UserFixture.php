<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\fixtures;

use onix\telegram\models\User;
use yii\mongodb\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;

    public $dataFile = '@tests/_data/user.php';
}
