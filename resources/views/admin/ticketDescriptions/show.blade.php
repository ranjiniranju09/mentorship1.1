@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ticketDescription.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ticket-descriptions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketDescription.fields.id') }}
                        </th>
                        <td>
                            {{ $ticketDescription->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketDescription.fields.ticket_category') }}
                        </th>
                        <td>
                            {{ $ticketDescription->ticket_category->category_description ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketDescription.fields.ticket_description') }}
                        </th>
                        <td>
                            {!! $ticketDescription->ticket_description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketDescription.fields.supporting_files') }}
                        </th>
                        <td>
                            @foreach($ticketDescription->supporting_files as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketDescription.fields.supporting_photo') }}
                        </th>
                        <td>
                            @foreach($ticketDescription->supporting_photo as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketDescription.fields.ticket_title') }}
                        </th>
                        <td>
                            {{ $ticketDescription->ticket_title }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ticket-descriptions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#ticket_description_ticket_responses" role="tab" data-toggle="tab">
                {{ trans('cruds.ticketResponse.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="ticket_description_ticket_responses">
            @includeIf('admin.ticketDescriptions.relationships.ticketDescriptionTicketResponses', ['ticketResponses' => $ticketDescription->ticketDescriptionTicketResponses])
        </div>
    </div>
</div>

@endsection