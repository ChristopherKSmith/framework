<?php
/**
 * Contains the OrderController class.
 *
 * @copyright   Copyright (c) 2017 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2017-12-17
 *
 */


namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
//use Vanilo\Framework\Contracts\Requests\UpdateShippingMethod;
use Vanilo\Framework\Contracts\ShippingMethod;
use Vanilo\Framework\Models\ShippingMethodProxy;

class ShippingMethodController extends BaseController
{
    public function index()
    {
        return view('vanilo::shipping-method.index', [
            'shipping_methods' => ShippingMethodProxy::paginate(100)
        ]);
    }
}
