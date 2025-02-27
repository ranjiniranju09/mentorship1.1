@extends('layouts.admin')
@section('content')
@can('test_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.tests.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.test.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Test', 'route' => 'admin.tests.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.test.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Test">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>{{ trans('cruds.test.fields.id') }}</th>
                    <th>Chapter</th>
                    <th>{{ trans('cruds.test.fields.title') }}</th>
                    <th>{{ trans('cruds.test.fields.is_published') }}</th>

                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td></td>
                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                    <td>
                        <select class="search">
                            <option value="">{{ trans('global.all') }}</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->chaptername }}">{{ $chapter->chaptername }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                    <td></td>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(document).ready(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        @can('test_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.tests.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id;
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}');
                    return;
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload() });
                }
            }
        };
        dtButtons.push(deleteButton);
        @endcan

        let dtOverrideGlobals = {
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: {
                url: "{{ route('admin.tests.index') }}",
                data: function (d) {
                    d.search_id = $('input[type="text"]').eq(0).val();
                    d.search_chapter = $('select.search').eq(0).val();
                    d.search_title = $('input[type="text"]').eq(2).val();
                    d.search_is_published = $('input[type="text"]').eq(3).val();
                }
            },
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'id', name: 'id' },
                { data: 'chaptername', name: 'chaptername' },
                { data: 'title', name: 'title' },
                { data: 'is_published', name: 'is_published' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            destroy: true,
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100
        };

        let table = $('.datatable-Test').DataTable(dtOverrideGlobals);

        // Handle column visibility changes and search input
        $('.datatable thead').on('input', '.search', function () {
            let strict = $(this).attr('strict') || false;
            let value = strict && this.value ? "^" + this.value + "$" : this.value;

            let index = $(this).parent().index();
            table.column(index).search(value, strict).draw();
        });
    });
</script>



@endsection