@can('recruitment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.recruitments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.recruitment.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.recruitment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-financialYearRecruitments">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.jobrole_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.studentname') }}
                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.recruiter_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.annual_salary') }}
                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.offer_letter_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.recruitment.fields.financial_year') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recruitments as $key => $recruitment)
                        <tr data-entry-id="{{ $recruitment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $recruitment->id ?? '' }}
                            </td>
                            <td>
                                {{ $recruitment->jobrole_name->jobrole_name ?? '' }}
                            </td>
                            <td>
                                @foreach($recruitment->studentnames as $key => $item)
                                    <span class="badge badge-info">{{ $item->fullname }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $recruitment->recruiter_name->recruitername ?? '' }}
                            </td>
                            <td>
                                {{ $recruitment->annual_salary ?? '' }}
                            </td>
                            <td>
                                {{ $recruitment->offer_letter_date ?? '' }}
                            </td>
                            <td>
                                {{ $recruitment->financial_year->financial_year ?? '' }}
                            </td>
                            <td>
                                @can('recruitment_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.recruitments.show', $recruitment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('recruitment_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.recruitments.edit', $recruitment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('recruitment_delete')
                                    <form action="{{ route('admin.recruitments.destroy', $recruitment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('recruitment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.recruitments.massDestroy') }}",
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
  let table = $('.datatable-financialYearRecruitments:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection