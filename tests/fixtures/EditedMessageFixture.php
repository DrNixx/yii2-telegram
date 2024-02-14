<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\fixtures;

use onix\telegram\models\EditedMessage;
use yii\mongodb\ActiveFixture;

class EditedMessageFixture extends ActiveFixture
{
    public $modelClass = EditedMessage::class;

    public $depends = [
        UserFixture::class,
        ChatFixture::class,
    ];
}