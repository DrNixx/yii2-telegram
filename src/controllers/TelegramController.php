<?php
namespace onix\telegram\controllers;

use onix\telegram\exceptions\TelegramException;
use onix\telegram\Telegram;
use Yii;
use yii\base\Action;
use yii\base\Exception as BaseException;
use yii\filters\AccessControl;
use yii\validators\IpValidator;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class TelegramController extends Controller
{
    public $allowedIPs = ['149.154.160.0/20', '91.108.4.0/22'];

    public function behaviors()
    {
        $allowedIPs = $this->allowedIPs;

        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['handle'],
                'rules' => [
                    [
                        'actions' => ['handle'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) use ($allowedIPs) {
                            $validator = new IpValidator();
                            $validator->setRanges($this->allowedIPs);
                            return $validator->validate(Yii::$app->getRequest()->getUserIP());
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException();
                }
            ],
        ];
    }

    /**
     * @inheritDoc
     *
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Handle webhook requests
     * @throws TelegramException
     * @throws BaseException
     */
    public function actionHandle()
    {
        /** @var Telegram $tg */
        /** @noinspection PhpUndefinedFieldInspection */
        $tg = Yii::$app->telegram;
        $tg->enableLimiter();
        $tg->handle(Yii::$app->request->rawBody);
    }
}
