@can('moduleresourcebank_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.moduleresourcebanks.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.moduleresourcebank.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.moduleresourcebank.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-moduleModuleresourcebanks">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.module') }}
                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.chapterid') }}
                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.link') }}
                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.resourcefile') }}
                        </th>
                        <th>
                            {{ trans('cruds.moduleresourcebank.fields.resource_photos') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($moduleresourcebanks as $key => $moduleresourcebank)
                        <tr data-entry-id="{{ $moduleresourcebank->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $moduleresourcebank->id ?? '' }}
                            </td>
                            <td>
                                {{ $moduleresourcebank->module->name ?? '' }}
                            </td>
                            <td>
                                {{ $moduleresourcebank->chapterid->chaptername ?? '' }}
                            </td>
                            <td>
                                {{ $moduleresourcebank->title ?? '' }}
                            </td>
                            <td>
                                {{ $moduleresourcebank->link ?? '' }}
                            </td>
                            <td>
                                @foreach($moduleresourcebank->resourcefile as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @foreach($moduleresourcebank->resource_photos as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('moduleresourcebank_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.moduleresourcebanks.show', $moduleresourcebank->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('moduleresourcebank_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.moduleresourcebanks.edit', $moduleresourcebank->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('moduleresourcebank_delete')
                                    <form action="{{ route('admin.moduleresourcebanks.destroy', $moduleresourcebank->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('moduleresourcebank_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.moduleresourcebanks.massDestroy') }}",
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
  let table = $('.datatable-moduleModuleresourcebanks:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection