<?php
namespace onix\telegram\console;

use onix\telegram\Telegram;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * Manage Telegram bot
 */
class TelegramController extends Controller
{
    /**
     * Retrieve information about the current status of a webhook.
     */
    public function actionWebhookinfo()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        $webhookinfo = $tg->request->getWebhookInfo();

        $this->stdout(Json::encode(($webhookinfo->result ?: $webhookinfo->printError(true)), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
