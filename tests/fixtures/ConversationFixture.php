<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\fixtures;

use onix\telegram\models\Conversation;
use yii\mongodb\ActiveFixture;

class ConversationFixture extends ActiveFixture
{
    public $modelClass = Conversation::class;

    public $dataFile = '@tests/_data/conversation.php';

    public $depends = [
        UserFixture::class,
        ChatFixture::class,
    ];
}