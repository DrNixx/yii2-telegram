<?php
namespace onix\telegram\tests\fixtures;

use onix\telegram\models\Chat;
use onix\telegram\models\ChosenInlineResult;
use yii\mongodb\ActiveFixture;

class ChosenInlineResultFixture extends ActiveFixture
{
    public $modelClass = ChosenInlineResult::class;
}