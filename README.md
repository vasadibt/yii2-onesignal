Yii2 Onesignal api
==================
This is a restful api to onesignal.

[![Total Downloads](https://poser.pugx.org/vasadibt/yii2-onesignal/downloads)](https://packagist.org/packages/vasadibt/yii2-onesignal)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vasadibt/yii2-onesignal/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vasadibt/yii2-onesignal/?branch=master)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vasadibt/yii2-onesignal "dev-master"
```

or add

```
"vasadibt/yii2-onesignal": "dev-master"
```

to the require section of your `composer.json` file.


Config
-----

Once the extension is installed, you have to configure the api component:

```php
'components' => [
    'onesignal' => [
        'class' => '\vasadibt\onesignal\OneSignal',
        'appId' => 'your-app-id-hash-code',
        'appAuthKey' => 'SetYourAppAuthKey',
        'userAuthKey' => 'SetYourUserAuthKey',
        'enabled' => YII_ENV_PROD ? true : false,
    ],
],
```

Usage
-----

Call api endpoints:

```php

Yii::$app->onesignal->apps->getAll();

Yii::$app->onesignal->devices->getOne('asd-asd-asd-asd-asd-asd');
Yii::$app->onesignal->devices->getAll();


Yii::$app->onesignal->notifications->add([
    'include_player_ids' => ['player-hash-code-12345-123456789'],
    'contents' => ["en" => 'New message'],
]);


Yii::$app->onesignal->notifications->add([
    'included_segments' => ['All'],
    'contents' => ["en" => 'New message'],
]);
```
