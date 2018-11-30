<?php

namespace vasadibt\onesignal\service;

use vasadibt\onesignal\resolver\ResolverFactory;

/**
 * Class Devices
 * @package vasadibt\onesignal\service
 */
class Devices extends Request
{
    const DEVICES_LIMIT = 300;

    const IOS = 0;
    const ANDROID = 1;
    const AMAZON = 2;
    const WINDOWS_PHONE = 3;
    const WINDOWS_PHONE_MPNS = 3;
    const CHROME_APP = 4;
    const CHROME_WEB = 5;
    const WINDOWS_PHONE_WNS = 6;
    const SAFARI = 7;
    const FIREFOX = 8;
    const MACOS = 9;
    const ALEXA = 10;
    const EMAIL = 11;

    /**
     * {@inheritdoc}
     */
    public $methodName = self::PLAYERS;

    /**
     * Get information about device with provided ID.
     *
     * @param string $id Device ID
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
     * Get information about all registered devices for your application.
     *
     * @param int $limit How many devices to return. Max is 300. Default is 300
     * @param int $offset Result offset. Default is 0. Results are sorted by id
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function getAll($limit = self::DEVICES_LIMIT, $offset = 0)
    {
        return $this->get(null, [
            'limit' => max(1, min(self::DEVICES_LIMIT, filter_var($limit, FILTER_VALIDATE_INT))),
            'offset' => max(0, filter_var($offset, FILTER_VALIDATE_INT)),
        ]);
    }

    /**
     * Register a device for your application.
     *
     * @param array $data Device data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function add(array $data)
    {
        return $this->post(null, ResolverFactory::createNewDeviceResolver($this->api)->resolve($data));
    }

    /**
     * Update existing registered device for your application with provided data.
     *
     * @param string $id Device ID
     * @param array $data New device data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function update($id, array $data)
    {
        return $this->put($id, ResolverFactory::createExistingDeviceResolver($this->api)->resolve($data));
    }

    /**
     * Call on new device session in your app.
     *
     * @param string $id Device ID
     * @param array $data Device data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function onSession($id, array $data)
    {
        return $this->post($id . '/on_session', ResolverFactory::createDeviceSessionResolver()->resolve($data));
    }

    /**
     * Track a new purchase.
     *
     * @param string $id Device ID
     * @param array $data Device data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function onPurchase($id, array $data)
    {
        return $this->post($id . '/on_purchase', ResolverFactory::createDevicePurchaseResolver()->resolve($data));
    }

    /**
     * Increment the device's total session length.
     *
     * @param string $id Device ID
     * @param array $data Device data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function onFocus($id, array $data)
    {
        return $this->post($id . '/on_focus', ResolverFactory::createDeviceFocusResolver()->resolve($data));
    }

    /**
     * Export all information about devices in a CSV format for your application.
     *
     * @param array $extraFields Additional fields that you wish to include.
     *                           Currently supports: "location", "country", "rooted"
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function csvExport(array $extraFields = [])
    {
        return $this->post('csv_export', ['extra_fields' => $extraFields]);
    }
}
