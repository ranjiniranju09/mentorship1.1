@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.guestspeaker.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.guestspeakers.index') }}">
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
                            <a class="btn btn-default" href="{{ route('frontend.guestspeakers.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection