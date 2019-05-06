<?php
/**
 * Contains the CreateShippingMethod class.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2019-05-04
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vanilo\Framework\Models\ShippingMethodType;

class CreateShippingMethod extends FormRequest
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'type' => ['required', Rule::in(ShippingMethodType::values())],
            'country_id' => 'required',
            'rate' => 'required|numeric|min:0',
            'zone' => 'required'
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
