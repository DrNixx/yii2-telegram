<?php
namespace onix\telegram\tests\fixtures;

use onix\telegram\models\Message;
use yii\mongodb\ActiveFixture;

class MessageFixture extends ActiveFixture
{
    public $modelClass = Message::class;

    public $depends = [
        UserFixture::class,
        ChatFixture::class,
    ];
}