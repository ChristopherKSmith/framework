<?php
/**
 * Contains the Shipping Method interface.
 *
 * @copyright   Copyright (c) 2017 Kyle Smith
 * @author      Kyle Smith
 * @license     MIT
 * @since       2017-10-07
 *
 */


namespace Vanilo\Framework\Contracts;

interface ShippingMethod
{
    /**
     * Returns the name of the shipping method.
     *
     * @return string
     */
    public function name();
}