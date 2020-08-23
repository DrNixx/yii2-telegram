Telegram Extension for Yii 2
============================
The Telegram integration for the Yii framework

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist onix/yii2-telegram "*"
```

or add

```
"onix/yii2-telegram": "*"
```

to the require section of your `composer.json` file.

Apply migration
```
yii migrate/up --migrationPath=@vendor/onix/yii2-telegram/src/migrations
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \onix\telegram\AutoloadExample::widget(); ?>```