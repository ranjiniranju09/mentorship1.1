@can('session_recording_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.session-recordings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.sessionRecording.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.sessionRecording.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-sessionTitleSessionRecordings">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.sessionRecording.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.sessionRecording.fields.session_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.sessionRecording.fields.session_title') }}
                        </th>
                        <th>
                            {{ trans('cruds.sessionRecording.fields.session_video_file') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessionRecordings as $key => $sessionRecording)
                        <tr data-entry-id="{{ $sessionRecording->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $sessionRecording->id ?? '' }}
                            </td>
                            <td>
                                {{ App\SessionRecording::SESSION_TYPE_SELECT[$sessionRecording->session_type] ?? '' }}
                            </td>
                            <td>
                                {{ $sessionRecording->session_title->session_title ?? '' }}
                            </td>
                            <td>
                                @foreach($sessionRecording->session_video_file as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('session_recording_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.session-recordings.show', $sessionRecording->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('session_recording_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.session-recordings.edit', $sessionRecording->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('session_recording_delete')
                                    <form action="{{ route('admin.session-recordings.destroy', $sessionRecording->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('session_recording_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.session-recordings.massDestroy') }}",
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
  let table = $('.datatable-sessionTitleSessionRecordings:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection