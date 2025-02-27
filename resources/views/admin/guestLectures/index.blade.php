
@extends('layouts.admin')
@section('content')
@can('guest_lecture_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.guest-lectures.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.guestLecture.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'GuestLecture', 'route' => 'admin.guest-lectures.parseCsvImport'])

            <a class="btn btn-info" href="{{route('admin.guestLectures.uploadRecording')}}"> Upload Guest Recording </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.guestLecture.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-GuestLecture">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>{{ trans('cruds.guestLecture.fields.id') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.guessionsession_title') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.speaker_name') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.invited_mentees') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.guestsession_date_time') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.guest_session_duration') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.platform') }}</th>
                    <th>{{ trans('cruds.guestLecture.fields.recording') }}</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td></td>
                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
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
                    <td><input class="search" type="text" placeholder="{{ trans('global.search') }}"></td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\GuestLecture::GUEST_SESSION_DURATION_RADIO as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\GuestLecture::PLATFORM_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td></td>
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
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('guest_lecture_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.guest-lectures.massDestroy') }}",
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
            buttons: dtButtons,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.guest-lectures.index') }}",
            columns: [
                {
                    data: 'placeholder',
                    name: 'placeholder',
                    defaultContent: '', // Prevents errors for missing data
                },
                { data: 'id', name: 'id' },
                { data: 'guessionsession_title', name: 'guessionsession_title' },
                { data: 'speaker.speakername', name: 'speaker.speakername' },
                { data: 'invited_mentees', name: 'invited_mentees.name' },
                { data: 'guestsession_date_time', name: 'guestsession_date_time' },
                { data: 'guest_session_duration', name: 'guest_session_duration' },
                { data: 'platform', name: 'platform' },
                { 
                    data: 'file_path',
                    name: 'recordings',
                    render: function(data, type, row) {
                        if (data) {
                            return `<a href="${data}" target="_blank" class="btn btn-sm btn-primary">Download</a>`;
                        }
                        return 'No Recording Available';
                    }
                },
                { data: 'actions', name: '{{ trans('global.actions') }}' }
            ],
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        };
        let table = $('.datatable-GuestLecture').DataTable(dtOverrideGlobals);

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
    });
</script>
@endsection
