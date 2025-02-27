@can('create_progress_table_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.create-progress-tables.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.createProgressTable.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.createProgressTable.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-lessonCreateProgressTables">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.lesson') }}
                        </th>
                        <th>
                            {{ trans('cruds.createProgressTable.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($createProgressTables as $key => $createProgressTable)
                        <tr data-entry-id="{{ $createProgressTable->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $createProgressTable->id ?? '' }}
                            </td>
                            <td>
                                {{ $createProgressTable->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $createProgressTable->lesson->chaptername ?? '' }}
                            </td>
                            <td>
                                {{ App\CreateProgressTable::STATUS_SELECT[$createProgressTable->status] ?? '' }}
                            </td>
                            <td>
                                @can('create_progress_table_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.create-progress-tables.show', $createProgressTable->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('create_progress_table_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.create-progress-tables.edit', $createProgressTable->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('create_progress_table_delete')
                                    <form action="{{ route('admin.create-progress-tables.destroy', $createProgressTable->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('create_progress_table_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.create-progress-tables.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-lessonCreateProgressTables:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection