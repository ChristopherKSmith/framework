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


use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Vanilo\Support\Traits\BuyableImageSpatieV7;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Framework\Contracts\ProductVariant as ProductVariantContract;

class ProductVariant extends Model implements ProductVariantContract, HasMedia
{
    protected const DEFAULT_THUMBNAIL_WIDTH  = 250;
    protected const DEFAULT_THUMBNAIL_HEIGHT = 250;
    protected const DEFAULT_THUMBNAIL_FIT    = Manipulations::FIT_CROP;

    
    use BuyableImageSpatieV7, HasMediaTrait;

    
    protected $table = 'product_variants';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function registerMediaConversions(Media $media = null)
    {
        foreach (config('vanilo.framework.image.variants', []) as $name => $settings) {
            $conversion = $this->addMediaConversion($name)
                 ->fit(
                     $settings['fit'] ?? static::DEFAULT_THUMBNAIL_FIT,
                     $settings['width'] ?? static::DEFAULT_THUMBNAIL_WIDTH,
                     $settings['height'] ?? static::DEFAULT_THUMBNAIL_HEIGHT
                 );
            if (!($settings['queued'] ?? false)) {
                $conversion->nonQueued();
            }
        }
    }

    public function propertyValues()
    {
        return $this->belongsToMany('Vanilo\Properties\Models\PropertyValue', 'product_variant_property_value', 'product_variant_id', 'property_value_id');
    }


}
