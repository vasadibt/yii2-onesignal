<?php

namespace vasadibt\onesignal\resolver;

use Symfony\Component\OptionsResolver\OptionsResolver;
use yii\base\BaseObject;

/**
 * Class DeviceFocusResolver
 * @package vasadibt\onesignal\resolver
 */
class DeviceFocusResolver extends BaseObject implements ResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(array $data)
    {
        return (new OptionsResolver())
            ->setDefault('state', 'ping')
            ->setAllowedTypes('state', 'string')
            ->setRequired('active_time')
            ->setAllowedTypes('active_time', 'int')
            ->resolve($data);
    }
}
