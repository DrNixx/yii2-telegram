<?php
namespace onix\telegram\exceptions;

use Yii;

/**
 * Thrown when bot token is invalid
 */
class InvalidBotTokenException extends TelegramException
{
    /**
     * InvalidBotTokenException constructor
     */
    public function __construct()
    {
        parent::__construct(Yii::t('telegram', 'Invalid bot token!'));
    }
}
