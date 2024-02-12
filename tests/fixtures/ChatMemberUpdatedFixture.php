<?php
namespace onix\telegram\tests\fixtures;

use onix\telegram\models\ChatMemberUpdated;
use yii\mongodb\ActiveFixture;

class ChatMemberUpdatedFixture extends ActiveFixture
{
    public $modelClass = ChatMemberUpdated::class;
}