@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ticketResponse.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ticket-responses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketResponse.fields.id') }}
                        </th>
                        <td>
                            {{ $ticketResponse->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketResponse.fields.ticket_description') }}
                        </th>
                        <td>
                            {{ $ticketResponse->ticket_description->ticket_title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketResponse.fields.ticket_response') }}
                        </th>
                        <td>
                            {!! $ticketResponse->ticket_response !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketResponse.fields.supporting_files') }}
                        </th>
                        <td>
                            @foreach($ticketResponse->supporting_files as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ticketResponse.fields.supporting_photo') }}
                        </th>
                        <td>
                            @foreach($ticketResponse->supporting_photo as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ticket-responses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection