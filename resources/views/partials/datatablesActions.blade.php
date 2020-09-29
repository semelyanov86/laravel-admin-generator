@can($viewGate)
    <a class="btn btn-xs btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        <i class="fa fa-eye" data-toggle="tooltip" data-placement="bottom" title="{{ trans('global.view') }}"></i>
    </a>
@endcan
@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        <i class="fa fa-pencil" data-toggle="tooltip" data-placement="bottom" title="{{ trans('global.edit') }}"></i>
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="bottom" title="{{ trans('global.delete') }}"></i></button>
    </form>
@endcan
