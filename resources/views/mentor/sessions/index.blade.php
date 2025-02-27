@extends('layouts.mentor')
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h1>Session Management</h1>
<!-- Add Session Button -->
 <span>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addSessionModal">
        Add Session
    </button>
    <button type="button" class="btn btn-warning mb-3" data-toggle="modal" data-target="#uploadRecordingModal">
        Upload recording
    </button>
</span>
    
    <!-- Add Session Modal -->
<div class="modal fade" id="addSessionModal" tabindex="-1" role="dialog" aria-labelledby="addSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSessionModalLabel">Add Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('sessions.store') }}" method="POST" >
                @csrf
                <div class="modal-body">
                    @php
                        $firstAssignment = $assignments->first();
                        $defaultMentorId = $firstAssignment->mentorname_id ?? '';
                        $defaultMenteeId = $firstAssignment->menteename_id ?? '';
                        $defaultMentor = $mentornames[$defaultMentorId] ?? '';
                        $defaultMentee = $menteenames[$defaultMenteeId] ?? '';
                    @endphp
                    
                    <!-- Hidden Mentor ID -->
                    <input type="hidden" id="mentorname_id" name="mentorname_id" value="{{ $defaultMentorId }}">
                    
                    <!-- Hidden Mentee ID -->
                    <input type="hidden" id="menteename_id" name="menteename_id" value="{{ $defaultMenteeId }}">
                    
                    <!-- Display Mentor Name -->
                    <div class="form-group">

                        <label class="required" for="modulename_id">{{ trans('cruds.session.fields.modulename') }}</label>
                        <select class="form-control select2 {{ $errors->has('modulename') ? 'is-invalid' : '' }}" name="modulename_id" id="modulename_id" required>
                            @foreach($modulenames as $id => $entry)
                                <option value="{{ $id }}" {{ old('modulename_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('modulename'))
                            <div class="invalid-feedback">
                                {{ $errors->first('modulename') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.session.fields.modulename_helper') }}</span>
                    </div>
            
            
                    {{--<div class="form-group">
                        <label class="required" for="sessiondatetime">Session Date & Time</label>
                        <input class="form-control datetime {{ $errors->has('sessiondatetime') ? 'is-invalid' : '' }}" 
                            type="datetime-local" 
                            name="sessiondatetime" 
                            id="sessiondatetime" 
                            value="{{ old('sessiondatetime', isset($session) ? $session->sessiondatetime->format('Y-m-d\TH:i') : '') }}" 
                            required>
                        @if($errors->has('sessiondatetime'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sessiondatetime') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.session.fields.sessiondatetime_helper') }}</span>
                    </div>--}}
                    <div class="form-group">
                        <label for="start_date_time">Session Date and Time:</label>
                        <input type="datetime-local" class="form-control" id="sessiondatetime" name="sessiondatetime" required>
                        @if($errors->has('sessiondatetime'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sessiondatetime') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="required" for="sessionlink">{{ trans('cruds.session.fields.sessionlink') }}</label>
                        <input class="form-control {{ $errors->has('sessionlink') ? 'is-invalid' : '' }}" type="text" name="sessionlink" id="sessionlink" value="{{ old('sessionlink', '') }}" required>
                        @if($errors->has('sessionlink'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sessionlink') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.session.fields.sessionlink_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="session_title">{{ trans('cruds.session.fields.session_title') }}</label>
                        <input class="form-control {{ $errors->has('session_title') ? 'is-invalid' : '' }}" type="text" name="session_title" id="session_title" value="{{ old('session_title', '') }}" required>
                        @if($errors->has('session_title'))
                            <div class="invalid-feedback">
                                {{ $errors->first('session_title') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.session.fields.session_title_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.session.fields.session_duration_minutes') }}</label>
                        @foreach(App\Session::SESSION_DURATION_MINUTES_RADIO as $key => $label)
                            <div class="form-check {{ $errors->has('session_duration_minutes') ? 'is-invalid' : '' }}">
                                <input class="form-check-input" type="radio" id="session_duration_minutes_{{ $key }}" name="session_duration_minutes" value="{{ $key }}" {{ old('session_duration_minutes', '') === (string) $key ? 'checked' : '' }} required>
                                <label class="form-check-label" for="session_duration_minutes_{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                        @if($errors->has('session_duration_minutes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('session_duration_minutes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.session.fields.session_duration_minutes_helper') }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Session</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Session Modal -->
@foreach($sessions as $session)
    <form action="{{ route('sessions.update', $session->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="editSessionModal{{ $session->id }}" tabindex="-1" role="dialog" aria-labelledby="editSessionModalLabel{{ $session->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSessionModalLabel{{ $session->id }}">Session Details - {{ $session->session_title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="session_title">Session Title:</label>
                            <input type="text" class="form-control" id="session_title" name="session_title" value="{{ $session->session_title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="sessiondatetime">Date & Time:</label>
                            @php
                                $sessionDateTime = \Carbon\Carbon::parse($session->sessiondatetime)->format('Y-m-d\TH:i');
                            @endphp
                            <input type="datetime-local" class="form-control" id="sessiondatetime" name="sessiondatetime" value="{{ $sessionDateTime }}" required>
                        </div>
                        <div class="form-group">
                            <label for="sessionlink">Session Link:</label>
                            <input type="text" class="form-control" id="sessionlink" name="sessionlink" value="{{ $session->sessionlink }}" required>
                        </div>
                        <div class="form-group">
                            <label for="session_duration_minutes">Duration (minutes):</label>
                            <input type="number" class="form-control" id="session_duration_minutes" name="session_duration_minutes" value="{{ $session->session_duration_minutes }}">
                        </div>
                        <div class="form-group">
                            <label>Mark as Done</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="done" id="doneYes" value="yes" {{ $session->done ? 'checked' : '' }}>
                                <label class="form-check-label" for="doneYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="done" id="doneNo" value="no" {{ !$session->done ? 'checked' : '' }}>
                                <label class="form-check-label" for="doneNo">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endforeach


<!-- Upload Recording Modal -->
<div class="modal fade" id="uploadRecordingModal" tabindex="-1" role="dialog" aria-labelledby="uploadRecordingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadRecordingModalLabel">Upload Recording</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadRecordingForm" method="POST" action="{{ route('upload.recording') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="selectSession">Select Session:</label>
                        <select class="form-control" id="selectSession" name="selectSession" required>
                            <option value="">------------ Select Here ------------</option>
                            @foreach($sessionTitles as $id => $session_title)
                                <option value="{{ $id }}">{{ $session_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recordingFile">Upload Recording:</label>
                        <input type="file" class="form-control-file" id="recordingFile" name="recordingFile" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="uploadRecordingBtn">Upload Recording</button>
                </form>
            </div>
        </div>
    </div>
</div>




<div class="container">
    <!-- Sessions Table -->
    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Module</th>
                    <th>Title</th>
                    <th>Date & Time</th>
                    <th>Duration (minutes)</th>
                    <th>Link</th>
                    <th>Recording</th> <!-- New column for Recording -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $modulenames[$session->modulename_id] ?? 'N/A' }}</td>
                        <td>{{ $session->session_title }}</td>
                        <td>{{ $session->sessiondatetime ?? 'No Date' }}</td>
                        <td>{{ $session->session_duration_minutes }}</td>
                        <td>
                            @php
                                $isPast = now()->greaterThanOrEqualTo(\Carbon\Carbon::parse($session->sessiondatetime));
                            @endphp
                            <a href="{{ strpos($session->sessionlink, 'http') === 0 ? $session->sessionlink : 'http://' . $session->sessionlink }}" 
                            class="btn btn-success {{ $isPast ? 'disabled' : '' }}" 
                            target="_blank">
                                Join
                            </a>



                        </td>
                        <td>
                            @if(!empty($session->file_path) && $session->done)
                                <a href="{{ $session->file_path }}" class="btn btn-warning" target="_blank">
                                    <i class="fa fa-video-camera" aria-hidden="true"></i> 
                                </a>
                            @else
                                <span class="text-muted">Not Available</span>
                            @endif
                        </td>


                        <td>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editSessionModal{{ $session->id }}">
                                Edit
                            </button>
                            <form action="{{ route('sessions.destroy', $session->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this session?');">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('#sessiondatetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true
        });
    });

    $(document).ready(function() {
    $('#uploadRecordingForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '{{ route("upload.recording") }}', // Ensure this route points to your upload method
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    $('#uploadRecordingModal').modal('hide');
                    // Optionally, refresh the page or update the UI
                }
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
});

</script>
@endpush
