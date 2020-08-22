<?php
namespace tests\unit\fixtures;

use onix\telegram\models\Conversation;
use yii\test\ActiveFixture;

class ConversationFixture extends ActiveFixture
{
    public $modelClass = Conversation::class;

    public $dataFile = '@tests/_data/conversation.php';

    public $depends = [
        UserFixture::class,
        ChatFixture::class,
    ];
}