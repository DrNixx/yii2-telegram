<?php
namespace tests\unit\fixtures;

use onix\telegram\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;

    public $dataFile = '@tests/_data/user.php';
}
