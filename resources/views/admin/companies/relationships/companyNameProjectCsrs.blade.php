@can('project_csr_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.project-csrs.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.projectCsr.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.projectCsr.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-companyNameProjectCsrs">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.projectCsr.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.projectCsr.fields.project_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.projectCsr.fields.company_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.projectCsr.fields.active') }}
                        </th>
                        <th>
                            {{ trans('cruds.projectCsr.fields.financial_year') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projectCsrs as $key => $projectCsr)
                        <tr data-entry-id="{{ $projectCsr->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $projectCsr->id ?? '' }}
                            </td>
                            <td>
                                {{ $projectCsr->project_name ?? '' }}
                            </td>
                            <td>
                                {{ $projectCsr->company_name->companyname ?? '' }}
                            </td>
                            <td>
                                {{ App\ProjectCsr::ACTIVE_SELECT[$projectCsr->active] ?? '' }}
                            </td>
                            <td>
                                {{ $projectCsr->financial_year->financial_year ?? '' }}
                            </td>
                            <td>
                                @can('project_csr_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.project-csrs.show', $projectCsr->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('project_csr_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.project-csrs.edit', $projectCsr->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('project_csr_delete')
                                    <form action="{{ route('admin.project-csrs.destroy', $projectCsr->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('project_csr_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.project-csrs.massDestroy') }}",
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
    pageLength: 10,
  });
  let table = $('.datatable-companyNameProjectCsrs:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection