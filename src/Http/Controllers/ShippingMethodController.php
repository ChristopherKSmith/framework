<?php
/**
 * Contains the ShippingMethodController class.
 *
 * @copyright   Copyright (c) 2017 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2017-12-17
 *
 */


namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Framework\Http\Requests\CreateShippingMethod;
use Vanilo\Framework\Http\Requests\UpdateShippingMethod;
use Vanilo\Framework\Contracts\ShippingMethod;
use Vanilo\Framework\Models\ShippingMethodProxy;
use Vanilo\Framework\Models\ShippingMethodTypeProxy;
use Konekt\Address\Models\CountryProxy;

class ShippingMethodController  extends BaseController
{
    public function index()
    {
        return view('vanilo::shipping-method.index', [
            'shipping_methods' => ShippingMethodProxy::paginate(10),
        ]);
    }

    /**
     * Displays the create new product view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('vanilo::shipping-method.create', [
            'shipping_method' => ShippingMethodProxy::class,
            'countries'  => CountryProxy::all(),
            'types' => ShippingMethodTypeProxy::choices(),
        ]);
    }

    public function edit(ShippingMethod $shipping_method)
    {
        return view('vanilo::shipping-method.edit', [
            'shipping_method' => $shipping_method,
            'countries'  => CountryProxy::all(),
            'types' => ShippingMethodTypeProxy::choices(),
        ]);
    }

    /**
     * @param CreateShippingMethod $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateShippingMethod $request)
    {
        try {
            $shipping_method = ShippingMethodProxy::create($request->all());
            flash()->success(__(':name has been created', ['name' => $shipping_method->name]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.shipping_method.index'));
    }

    public function update(ShippingMethod $shipping_method, UpdateShippingMethod $request)
    {
        try {
            $shipping_method->update($request->all());

            flash()->success(__(':name has been updated', ['name' => $shipping_method->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.shipping_method.index', $shipping_method));
    }
}
