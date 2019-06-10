<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */

namespace Vanilo\Framework\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Vanilo\Category\Traits\HasTaxons;
use Vanilo\Contracts\Buyable;
use Vanilo\Framework\Traits\HasProperties;
use Vanilo\Support\Traits\BuyableImageSpatieV7;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;
use Vanilo\Framework\Models\ProductSKU;
use Illuminate\Database\Eloquent\Relations\Relation;
use Vanilo\Properties\Models\Property as Property;

//Testing
use Illuminate\Support\Facades\Log;

class Product extends BaseProduct implements Buyable, HasMedia
{
    protected const DEFAULT_THUMBNAIL_WIDTH  = 250;
    protected const DEFAULT_THUMBNAIL_HEIGHT = 250;
    protected const DEFAULT_THUMBNAIL_FIT    = Manipulations::FIT_CROP;

    use BuyableModel, BuyableImageSpatieV7, HasMediaTrait, HasTaxons, HasProperties;

    protected $dates = ['created_at', 'updated_at', 'last_sale_at'];
    protected $appends = ['images'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            
            foreach($product->skus as $sku)
            {
                $sku->delete();
            }
        });
    }

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

    public function skus()
    {
        return $this->hasMany('Vanilo\Framework\Models\ProductSKU')->with('propertyValues');
    }

    public function propertyValues(){
        return $this->skus
            ->pluck('propertyValues')
            ->flatten(1)
            ->unique('id')
            ->sortBy('id');
    }

    public function isOnSale(): bool
    {
            return $this->sale_price > 0;
    }

    public function getDiscountPrice() : float
    {
        return $this->price - $this->sale_price;
    }

    public function getDiscountPercent(): int
    {
        if($this->getDiscountPrice() > 0)
        {
            return ($this->getDiscountPrice() / $this->price) * 100;
        }
        return 0;
    }

    public function hasProperty(Property $property) : bool
    {
        foreach($this->properties as $prop){
            if($prop->id === $property->id){return true;}
        }
        return false;
    }

    public function findSKU(String $code)
    {

        foreach($this->skus as $sku){
            if($sku->code === $code){return $sku;}
        }
        return false;

    }
    
    public function unitsSold()
    {
        return $this->skus()->sum('units_sold');
    }

    public function lastSaleAt()
    {
        return $this->skus()->max('last_sale_at');
    }

    public function skuCount()
    {
        return $this->skus()->count();
    }

    public function defaultSKU()
    {
        return $this->skus()->where('stock', '>', 0)->first();
    }

    public function defaultPrice()
    {
        return $this->defaultSKU()['price'];
    }
}
