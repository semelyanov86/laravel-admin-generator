@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <strong>{{ trans('global.create') }} {{ trans('cruds.country.title_singular') }}</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.countries.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.country.fields.name') }}</label> @if ( trans('cruds.country.fields.name_helper') && trans('cruds.country.fields.name_helper') != ' '  )
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{{ trans('cruds.country.fields.name_helper') }}"></i>
                @endif
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="short_code">{{ trans('cruds.country.fields.short_code') }}</label> @if ( trans('cruds.country.fields.short_code_helper') && trans('cruds.country.fields.short_code_helper') != ' '  )
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{{ trans('cruds.country.fields.name_helper') }}"></i>
                @endif
                <input class="form-control {{ $errors->has('short_code') ? 'is-invalid' : '' }}" type="text" name="short_code" id="short_code" value="{{ old('short_code', '') }}" required>
                @if($errors->has('short_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('short_code') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
