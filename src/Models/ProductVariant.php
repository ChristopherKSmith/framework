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
use Vanilo\Properties\Traits\HasPropertyValues;

class ProductVariant extends Model implements ProductVariantContract, HasMedia
{
    protected const DEFAULT_THUMBNAIL_WIDTH  = 250;
    protected const DEFAULT_THUMBNAIL_HEIGHT = 250;
    protected const DEFAULT_THUMBNAIL_FIT    = Manipulations::FIT_CROP;

    
    use BuyableImageSpatieV7, HasMediaTrait, HasPropertyValues;

    
    protected $table = 'product_variants';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $appends = ['images'];

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

    public function getImagesAttribute()
    {
        //Check if media has collection
        if ($this->media->isEmpty()) {
            return [];
        } else {
            $images = [];
            foreach(config('vanilo.framework.image.variants', []) as $name => $settings){
                $image_variant = [];
                foreach($this->getMedia() as $media)
                {
                    array_push($image_variant,$media->first() ? $media->getUrl($name) : '/images/product-'.$name.'.jpg');
                }
                $images[$name] = $image_variant;
            }
            
            return $images;
        }  
    }


}
