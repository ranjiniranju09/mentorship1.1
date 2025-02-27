@can('chapter_test_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.chapter-tests.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.chapterTest.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.chapterTest.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-moduleidChapterTests">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.chapterTest.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.chapterTest.fields.moduleid') }}
                        </th>
                        <th>
                            {{ trans('cruds.chapterTest.fields.chapter') }}
                        </th>
                        <th>
                            {{ trans('cruds.chapterTest.fields.test_title') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chapterTests as $key => $chapterTest)
                        <tr data-entry-id="{{ $chapterTest->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $chapterTest->id ?? '' }}
                            </td>
                            <td>
                                {{ $chapterTest->moduleid->name ?? '' }}
                            </td>
                            <td>
                                {{ $chapterTest->chapter->chaptername ?? '' }}
                            </td>
                            <td>
                                {{ $chapterTest->test_title ?? '' }}
                            </td>
                            <td>
                                @can('chapter_test_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.chapter-tests.show', $chapterTest->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('chapter_test_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.chapter-tests.edit', $chapterTest->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('chapter_test_delete')
                                    <form action="{{ route('admin.chapter-tests.destroy', $chapterTest->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('chapter_test_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.chapter-tests.massDestroy') }}",
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
  let table = $('.datatable-moduleidChapterTests:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection