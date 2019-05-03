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


namespace App\Contracts;

interface ShippingMethod
{
    /**
     * Returns whether the method is active (based on its state)
     *
     * @return bool
     */
    //public function isActive();

    /**
     * Returns the name of the shipping method.
     *
     * @return string
     */
    public function name();
}