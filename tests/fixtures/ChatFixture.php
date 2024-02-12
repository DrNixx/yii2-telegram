<?php
namespace onix\telegram\tests\fixtures;

use onix\telegram\models\Chat;
use yii\mongodb\ActiveFixture;

class ChatFixture extends ActiveFixture
{
    public $modelClass = Chat::class;

    public $dataFile = '@tests/_data/chat.php';
}