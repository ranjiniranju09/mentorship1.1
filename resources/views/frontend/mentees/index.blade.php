@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('mentee_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.mentees.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.mentee.title_singular') }}
                        </a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                            {{ trans('global.app_csvImport') }}
                        </button>
                        @include('csvImport.modal', ['model' => 'Mentee', 'route' => 'admin.mentees.parseCsvImport'])
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.mentee.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Mentee">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.mentee.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.mobile') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.dob') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.skilss') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.interestedskills') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.mentee.fields.languagesspoken') }}
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
                                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                                    </td>
                                    <td>
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($languagespokens as $key => $item)
                                                <option value="{{ $item->langname }}">{{ $item->langname }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mentees as $key => $mentee)
                                    <tr data-entry-id="{{ $mentee->id }}">
                                        <td>
                                            {{ $mentee->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mentee->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mentee->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mentee->mobile ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mentee->dob ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mentee->skilss ?? '' }}
                                        </td>
                                        <td>
                                            {{ $mentee->interestedskills ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($mentee->languagesspokens as $key => $item)
                                                <span>{{ $item->langname }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('mentee_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.mentees.show', $mentee->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('mentee_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.mentees.edit', $mentee->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('mentee_delete')
                                                <form action="{{ route('frontend.mentees.destroy', $mentee->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('mentee_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.mentees.massDestroy') }}",
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
  let table = $('.datatable-Mentee:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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