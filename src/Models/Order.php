<?php
/**
 * Contains the Order class.
 *
 * @copyright   Copyright (c) 2019
 * @author      Kyle Smith
 * @license     MIT
 * @since       2019-05-04
 *
 */

namespace Vanilo\Framework\Models;

use Vanilo\Order\Models\Order as BaseOrder;
use Vanilo\Models\ShippingMethodProxy;

class Order extends BaseOrder
{
    /**
     * Relationship to the ShippingMethod the Shipping belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethodProxy::modelClass(), 'shipping_method_id');
    }

    public function subtotal()
    {
        return $this->items->sum('total');
    }

    public function total()
    {
        return $this->items->sum('total') + $this->shippingMethod->rate;
    }
}
