@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $product->name }}
@stop

@section('content')
<div class="row">

    <div class="col-12 col-lg-8 col-xl-9">
        {!! Form::model($productVariant, ['url'  => route('vanilo.product_variant.update', [$product, $productVariant]), 'method' => 'PUT']) !!}
        <div class="card card-accent-secondary">
            <div class="card-header">
                {{ __('Product Variant Data') }}
            </div>
            <div class="card-block">
                @include('vanilo::product-variant._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">{{ __('Save') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
        @include('vanilo::product-variant._edit_images')
    </div>

</div>
@stop
