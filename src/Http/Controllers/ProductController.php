<?php
/**
 * Contains the Product controller class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-19
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\Product\Contracts\Product;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Product\Models\ProductStateProxy;
use Vanilo\Framework\Contracts\Requests\CreateProduct;
use Vanilo\Framework\Contracts\Requests\UpdateProduct;
use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Framework\Models\ProductSKUProxy;

class ProductController extends BaseController
{
    /**
     * Displays the product index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('vanilo::product.index', [
            'products' => ProductProxy::paginate(100)
        ]);
    }

    /**
     * Displays the create new product view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('vanilo::product.create', [
            'product' => app(Product::class),
            'states'  => ProductStateProxy::choices()
        ]);
    }

    /**
     * @param CreateProduct $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateProduct $request)
    {
        try {
            //Create Product
            $product = ProductProxy::create($request->except('images'));

            //Then Create Product SKU
            $product_sku = ProductSKUProxy::create([
                'sku' => $request['sku'],
                'price' => $request['price'],
                'cost' => $request['cost'],
                'stock' => $request['stock'],
                'product_id' => $product->id,

            ]);

            flash()->success(__(':name has been created', ['name' => $product->name]));

            try {
                if (!empty($request->files->filter('images'))) {
                    $product->addMultipleMediaFromRequest(['images'])->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection();
                    });
                }
            } catch (\Exception $e) { // Here we already have the product created
                flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

                return redirect()->route('vanilo.product.edit', ['product' => $product]);
            }
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.product.index'));
    }

    /**
     * Show the product
     *
     * @param Product $product
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('vanilo::product.show', [
            'product'    => $product,
            'skus' => $product->skus()->paginate(15),
            'taxonomies' => TaxonomyProxy::all(),
            'properties' => PropertyProxy::all()
        ]);
    }

    /**
     * @param Product $product
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('vanilo::product.edit', [
            'product'    => $product,
            'states'     => ProductStateProxy::choices()
        ]);
    }

    /**
     * Saves updates to an existing product
     *
     * @param Product       $product
     * @param UpdateProduct $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Product $product, UpdateProduct $request)
    {
        try {
            $product->update($request->all());

            flash()->success(__(':name has been updated', ['name' => $product->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.product.index'));
    }

    /**
     * Delete a product
     *
     * @param Product $product
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Product $product)
    {
        try {
            $name = $product->name;
            $product->delete();

            flash()->warning(__(':name has been deleted', ['name' => $name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.product.index'));
    }
}
