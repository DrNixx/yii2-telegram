<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace onix\telegram\tests\fixtures;

use onix\telegram\models\ChatMemberUpdated;
use onix\telegram\models\InlineQuery;
use yii\mongodb\ActiveFixture;

class InlineQueryFixture extends ActiveFixture
{
    public $modelClass = InlineQuery::class;
}