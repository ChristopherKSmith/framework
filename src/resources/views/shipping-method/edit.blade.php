@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $shipping_method->name }}
@stop

@section('content')
{!! Form::model($shipping_method, [
        'route'  => ['vanilo.shipping_method.update', $shipping_method],
        'method' => 'PUT'
    ])
!!}

    <div class="card card-accent-secondary">
        <div class="card-header">
            {{ __('Shipping Method Details') }}
        </div>

        <div class="card-block">
            @include('vanilo::shipping-method._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Save') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
