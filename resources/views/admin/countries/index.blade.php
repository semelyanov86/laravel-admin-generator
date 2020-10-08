@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            <strong  class="line-title">{{ trans('cruds.country.title_singular') }} {{ trans('global.list') }}</strong>
            @can('country_create')
                <a class="btn btn-sm btn-success  @if ( app()->getLocale() === 'he' ) pull-left @else pull-right @endif" href="{{ route('admin.countries.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.country.title_singular') }}
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table class="display responsive nowrap table table-bordered table-striped table-hover ajaxTable datatable datatable-Country">
                <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.country.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.country.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.country.fields.short_code') }}
                    </th>
                    <th width="10">
                        &nbsp;
                    </th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>



@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('country_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.countries.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                        return entry.id
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: { ids: ids, _method: 'DELETE' }})
                            .done(function () { location.reload() })
                    }
                }
            }
            dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: {
                    buttons: dtButtons,
                    dom: {
                        container: {
                            className: 'dt-buttons d-none d-sm-block'
                        }
                    }
                },
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'td.dtr-control'
                    }
                },
                ajax: "{{ route('admin.countries.index') }}",
                columns: [
                    { data: 'placeholder', name: 'placeholder' },
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'short_code', name: 'short_code' },
                    { data: 'actions', name: '{{ trans('global.actions') }}' },
                    { data: 'collapse', name: 'collapse' }
                ],
                orderCellsTop: true,
                order: [[ 1, 'desc' ]],
                pageLength: 10,
                initComplete: function(settings, json) {
                    $('div.dataTables_length').addClass( 'd-none d-sm-block' );
                }
            };
            let table = $('.datatable-Country').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });

    </script>
@endsection
