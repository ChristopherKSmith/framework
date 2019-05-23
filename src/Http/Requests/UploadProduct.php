<?php
/**
 * Contains the Upload Product request class.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2019-05-22
 *
 */


namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Vanilo\Product\Models\ProductStateProxy;
use Vanilo\Framework\Contracts\Requests\UploadProduct as UploadProductContract;

class UploadProduct extends FormRequest implements UploadProductContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'csv_file'   => 'required|mimes:csv,txt',
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
