@extends('appshell::layouts.default')

@section('title')
    {{ __('Upload New Products') }}
@stop

@section('content')

<div class="card card-accent-success">
    <div class="card-header">{{ __('Product CSV File') }}</div>
    <div class="card-block">
            <div class="card">
                {!! Form::open(['route' => 'vanilo.product_upload.upload', 'enctype'=>'multipart/form-data']) !!}
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="p-3 bg-secondary">
                            <div class="align-content-center text-center">
                                <i class="zmdi font-2xl zmdi-collection-image"></i>
                            </div>
                        </div>
                        <div class="p-2">
                            

                                {{ Form::file('csv_file', ['class' => 'form-control-file']) }}

                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success">{{ __('Upload New Products') }}</button>
                        <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>
                {!! Form::close() !!}
            </div>
        @if ($errors->has('csv_file'))
            <div class="sinvalid-feedback">{{ $errors->first('csv_file') }}</div>
        @endif
    </div>
</div>

@if ($errors->import->any())
  <div class="alert alert-danger">
    The import has following errors in <strong>line {{ session('error_line') }}</strong>:
      <ul>
        @foreach ($errors->import->all() as $message)
          <li>{{ $message }}</li>
        @endforeach
      </ul>
    </div>
@endif


@stop
