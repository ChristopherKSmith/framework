@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $product->name }}
@stop

@section('content')
<div class="row">

    <div class="col-12 col-lg-8 col-xl-9">
        {!! Form::model($productSku, ['url'  => route('vanilo.product_sku.update', [$product, $productSku]), 'method' => 'PUT']) !!}
        <div class="card card-accent-secondary">
            <div class="card-header">
                {{ __('Product SKU') }}
            </div>
            <div class="card-block">
                @include('vanilo::product-sku._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">{{ __('Save') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
        @include('vanilo::product-sku._edit_images')
    </div>

</div>
@stop
