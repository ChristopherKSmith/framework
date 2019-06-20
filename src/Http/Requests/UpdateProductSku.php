<?php
/**
 * Contains the UpdateProductSKU class.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-09
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductSku extends FormRequest
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'code'  => [
                'required',
                Rule::unique('product_skus')->ignore($this->route('product_sku')->id),
                ],
            'images'   => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif'
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
