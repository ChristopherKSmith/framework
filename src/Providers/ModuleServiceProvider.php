<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-09
 *
 */

namespace Vanilo\Framework\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\AppShell\Breadcrumbs\HasBreadcrumbs;
use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Vanilo\Category\Contracts\Taxon as TaxonContract;
use Vanilo\Framework\Http\Requests\CreateMedia;
use Vanilo\Framework\Http\Requests\CreateProperty;
use Vanilo\Framework\Http\Requests\CreatePropertyValue;
use Vanilo\Framework\Http\Requests\CreatePropertyValueForm;
use Vanilo\Framework\Http\Requests\CreateTaxon;
use Vanilo\Framework\Http\Requests\CreateTaxonForm;
use Vanilo\Framework\Http\Requests\SyncModelPropertyValues;
use Vanilo\Framework\Http\Requests\SyncModelProperties;
use Vanilo\Framework\Http\Requests\SyncModelTaxons;
use Vanilo\Framework\Http\Requests\UpdateProperty;
use Vanilo\Framework\Http\Requests\UpdatePropertyValue;
use Vanilo\Framework\Http\Requests\UpdateTaxon;
use Vanilo\Framework\Models\Address;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Framework\Factories\CheckoutDataFactory;
use Vanilo\Framework\Factories\OrderFactory;
use Vanilo\Framework\Http\Requests\CreateProduct;
use Vanilo\Framework\Http\Requests\CreateTaxonomy;
use Vanilo\Framework\Http\Requests\UpdateOrder;
use Vanilo\Framework\Http\Requests\UpdateProduct;
use Vanilo\Framework\Http\Requests\UpdateTaxonomy;
use Menu;
use Vanilo\Framework\Models\Customer;
use Vanilo\Framework\Models\Product;
use Vanilo\Framework\Models\Taxon;
use Vanilo\Framework\Models\Order;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Product\Contracts\Product as ProductContract;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Framework\Contracts\ShippingMethod as ShippingMethodContract;
use Vanilo\Framework\Models\ShippingMethod;
use Vanilo\Framework\Contracts\ShippingMethodType as ShippingMethodTypeContract;
use Vanilo\Framework\Models\ShippingMethodType;
use Vanilo\Framework\Contracts\ProductSku as ProductSkuContract;
use Vanilo\Framework\Models\ProductSkuProxy;
use Vanilo\Framework\Models\ProductSku;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    use HasBreadcrumbs;

    protected $requests = [
        CreateProduct::class,
        UpdateProduct::class,
        UpdateOrder::class,
        CreateTaxonomy::class,
        UpdateTaxonomy::class,
        CreateTaxon::class,
        UpdateTaxon::class,
        CreateTaxonForm::class,
        SyncModelTaxons::class,
        CreateMedia::class,
        CreateProperty::class,
        UpdateProperty::class,
        CreatePropertyValueForm::class,
        CreatePropertyValue::class,
        UpdatePropertyValue::class,
        SyncModelPropertyValues::class,
        SyncModelProperties::class
    ];

    public function register()
    {
        parent::register();

        $this->app->bind(CheckoutDataFactoryContract::class, CheckoutDataFactory::class);
        $this->app->concord->registerModel(ShippingMethodContract::class, ShippingMethod::class);
        $this->app->concord->registerModel(ProductSkuContract::class, ProductSku::class);
        $this->app->concord->registerEnum(ShippingMethodTypeContract::class, ShippingMethodType::class);
    }

    public function boot()
    {
        parent::boot();

        // Use the frameworks's extended model classes
        $this->concord->registerModel(ProductContract::class, Product::class);
        $this->concord->registerModel(AddressContract::class, Address::class);
        $this->concord->registerModel(CustomerContract::class, Customer::class);
        $this->concord->registerModel(TaxonContract::class, Taxon::class);
        $this->concord->registerModel(OrderContract::class, Order::class);


        // This is ugly, but it does the job for v0.1
        Relation::morphMap([
            app(ProductContract::class)->morphTypeName() => ProductProxy::modelClass(),
            app(ProductSkuContract::class)->morphTypeName() => ProductSkuProxy::modelClass()
        ]);

        // Use the framework's extended order factory
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);

        $this->loadBreadcrumbs();
        $this->addMenuItems();
    }

    protected function addMenuItems()
    {
        if ($menu = Menu::get('appshell')) {
            $shop = $menu->addItem('shop', __('Shop'));
            $shop->addSubItem('products', __('Products'), ['route' => 'vanilo.product.index'])->data('icon', 'layers');
            $shop->addSubItem('product_properties', __('Product Properties'), ['route' => 'vanilo.property.index'])->data('icon', 'format-list-bulleted');
            $shop->addSubItem('categories', __('Categorization'), ['route' => 'vanilo.taxonomy.index'])->data('icon', 'folder');
            $shop->addSubItem('orders', __('Orders'), ['route' => 'vanilo.order.index'])->data('icon', 'mall');

            $shop = $menu->addItem('shipping', __('Shipping'));
            $shop->addSubItem('shipping_methods', __('Shipping Methods'), 'admin/shipping/method')->data('icon', 'layers');
        }
    }
}
