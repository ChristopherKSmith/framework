@extends('appshell::layouts.default')

@section('title')
    Create {{  $product->name  }} SKU
@stop

@section('content')

    {!! Form::open(['url' => route('vanilo.product_sku.store', $product), 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Product SKU') }}
                </div>
                <div class="card-block">
                    @include('vanilo::product-sku._form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create SKU') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">
            @include('vanilo::product-sku._create_images')
        </div>

    {!! Form::close() !!}

@stop
