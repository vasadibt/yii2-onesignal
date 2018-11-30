<?php

namespace vasadibt\onesignal\service;


use vasadibt\onesignal\resolver\ResolverFactory;

/**
 * Class Notifications
 * @package vasadibt\onesignal\service
 */
class Notifications extends Request
{
    const NOTIFICATIONS_LIMIT = 50;

    /**
     * {@inheritdoc}
     */
    public $methodName = self::NOTIFICATIONS;

    /**
     * Get information about notification with provided ID.
     *
     * @param string $id Notification ID
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
     * Get information about all notifications.
     *
     * @param int $limit How many notifications to return (max 50)
     * @param int $offset Results offset (results are sorted by ID)
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function getAll($limit = self::NOTIFICATIONS_LIMIT, $offset = 0)
    {
        return $this->get(null, [
            'limit' => max(1, min(self::NOTIFICATIONS_LIMIT, filter_var($limit, FILTER_VALIDATE_INT))),
            'offset' => max(0, filter_var($offset, FILTER_VALIDATE_INT)),
        ]);
    }

    /**
     * Send new notification with provided data.
     *
     * @param array $data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function add(array $data)
    {
        return $this->post(null, ResolverFactory::createNotificationResolver($this->api)->resolve($data));
    }

    /**
     * Open notification.
     *
     * @param string $id Notification ID
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function open($id)
    {
        return $this->put($id, ['opened' => true]);
    }

    /**
     * Cancel notification.
     *
     * @param string $id Notification ID
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\web\BadRequestHttpException
     */
    public function cancel($id)
    {
        return $this->delete($id);
    }
}
