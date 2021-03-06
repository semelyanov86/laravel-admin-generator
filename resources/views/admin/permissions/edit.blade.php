@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <strong>{{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.permissions.update", [$permission->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.permission.fields.title') }}</label> @if ( trans('cruds.permission.fields.title_helper') && trans('cruds.permission.fields.title_helper') != ' '  )
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{{ trans('cruds.permission.fields.title_helper') }}"></i>
                @endif
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $permission->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
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
