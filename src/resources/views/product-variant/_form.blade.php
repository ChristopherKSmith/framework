
<hr>

<div class="form-row">
    <label class="form-control-label col-md-2">{{ __('SKU') }}</label>
    <div class="form-group col-12 col-md-6 col-xl-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="zmdi zmdi-code-setting"></i>
            </span>
            {{ Form::text('sku', null, [
                    'class' => 'form-control' . ($errors->has('sku') ? ' is-invalid' : ''),
                    'placeholder' => __('SKU (product code)')
                ])
            }}
        </div>
        @if ($errors->has('sku'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('sku') }}</div>
        @endif
    </div>
</div>

{{-- <div class="form-row">
    <label class="form-control-label col-md-2">{{ __('Stock') }}</label>
    <div class="form-group col-12 col-md-6 col-xl-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="zmdi zmdi-code-setting"></i>
            </span>
            {{ Form::number('stock', null, [
                    'class' => 'form-control' . ($errors->has('stock') ? ' is-invalid' : ''),
                    'placeholder' => __('Product Stock Count')
                ])
            }}
        </div>
        @if ($errors->has('stock'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('stock') }}</div>
        @endif
    </div>
</div> --}}

<div class="form-row">
    <label class="form-control-label col-md-2">{{ __('Cost') }}</label>
    <div class="form-group col-12 col-md-6 col-xl-4">
        <div class="input-group">
            {{ Form::text('cost', null, [
                    'class' => 'form-control' . ($errors->has('cost') ? ' is-invalid' : ''),
                    'placeholder' => __('Product Cost')
                ])
            }}
            <span class="input-group-addon">
                {{ config('vanilo.framework.currency.code') }}
            </span>
        </div>
        @if ($errors->has('price'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('price') }}</div>
        @endif
    </div>
</div>

<div class="form-row">
    <label class="form-control-label col-md-2">{{ __('Price') }}</label>
    <div class="form-group col-12 col-md-6 col-xl-4">
        <div class="input-group">
            {{ Form::text('price', null, [
                    'class' => 'form-control' . ($errors->has('price') ? ' is-invalid' : ''),
                    'placeholder' => __('Price')
                ])
            }}
            <span class="input-group-addon">
                {{ config('vanilo.framework.currency.code') }}
            </span>
        </div>
        @if ($errors->has('price'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('price') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-group">
    <h5>{{ __('Variant Property Values') }}</h5>

    @foreach($product->properties as $property)
        <label class="form-control-label col-md-2">{{ $property->name }}</label>
        <div class="form-group col-12 col-md-6 col-xl-4">
            <div class="input-group">
                <select name="propertyValueIds[]" style="width:100%;">
                    @foreach($property->values() as $property_value)
                        <option value="{{$property_value->id}}"
                            @if(isset($productVariant) && $productVariant->propertyValues->firstWhere('property_id', $property->id)->title === $property_value->title) 
                                selected 
                            @endif >
                            {{$property_value->title}}
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->has('property_value'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('property_value') }}</div>
            @endif
        </div>
    @endforeach
</div>

    
    