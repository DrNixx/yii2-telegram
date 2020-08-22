<?php
namespace onix\telegram\console;

use onix\telegram\Telegram;
use yii\console\Controller;

class TelegramController extends Controller
{
    public function webhookinfoAction()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        $webhookinfo = $tg->request->getWebhookInfo();

        $this->stdout(print_r(($webhookinfo->result ?: $webhookinfo->printError(true)), true));
    }
}
