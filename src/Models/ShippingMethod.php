<?php
/**
 * Contains the ShippingMethod class.
 *
 * @copyright   Copyright (c) 2017 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Framework\Contract\ShippingMethod as ShippingMethodContract;

class ShippingMethod extends Model implements ShippingMethodContract
{
    use CastsEnums;

    protected $table = 'shipping_methods';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        // 'state' => 'ShippingMethodStateProxy@enumClass',
        // 'zone_type' => 'ShippingZoneTypeProxy@enumClass'
    ];


    /**
     * @inheritdoc
     */
    // public function isActive()
    // {
    //     return $this->state->isActive();
    // }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * Scope for returning the products with active state
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeActives($query)
    // {
    //     return $query->whereIn(
    //         'state',
    //         ShippingMethodStateProxy::getActiveStates()
    //     );
    // }
}
