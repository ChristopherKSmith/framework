<?php
/**
 * Contains the Product controller class.
 *
 * @copyright   Copyright (c) 2019 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2019-05-10
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Product\Contracts\Product;
use Vanilo\Framework\Contracts\ProductSku as ProductSku;
use Vanilo\Framework\Models\ProductSkuProxy;
use Vanilo\Framework\Http\Requests\CreateProductSku;
use Vanilo\Framework\Http\Requests\UpdateProductSku;
use Vanilo\Properties\Models\PropertyValueProxy;

class ProductSkuController extends BaseController
{
   
    /**
     * Displays the create new product view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Product $product)
    {
        return view('vanilo::product-sku.create', [
            'product' => $product
        ]);
    }

    /**
     * @param CreateProductSKU $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateProductSku $request, Product $product)
    {
        try {
            //Create SKU
            $product_sku = ProductSkuProxy::create(array_merge($request->except(['images','propertyValueIds']), ['product_id' => $product->id]));
            flash()->success(__(':name has been created', ['name' => $product->name]));

            //Create property values in pivot table
            try{
                
                $product_sku->propertyValues()->sync($request->propertyValueIds);

            }catch(\Exception $e){// Here we already have the product SKU created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->route('vanilo.product_sku.edit', ['product'=> $product, 'productSku' => $product_sku]);

            }

            //Create Images
            try {
                if (!empty($request->files->filter('images'))) {
                    $product_sku->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection();
                    });
                }
            } catch (\Exception $e) { // Here we already have the product SKU created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->route('vanilo.product_sku.edit', ['product'=> $product, 'productSku' => $product_sku]);
            }
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }

        return redirect()->route('vanilo.product.show', ['product' => $product]);
    }

    public function edit(Product $product, ProductSku $product_sku)
    {
        return view('vanilo::product-sku.edit', [
            'product'      => $product,
            'productSku'    => $product_sku
        ]);
    }

    /**
     * @param UpdateProductSku $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateProductSku $request, Product $product, ProductSku $product_sku)
    {
        try {
            //Update the SKU
            $product_sku->update(array_merge($request->except(['images','propertyValueIds']), ['product_id' => $product->id]));
            flash()->success(__(':name sku has been updated', ['name' => $product->name]));

            //Update the property values in pivot table
            try{
                $product_sku->propertyValues()->sync($request->propertyValueIds);
            }catch(\Exception $e){// Here we already have the product SKU created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->route('vanilo.product_sku.edit', ['product'=> $product, 'productSku' => $product_sku]);
            }

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->route('vanilo.product_sku.edit', ['product'=> $product, 'productSku' => $product_sku]);
        }

        return redirect()->route('vanilo.product.show', ['product' => $product]);
    }

    /**
     * Delete a Product SKU
     *
     * @param Product $product
     * @param ProductSku $product_sku
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Product $product, ProductSku $product_sku)
    {
        try {
            $code = $product_sku->code;
            $product_sku->delete();

            flash()->warning(__(':sku has been deleted', ['sku' => $code]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect()->route('vanilo.product.show', ['product' => $product]);
    }

}