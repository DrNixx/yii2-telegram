<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\fixtures;

use onix\telegram\models\TelegramUpdate;
use yii\mongodb\ActiveFixture;

class UpdatesFixture extends ActiveFixture
{
    public $modelClass = TelegramUpdate::class;

    public $depends = [
        MessageFixture::class,
        CallbackQueryFixture::class,
        ChosenInlineResultFixture::class,
        ChatMemberUpdatedFixture::class,
        EditedMessageFixture::class,
        InlineQueryFixture::class,
    ];
}