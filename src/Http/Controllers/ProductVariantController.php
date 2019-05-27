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
use Vanilo\Framework\Contracts\ProductVariant;
use Vanilo\Framework\Models\ProductVariantProxy;
use Vanilo\Framework\Http\Requests\CreateProductVariant;
use Vanilo\Framework\Http\Requests\UpdateProductVariant;
use Vanilo\Properties\Models\PropertyValueProxy;

class ProductVariantController extends BaseController
{
   
    /**
     * Displays the create new product view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Product $product)
    {
        return view('vanilo::product-variant.create', [
            'product' => $product
        ]);
    }

    /**
     * @param CreateProductVariant $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateProductVariant $request, Product $product)
    {
        try {
            //Create Variant
            $product_variant = ProductVariantProxy::create(array_merge($request->except(['images','propertyValueIds']), ['product_id' => $product->id]));
            flash()->success(__(':name has been created', ['name' => $product->name]));

            //Create property values in pivot table
            try{
                
                $product_variant->propertyValues()->sync($request->propertyValueIds);

            }catch(\Exception $e){// Here we already have the product variant created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->route('vanilo.product_variant.edit', ['product'=> $product, 'productVariant' => $product_variant]);

            }

            //Create Images
            try {
                if (!empty($request->files->filter('images'))) {
                    $product_variant->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection();
                    });
                }
            } catch (\Exception $e) { // Here we already have the product variant created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->route('vanilo.product_variant.edit', ['product'=> $product, 'productVariant' => $product_variant]);
            }
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }

        return redirect()->route('vanilo.product.show', ['product' => $product]);
    }

    public function edit(Product $product, ProductVariant $product_variant)
    {
        return view('vanilo::product-variant.edit', [
            'product'      => $product,
            'productVariant'    => $product_variant
        ]);
    }

    /**
     * @param UpdateProductVariant $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateProductVariant $request, Product $product, ProductVariant $product_variant)
    {
        try {
            //Update the variant
            $product_variant->update(array_merge($request->except(['images','propertyValueIds']), ['product_id' => $product->id]));
            flash()->success(__(':name variant has been updated', ['name' => $product->name]));

            //Update the property values in pivot table
            try{
                $product_variant->propertyValues()->sync($request->propertyValueIds);
            }catch(\Exception $e){// Here we already have the product variant created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
                return redirect()->route('vanilo.product_variant.edit', ['product'=> $product, 'productVariant' => $product_variant]);
            }

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->route('vanilo.product_variant.edit', ['product'=> $product, 'productVariant' => $product_variant]);
        }

        return redirect()->route('vanilo.product.show', ['product' => $product]);
    }

    /**
     * Delete a Product Variant
     *
     * @param Product $product
     * @param ProductVariant $product_variant
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Product $product, ProductVariant $product_variant)
    {
        try {
            $sku = $product_variant->sku;
            $product_variant->delete();

            flash()->warning(__(':sku has been deleted', ['sku' => $sku]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect()->route('vanilo.product.show', ['product' => $product]);
    }

}