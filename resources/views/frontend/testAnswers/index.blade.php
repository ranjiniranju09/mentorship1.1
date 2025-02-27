@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('test_answer_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.test-answers.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.testAnswer.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'TestAnswer', 'route' => 'admin.test-answers.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.testAnswer.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-TestAnswer">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.testAnswer.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.testAnswer.fields.test_result') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.testAnswer.fields.question') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.testAnswer.fields.option') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.testAnswer.fields.is_correct') }}
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
                                            @foreach($test_results as $key => $item)
                                                <option value="{{ $item->score }}">{{ $item->score }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($questions as $key => $item)
                                                <option value="{{ $item->question_text }}">{{ $item->question_text }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($question_options as $key => $item)
                                                <option value="{{ $item->option_text }}">{{ $item->option_text }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($testAnswers as $key => $testAnswer)
                                    <tr data-entry-id="{{ $testAnswer->id }}">
                                        <td>
                                            {{ $testAnswer->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $testAnswer->test_result->score ?? '' }}
                                        </td>
                                        <td>
                                            {{ $testAnswer->question->question_text ?? '' }}
                                        </td>
                                        <td>
                                            {{ $testAnswer->option->option_text ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $testAnswer->is_correct ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $testAnswer->is_correct ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            @can('test_answer_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.test-answers.show', $testAnswer->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('test_answer_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.test-answers.edit', $testAnswer->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('test_answer_delete')
                                                <form action="{{ route('frontend.test-answers.destroy', $testAnswer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('test_answer_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.test-answers.massDestroy') }}",
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
  let table = $('.datatable-TestAnswer:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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