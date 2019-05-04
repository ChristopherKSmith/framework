@extends('appshell::layouts.default')

@section('title')
    {{ __('Shipping Methods') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create products')
                    <a href="{{ route('vanilo.shipping_method.create') }}" class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Shipping Method') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('Zone') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Rate') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('State') }}</th>
                        <th style="width: 10%">&nbsp;</th>
                    </tr>
                    </thead>
    
                    <tbody>
                    @foreach($shipping_methods as $method)
                        <tr>
                            <td>
                                <span class="font-lg mb-3 font-weight-bold">
                                        {{ $method->country->name }}
                                </span>
                                <div class="text-muted" title="{{ __('Example') }}">{{ $method->zone }}</div>
                            </td>
                            <td>
                                <span class="font-lg mb-3">
                                        {{ $method->name }}
                                </span>
                            </td>
                            <td>
                                <span class="font-lg mb-3">
                                        {{$method->type->label()}}
                                </span>
                                
                            </td>
                            <td>
                                <span class="mb-3">
                                        {{ format_price($method->rate) }}
                                </span>
                            </td>
                            <td>
                                <span class="mb-3">
                                        {{ $method->description }}
                                </span>
                            </td>
                            <td>
                                <div class="mt-2">
                                    <span class="badge badge-pill badge-{{$method->is_active ? 'success' : 'secondary'}}">{{$method->is_active ? 'Active' : 'Inactive'}}</span>
                                </div>
                            </td>
                            <td>
                                    <a href="{{ route('vanilo.shipping_method.edit', $method) }}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
    
                </table>
    
                @if($shipping_methods->hasPages())
                    <hr>
                    <nav>
                        {{ $shipping_methods->links() }}
                    </nav>
                @endif
    
            </div>
    </div>

@stop
