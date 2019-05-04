<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-layers"></i>
        </span>
        {{ Form::text('name', null, [
                'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Shipping Method name')
            ])
        }}
        @if ($errors->has('name'))
            <div class="invalid-tooltip">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-4">
        <label class="form-control-label">{{ __('Type') }}</label>
        <div class="form-group">
            {{ Form::select(
                        'type',
                        $types,
                        null,
                        ['class' => 'form-control' . ($errors->has('country_id') ? ' is-invalid' : '')]
                    )
            }}
            
            @if ($errors->has('type'))
                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-4">
        <label class="form-control-label">{{ __('Country') }}</label>
        <div class="form-group">
            {{ Form::select(
                        'country_id',
                        $countries->pluck('name', 'id'),
                        setting('appshell.default.country'),
                        ['class' => 'form-control' . ($errors->has('country_id') ? ' is-invalid' : '')]
                    )
            }}

            @if ($errors->has('country_id'))
                <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-4">
        <label class="form-control-label">{{ __('Shipping Rate') }}</label>
        <div class="input-group">
            {{ Form::number('rate', null, [
                    'class' => 'form-control' . ($errors->has('rate') ? ' is-invalid' : ''),
                    'placeholder' => __('Shipping Rate')
                ])
            }}
            <span class="input-group-addon">
                {{ config('vanilo.framework.currency.code') }}
            </span>
        </div>
        @if ($errors->has('rate'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('rate') }}</div>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="form-control-label col-md-2">{{ __('State') }}</label>
    <div class="col-md-10">

        {{-- @foreach($states as $key => $value)
            <label class="radio-inline" for="state_{{ $key }}">
                {{ Form::radio('state', $key, $product->state == $value, ['id' => "state_$key"]) }}
                {{ $value }}
                &nbsp;
            </label>
        @endforeach --}}

        <label class="radio-inline" for="state">
                {{ Form::radio('state', "active", "1", null) }}
                Active
                &nbsp;
        </label>

        <label class="radio-inline" for="state">
                {{ Form::radio('state', "in_active", "0", null) }}
                Inactive
                &nbsp;
        </label>

        @if ($errors->has('state'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('state') }}</div>
        @endif
    </div>
</div>


<div class="form-group">
    <label>{{ __('Description') }}</label>

    {{ Form::textarea('description', null,
            [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                'placeholder' => __('Description of the shipping method')
            ]
    ) }}

    @if ($errors->has('description'))
        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
    @endif
</div>