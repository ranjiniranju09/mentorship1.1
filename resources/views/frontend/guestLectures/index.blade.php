@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('guest_lecture_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.guest-lectures.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.guestLecture.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'GuestLecture', 'route' => 'admin.guest-lectures.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.guestLecture.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-GuestLecture">
                            <thead>
                                <tr>
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
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($guestspeakers as $key => $item)
                                                <option value="{{ $item->speakername }}">{{ $item->speakername }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($mentees as $key => $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\GuestLecture::GUEST_SESSION_DURATION_RADIO as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\GuestLecture::PLATFORM_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guestLectures as $key => $guestLecture)
                                    <tr data-entry-id="{{ $guestLecture->id }}">
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
                                                <span>{{ $item->name }}</span>
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
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.guest-lectures.show', $guestLecture->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('guest_lecture_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.guest-lectures.edit', $guestLecture->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('guest_lecture_delete')
                                                <form action="{{ route('frontend.guest-lectures.destroy', $guestLecture->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('guest_lecture_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.guest-lectures.massDestroy') }}",
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
  let table = $('.datatable-GuestLecture:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection