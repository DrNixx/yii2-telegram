<?php
namespace tests\unit\fixtures;

use onix\telegram\models\Message;
use yii\test\ActiveFixture;

class MessageFixture extends ActiveFixture
{
    public $modelClass = Message::class;

    public $depends = [
        UserFixture::class,
        ChatFixture::class,
    ];
}