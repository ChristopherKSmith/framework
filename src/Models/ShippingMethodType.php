<?php
/**
 * Contains the ShippingMethodType enum class.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2017-11-27
 *
 */
namespace Vanilo\Framework\Models;

use Konekt\Enum\Enum;
use Vanilo\Framework\Contracts\ShippingMethodType as ShippingMethodTypeContract;

class ShippingMethodType extends Enum implements ShippingMethodTypeContract
{
    const __default = self::FREESHIPPING;

    /**
     * Flat rate shipping has a fixed/rate or price.
     */
    const FLATRATE = 'flatrate';

    /**
     * Free Shipping rate.
     */
    const FREESHIPPING = 'free_shipping';

    /**
     * Item will not be shipping and pick up in store.
     */
    const STOREPICKUP = 'store_pickup';

    // $labels static property needs to be defined
    public static $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::FLATRATE     => __('Flat Rate'),
            self::FREESHIPPING   => __('Free Shipping'),
            self::STOREPICKUP   => __('Store Pickup')
        ];
    }
}
