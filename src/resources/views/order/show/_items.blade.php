<div class="card">
    <div class="card-header">
        {{ __('Ordered Items') }}
    </div>

    <div class="card-block">
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="width: 7%">#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Brand') }}</th>
                <th>{{ __('SKU') }}</th>
                <th>{{ __('Qty') }}</th>
                <th>{{ __('Price') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach($order->getItems() as $item)
                @php $sku = Vanilo\Framework\Models\ProductSku::find($item->product_id); @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sku->title() }}</td>
                    <td>{{ $sku->brand() }}</td>
                    <td>{{ $sku->code }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ format_price($item->price) }}</td>
                </tr>
                
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <div style="float:right;">
                                <div>{{ __('Sub total') }}: {{ format_price($order->subtotal()) }} </div>
                                <div>{{ __('Shipping total') }}: {{ format_price($order->shippingMethod->rate) }}</div>
                                <div>{{ __('Order total') }}: {{ format_price($order->total()) }}</div>
                        </div>
                        
                    </th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
