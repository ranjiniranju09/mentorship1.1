@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('lesson_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.lessons.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.lesson.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Lesson', 'route' => 'admin.lessons.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.lesson.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Lesson">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.lesson.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.course') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.thumbnail') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.short_text') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.long_text') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.video') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.position') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.is_published') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.lesson.fields.is_free') }}
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
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($courses as $key => $item)
                                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lessons as $key => $lesson)
                                    <tr data-entry-id="{{ $lesson->id }}">
                                        <td>
                                            {{ $lesson->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $lesson->course->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $lesson->title ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($lesson->thumbnail as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $media->getUrl('thumb') }}">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $lesson->short_text ?? '' }}
                                        </td>
                                        <td>
                                            {{ $lesson->long_text ?? '' }}
                                        </td>
                                        <td>
                                            @if($lesson->video)
                                                <a href="{{ $lesson->video->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $lesson->position ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $lesson->is_published ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $lesson->is_published ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $lesson->is_free ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $lesson->is_free ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            @can('lesson_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.lessons.show', $lesson->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('lesson_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.lessons.edit', $lesson->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('lesson_delete')
                                                <form action="{{ route('frontend.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('lesson_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.lessons.massDestroy') }}",
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
  let table = $('.datatable-Lesson:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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