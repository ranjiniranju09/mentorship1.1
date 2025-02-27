@extends('layouts.admin')
@section('content')

@can('ticket_response_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ticket-responses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ticketResponse.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'TicketResponse', 'route' => 'admin.ticket-responses.parseCsvImport'])
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.ticketResponse.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TicketResponse">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>{{ trans('cruds.ticketResponse.fields.ticket_category_id') }}</th> <!-- Change this header if needed -->
                    <th>{{ trans('cruds.ticketResponse.fields.attachment_url') }}</th>
                    <th>{{ trans('cruds.ticketResponse.fields.response') }}</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
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

        @can('ticket_response_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.ticket-responses.massDestroy') }}",
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
                            data: { ids: ids, _method: 'DELETE' }
                        }).done(function () { location.reload() })
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
    ajax: "{{ route('admin.ticket-descriptions.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'ticket_category.category_description', name: 'ticket_category.category_description' },
        {
            data: 'attachment_url', 
            name: 'attachment_url', 
            sortable: false, 
            searchable: false,
            render: function(data, type, row) {
                // Log the data to see what is being returned
                console.log(data); // Check if data is coming through

                // Check if the attachment URL exists
                if (data && data !== null && data !== '') {
                    return `<a href="${data}" target="_blank">${data}</a>`;
                } else {
                    return 'No Attachment'; // Return a message if no URL exists
                }
            }
        },
        { data: 'response', name: 'response' },
        { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[1, 'desc']],
    pageLength: 100,
};





        let table = $('.datatable-TicketResponse').DataTable(dtOverrideGlobals);

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        let visibleColumnsIndexes = null;
        $('.datatable thead').on('input', '.search', function () {
            let strict = $(this).attr('strict') || false;
            let value = strict && this.value ? "^" + this.value + "$" : this.value;
            let index = $(this).parent().index();

            if (visibleColumnsIndexes !== null) {
                index = visibleColumnsIndexes[index];
            }

            table
                .column(index)
                .search(value, strict)
                .draw();
        });

        table.on('column-visibility.dt', function(e, settings, column, state) {
            visibleColumnsIndexes = [];
            table.columns(":visible").every(function(colIdx) {
                visibleColumnsIndexes.push(colIdx);
            });
        });
    });
</script>
@endsection
