<?php

namespace vasadibt\onesignal\resolver;

use vasadibt\onesignal\OneSignal;

/**
 * Class ResolverFactory
 * @package vasadibt\onesignal\resolver
 */
abstract class ResolverFactory
{
    /**
     * @return AppResolver
     */
    public static function createAppResolver()
    {
        return new AppResolver();
    }

    /**
     * @return DeviceSessionResolver
     */
    public static function createDeviceSessionResolver()
    {
        return new DeviceSessionResolver();
    }

    /**
     * @return DevicePurchaseResolver
     */
    public static function createDevicePurchaseResolver()
    {
        return new DevicePurchaseResolver();
    }

    /**
     * @return DeviceFocusResolver
     */
    public static function createDeviceFocusResolver()
    {
        return new DeviceFocusResolver();
    }

    /**
     * @param OneSignal $api
     * @return DeviceResolver
     */
    public static function createNewDeviceResolver($api)
    {
        return new DeviceResolver(['api' => $api, 'isNewDevice' => true]);
    }

    /**
     * @param OneSignal $api
     * @return DeviceResolver
     */
    public static function createExistingDeviceResolver($api)
    {
        return new DeviceResolver(['api' => $api, 'isNewDevice' => false]);
    }

    /**
     * @param OneSignal $api
     * @return NotificationResolver
     */
    public static function createNotificationResolver($api)
    {
        return new NotificationResolver(['api' => $api]);
    }
}
