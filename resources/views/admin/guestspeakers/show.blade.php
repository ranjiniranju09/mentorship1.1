@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.guestspeaker.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.guestspeakers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.guestspeaker.fields.id') }}
                        </th>
                        <td>
                            {{ $guestspeaker->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.guestspeaker.fields.speakername') }}
                        </th>
                        <td>
                            {{ $guestspeaker->speakername }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.guestspeaker.fields.speaker_name') }}
                        </th>
                        <td>
                            {{ $guestspeaker->speaker_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.guestspeaker.fields.speakermobile') }}
                        </th>
                        <td>
                            {{ $guestspeaker->speakermobile }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.guestspeakers.index') }}">
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
            <a class="nav-link" href="#speaker_name_guest_lectures" role="tab" data-toggle="tab">
                {{ trans('cruds.guestLecture.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="speaker_name_guest_lectures">
            @includeIf('admin.guestspeakers.relationships.speakerNameGuestLectures', ['guestLectures' => $guestspeaker->speakerNameGuestLectures])
        </div>
    </div>
</div>

@endsection