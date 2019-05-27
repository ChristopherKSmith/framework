<?php
/**
 * Contains the HasPropertyValues trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Framework\Traits;
use Vanilo\Properties\Traits\HasPropertyValues as BaseTrait;

trait HasPropertyValues
{
    use BaseTrait;

    protected static function bootHasPropertyValues()
    {
        self::deleting(function ($model) {
            foreach($model->propertyValues as $value)
            {
                $model->propertyValues()->detach($value);
            }
        });
    }
}