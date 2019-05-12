<?php
/**
 * Contains the HasProperty trait.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Framework\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Properties\Contracts\Property;
use Vanilo\Properties\Models\PropertyProxy;

trait HasProperties
{
    public function properties(): MorphToMany
    {
        return $this->morphToMany(PropertyProxy::modelClass(), 'model',
            'model_properties', 'model_id', 'property_id'
        );
    }

    public function addProperty(Property $property): void
    {
        $this->properties()->attach($property);
    }

    public function addProperties(iterable $properties)
    {
        foreach ($properties as $property) {
            if (! $property instanceof Property) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Every element passed to addProperties must be a Property object. Given `%s`.',
                        is_object($property) ? get_class($property) : gettype($property)
                    )
                );
            }
        }

        return $this->properties()->saveMany($properties);
    }

    public function removeProperty(Property $property)
    {
        return $this->properties()->detach($property);
    }
}
