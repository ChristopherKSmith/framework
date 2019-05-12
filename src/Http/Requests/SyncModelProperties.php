<?php
/**
 * Contains the SyncModelPropertyValues class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-02
 *
 */

namespace Vanilo\Framework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\AppShell\Http\Requests\HasFor;
use Vanilo\Framework\Contracts\Requests\SyncModelProperties as SyncModelPropertiesContract;

class SyncModelProperties extends FormRequest implements SyncModelPropertiesContract
{
    use HasFor;

    protected $allowedFor = ['product'];

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge($this->getForRules(), [
            'properties' => 'sometimes|array'
        ]);
    }

    public function getPropertyIds(): array
    {
        return $this->get('properties') ?: [];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
