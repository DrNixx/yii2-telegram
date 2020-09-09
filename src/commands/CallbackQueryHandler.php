<?php
namespace onix\telegram\commands;

use onix\telegram\entities\CallbackQuery;
use onix\telegram\entities\ServerResponse;

interface CallbackQueryHandler
{
    /**
     * @param CallbackQuery $query
     * @return ServerResponse|bool
     */
    public function callbackHandler(CallbackQuery $query);
}