@extends('appshell::layouts.default')

@section('title')
    Create {{  $product->name  }} variant
@stop

@section('content')

    {!! Form::open(['url' => route('vanilo.product_variant.store', $product), 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Variant Details') }}
                </div>
                <div class="card-block">
                    @include('vanilo::product-variant._form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create variant') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">
            @include('vanilo::product-variant._create_images')
        </div>

    {!! Form::close() !!}

@stop
