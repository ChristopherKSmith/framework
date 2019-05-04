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

namespace Vanilo\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Framework\Contracts\ShippingMethod as ShippingMethodContract;
use Konekt\Address\Models\CountryProxy;

class ShippingMethod extends Model implements ShippingMethodContract
{
    use CastsEnums;

    protected $table = 'shipping_methods';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $enums = [
        'type' => 'ShippingMethodTypeProxy@enumClass'
    ];


    /**
     * Relationship to the country the address belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(CountryProxy::modelClass(), 'country_id');
    }

    public function name(): string
    {
        return $this->name;
    }

}
