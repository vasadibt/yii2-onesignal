<?php

namespace vasadibt\onesignal\service;

use vasadibt\onesignal\resolver\ResolverFactory;

/**
 * Class Apps
 * @package vasadibt\onesignal\service
 */
class Apps extends Request
{
    /**
     * {@inheritdoc}
     */
    public $methodName = self::APPS;

    /**
     * Get information about application with provided ID.
     *
     * @param string $id ID of your application
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function getOne($id)
    {
        return $this->get($id);
    }

    /**
     * Get information about all your created applications.
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function getAll()
    {
        return $this->get();
    }

    /**
     * Create a new application with provided data.
     *
     * @param array $data Application data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function add(array $data)
    {
        return $this->post(null, ResolverFactory::createAppResolver()->resolve($data));
    }

    /**
     * Update application with provided data.
     *
     * @param string $id ID of your application
     * @param array $data New application data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function update($id, array $data)
    {
        return $this->put($id, ResolverFactory::createAppResolver()->resolve($data));
    }
}
