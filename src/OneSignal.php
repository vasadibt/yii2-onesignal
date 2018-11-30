<?php

namespace vasadibt\onesignal;

use GuzzleHttp\Client;
use vasadibt\onesignal\resolver\ResolverFactory;
use vasadibt\onesignal\service\Apps;
use vasadibt\onesignal\service\Devices;
use vasadibt\onesignal\service\Notifications;
use vasadibt\onesignal\service\Request;
use Yii;
use yii\base\Component;
use yii\log\FileTarget;

/**
 * @property-read Apps $apps          Applications API service
 * @property-read Devices $devices       Devices API service
 * @property-read Notifications $notifications Notifications API service
 */
class OneSignal extends Component
{
    public $apiUrl = 'https://onesignal.com/api/v1';
    public $appId;
    public $appAuthKey;
    public $userAuthKey;
    public $enabled = true;
    /**
     * @var bool
     */
    public $log = true;
    /**
     * @var bool
     */
    public $logTarget = [
        'class' => '\yii\log\FileTarget',
        'logFile' => '@runtime/logs/onesignal.log',
        'categories' => ['vasadibt\onesignal\*'],
        'logVars' => [],
    ];

    /**
     * @var Client|array
     */
    public $client;

    /**
     * @var array
     */
    private $services = [
        'apps' => 'vasadibt\onesignal\service\Apps',
        'devices' => 'vasadibt\onesignal\service\Devices',
        'notifications' => 'vasadibt\onesignal\service\Notifications',
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->client = new Client([
            'verify' => false,
            'debug' => false
        ]);

        if (!empty($this->logTarget)) {
            Yii::$app->getLog()->targets['onesignal'] = Yii::createObject($this->logTarget);
        }
    }

    /**
     * Create required services on the fly.
     *
     * @param string $name
     *
     * @return \vasadibt\onesignal\service\Request
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->services)) {
            if (!is_object($this->services[$name])) {
                $this->services[$name] = Yii::createObject([
                    'class' => $this->services[$name],
                    'api' => $this,
                    'basicAuthKey' => ($name == 'apps' ? $this->userAuthKey : $this->appAuthKey),
                ]);
            }
            return $this->services[$name];
        }
        return parent::__get($name);
    }

    /**
     * @param $message
     * @param $category
     */
    public function log($message, $category = 'vasadibt\onesignal')
    {
        if($this->log){
            Yii::info($message, $category);
        }
    }
}
