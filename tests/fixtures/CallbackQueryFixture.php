<?php
namespace onix\telegram\tests\fixtures;

use onix\telegram\models\CallbackQuery;
use yii\mongodb\ActiveFixture;

class CallbackQueryFixture extends ActiveFixture
{
    public $modelClass = CallbackQuery::class;
}