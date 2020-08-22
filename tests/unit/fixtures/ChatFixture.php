<?php
namespace tests\unit\fixtures;

use onix\telegram\models\Chat;
use yii\test\ActiveFixture;

class ChatFixture extends ActiveFixture
{
    public $modelClass = Chat::class;

    public $dataFile = '@tests/_data/chat.php';
}