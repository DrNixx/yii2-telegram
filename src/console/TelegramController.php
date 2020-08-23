<?php
namespace onix\telegram\console;

use onix\telegram\entities\ServerResponse;
use onix\telegram\exceptions\InvalidWebhookException;
use onix\telegram\exceptions\TelegramException;
use onix\telegram\Telegram;
use Yii;
use yii\base\Exception as BaseException;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * Manage Telegram bot
 */
class TelegramController extends Controller
{
    /**
     * @param ServerResponse $response
     */
    private function printResponse($response)
    {
        $this->stdout(Json::encode(($response->result ?: $response->printError(true)), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /**
     * Retrieve information about the current status of a webhook.
     */
    public function actionWebhookInfo()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        $webhookinfo = $tg->request->getWebhookInfo();

        $this->printResponse($webhookinfo);
    }

    /**
     * Set Webhook for bot.
     *
     * @throws TelegramException
     */
    public function actionSetWebhook()
    {
        $params = isset(Yii::$app->params['telegram']) ? Yii::$app->params['telegram'] : null;

        if (isset(Yii::$app->params['telegram'])) {
            $params = Yii::$app->params['telegram'];

            if (isset($params['webhook'])) {
                $webhook = $params['webhook'];

                if (empty($webhook['url'] ?? null)) {
                    throw new InvalidWebhookException('Invalid webhook');
                }

                $webhook_params = array_filter([
                    'certificate'     => $webhook['certificate'] ?? null,
                    'max_connections' => $webhook['max_connections'] ?? null,
                    'allowed_updates' => $webhook['allowed_updates'] ?? null,
                ], function ($v, $k) {
                    if ($k === 'allowed_updates') {
                        // Special case for allowed_updates, which can be an empty array.
                        return is_array($v);
                    }
                    return !empty($v);
                }, ARRAY_FILTER_USE_BOTH);


                /** @var Telegram $tg */
                /** @noinspection PhpUndefinedFieldInspection */
                $tg = \Yii::$app->telegram;

                $response = $tg->setWebhook($webhook['url'], $webhook_params);

                $this->printResponse($response);

                return;
            }
        }

        $this->stderr('Application params for telegram bot webhook not set or incorrect');
    }

    /**
     * Delete any assigned webhook.
     *
     * @throws TelegramException
     */
    public function actionDeleteWebhook()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        $response = $tg->deleteWebhook();

        $this->printResponse($response);
    }

    /**
     * Receive incoming updates from telegram bot.
     *
     * @throws TelegramException
     * @throws BaseException
     */
    public function actionGetUpdates()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = \Yii::$app->telegram;

        $response = $tg->handleGetUpdates();

        $this->printResponse($response);

        $this->stdout("OK\n");
    }
}
