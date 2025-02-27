@can('subchapter_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.subchapters.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.subchapter.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.subchapter.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-chapterSubchapters">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.subchapter.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.subchapter.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.subchapter.fields.chapter') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subchapters as $key => $subchapter)
                        <tr data-entry-id="{{ $subchapter->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $subchapter->id ?? '' }}
                            </td>
                            <td>
                                {{ $subchapter->title ?? '' }}
                            </td>
                            <td>
                                {{ $subchapter->chapter->chaptername ?? '' }}
                            </td>
                            <td>
                                @can('subchapter_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.subchapters.show', $subchapter->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('subchapter_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.subchapters.edit', $subchapter->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('subchapter_delete')
                                    <form action="{{ route('admin.subchapters.destroy', $subchapter->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('subchapter_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.subchapters.massDestroy') }}",
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
  let table = $('.datatable-chapterSubchapters:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection