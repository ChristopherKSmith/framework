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

//use Vanilo\Framework\Contracts\Requests\UpdateShippingMethod;
use Vanilo\Framework\Contracts\ShippingMethod;
use Vanilo\Framework\Models\ShippingMethodProxy;
use Vanilo\Framework\Models\ShippingMethodTypeProxy;
use Konekt\Address\Models\CountryProxy;

class ShippingMethodController extends Controller
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

    public function edit()
    {
        return view('vanilo::shipping-method.edit', []);
    }

    public function store()
    {
        return view('vanilo::shipping-method.store', []);
    }

    public function update()
    {
        return view('vanilo::shipping-method.update', []);
    }
}
