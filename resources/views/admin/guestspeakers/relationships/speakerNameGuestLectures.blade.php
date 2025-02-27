@can('guest_lecture_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.guest-lectures.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.guestLecture.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.guestLecture.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-speakerNameGuestLectures">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.guessionsession_title') }}
                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.speaker_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.invited_mentees') }}
                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.guestsession_date_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.guest_session_duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.guestLecture.fields.platform') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guestLectures as $key => $guestLecture)
                        <tr data-entry-id="{{ $guestLecture->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $guestLecture->id ?? '' }}
                            </td>
                            <td>
                                {{ $guestLecture->guessionsession_title ?? '' }}
                            </td>
                            <td>
                                {{ $guestLecture->speaker_name->speakername ?? '' }}
                            </td>
                            <td>
                                @foreach($guestLecture->invited_mentees as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $guestLecture->guestsession_date_time ?? '' }}
                            </td>
                            <td>
                                {{ App\GuestLecture::GUEST_SESSION_DURATION_RADIO[$guestLecture->guest_session_duration] ?? '' }}
                            </td>
                            <td>
                                {{ App\GuestLecture::PLATFORM_SELECT[$guestLecture->platform] ?? '' }}
                            </td>
                            <td>
                                @can('guest_lecture_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.guest-lectures.show', $guestLecture->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('guest_lecture_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.guest-lectures.edit', $guestLecture->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('guest_lecture_delete')
                                    <form action="{{ route('admin.guest-lectures.destroy', $guestLecture->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('guest_lecture_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.guest-lectures.massDestroy') }}",
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
  let table = $('.datatable-speakerNameGuestLectures:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection