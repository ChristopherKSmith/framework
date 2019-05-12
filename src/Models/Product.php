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
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Framework\Traits\HasProperties;
use Vanilo\Support\Traits\BuyableImageSpatieV7;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;
use Vanilo\Framework\Models\ProductVariant;

class Product extends BaseProduct implements Buyable, HasMedia
{
    protected const DEFAULT_THUMBNAIL_WIDTH  = 250;
    protected const DEFAULT_THUMBNAIL_HEIGHT = 250;
    protected const DEFAULT_THUMBNAIL_FIT    = Manipulations::FIT_CROP;

    use BuyableModel, BuyableImageSpatieV7, HasMediaTrait, HasTaxons, HasProperties, HasPropertyValues;

    protected $dates = ['created_at', 'updated_at', 'last_sale_at'];

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

    public function variants()
    {
        return $this->hasMany('Vanilo\Framework\Models\ProductVariant');
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
}
