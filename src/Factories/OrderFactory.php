<?php
/**
 * Contains the OrderFactory class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-02
 *
 */


namespace Vanilo\Framework\Factories;

use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Order\Factories\OrderFactory as BaseOrderFactory;
use Illuminate\Support\Facades\DB;
use Konekt\Address\Contracts\AddressType;
use Konekt\Address\Models\AddressProxy;
use Konekt\Address\Models\AddressTypeProxy;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Buyable;
use Vanilo\Order\Contracts\Billpayer;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Order\Events\OrderWasCreated;
use Vanilo\Order\Exceptions\CreateOrderException;

class OrderFactory extends BaseOrderFactory
{
    public function createFromCheckout(Checkout $checkout)
    {
        $orderData = [
            'billpayer'       => $checkout->getBillpayer()->toArray(),
            'shippingAddress' => $checkout->getShippingAddress()->toArray()
        ];

        $items = $this->convertCartItemsToDataArray($checkout->getCart());

        return $this->createFromDataArray($orderData, $items);
    }

    public function convertCartItemsToDataArray(CheckoutSubject $cart)
    {
        return $cart->getItems()->map(function ($item) {
            return [
                'product'  => $item->getBuyable(),
                'quantity' => $item->getQuantity()
            ];
        })->all();
    }

    /**
     * Overrides Parent function to include shipping method field
     *
     * @param array $data
     * @param array $items
     *
     * @return Order
     */
    public function createFromDataArray(array $data, array $items): Order
    {
        if (empty($items)) {
            throw new CreateOrderException(__('Can not create an order without items'));
        }

        DB::beginTransaction();

        try {
            $order = app(Order::class);

            $order->fill(array_except($data, ['billpayer', 'shippingAddress']));
            $order->number  = $data['number'] ?? $this->orderNumberGenerator->generateNumber($order);
            $order->user_id = $data['user_id'] ?? auth()->id();
            $order->shipping_method_id = $data['shipping_method_id'] ?? 1;
            $order->save();

            $this->createBillpayer($order, $data);
            $this->createShippingAddress($order, $data);

            $this->createItems($order,
                array_map(function ($item) {
                    // Default quantity is 1 if unspecified
                    $item['quantity'] = $item['quantity'] ?? 1;
                    return $item;
                }, $items)
            );

            $order->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        event(new OrderWasCreated($order));

        return $order;
    }
}
