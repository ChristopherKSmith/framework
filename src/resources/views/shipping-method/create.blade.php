@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new shipping method') }}
@stop

@section('content')

    {!! Form::open(['route' => 'vanilo.shipping_method.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Shipping Method Details') }}
                </div>
                <div class="card-block">
                    @include('vanilo::shipping-method._form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create shipping method') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

    {!! Form::close() !!}

@stop