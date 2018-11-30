<?php
/**
 * Created by PhpStorm.
 * User: Tamás
 * Date: 2018. 11. 29.
 * Time: 13:28
 */

namespace vasadibt\onesignal\service;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use vasadibt\onesignal\OneSignal;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

/**
 * Class Request
 * @package vasadibt\onesignal\service\base
 *
 * @property OneSignal $api
 */
class Request extends BaseObject
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    const APPS = 'apps';
    const PLAYERS = 'players';
    const NOTIFICATIONS = 'notifications';

    /**
     * @var OneSignal
     */
    public $api;
    /**
     * @var string
     */
    public $basicAuthKey;
    /**
     * @var string api endpoint name
     */
    public $methodName;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->api)) {
            throw new InvalidConfigException("The 'api' option is required.");
        }

        if (empty($this->basicAuthKey)) {
            throw new InvalidConfigException("The 'basicAuthKey' option is required.");
        }
        if (empty($this->methodName)) {
            throw new InvalidConfigException("The 'methodName' option is required.");
        }
    }

    /**
     * Make a request to the OneSignal API
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function request($method, $uri = null, $data = [])
    {
        $uri = rtrim($this->api->apiUrl . '/' . $this->methodName . '/' . $uri, '/');
        $this->api->log('Create api request: ' . $uri, __METHOD__);

        $this->api->log('Request data: ' . PHP_EOL . Json::encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), __METHOD__);

        if (!$this->api->enabled) {
            $this->api->log('Api is disabled', __METHOD__);
            return [];
        }

        $queryKey = in_array($method, [static::GET, static::DELETE]) ? 'query' : 'json';
        $options = [
            'headers' => ['Authorization' => 'Basic ' . $this->basicAuthKey],
            $queryKey => array_merge(['app_id' => $this->api->appId], $data),
        ];

        // make signature
        try {
            $response = $this->api->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            $this->api->log('Api exception: ' . $e->getMessage(), __METHOD__);
            if (method_exists($e, 'getResponse')) {
                $this->api->log($this->responseFormat($e->getResponse()), __METHOD__);
            }
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $jsonData = Json::decode($response->getBody());
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        $this->api->log('Success request', __METHOD__);
        $this->api->log(Json::encode($jsonData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), __METHOD__);

        return $jsonData;
    }

    /**
     * @param string|ResponseInterface $response
     * @return string
     */
    public function responseFormat($response)
    {
        if ($response instanceof ResponseInterface) {
            $body = $response->getBody();
            $response = static::isJson($body)
                ? Json::encode(Json::decode($response), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                : $body;
        }
        return $response;
    }

    /**
     * GET
     * @param $uri
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function get($uri = null, $data = [])
    {
        return $this->request(static::GET, $uri, $data);
    }

    /**
     * POST
     * @param $uri
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function post($uri = null, $data = [])
    {
        return $this->request(static::POST, $uri, $data);
    }

    /**
     * PUT
     * @param $uri
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function put($uri = null, $data = [])
    {
        return $this->request(static::PUT, $uri, $data);
    }


    /**
     * DELETE
     * @param $uri
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function delete($uri = null, $data = [])
    {
        return $this->request(static::DELETE, $uri, $data);
    }

    /**
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}