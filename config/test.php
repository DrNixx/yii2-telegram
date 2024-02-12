<?php
$params = require __DIR__ . '/params.php';

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@onix/telegram/tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@onix/telegram', dirname(__DIR__) . '/src');

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'yii2-telegram-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=yii2_telegram_tests',
            'username' => 'test',
            'password' => 'test',
            'charset' => 'utf8',
        ],

        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://127.0.0.1:27017/telegram_test',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'curl' => [
            'class' => 'onix\http\Curl',
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],

        'telegram' => [
            'class' => 'onix\telegram\Telegram',
            'api_key' => '123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11',
            'bot_username' => 'testbot'
        ],
    ],
    'params' => $params,
];
