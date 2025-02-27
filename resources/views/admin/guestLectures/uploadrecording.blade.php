@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Upload Recording</h5>
    </div>
    <div class="card-body">
        <form id="uploadRecordingForm" method="POST" action="{{ route('admin.guestLectures.storeRecording') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="selectSession">Select Session:</label>
                <select class="form-control" id="selectSession" name="selectSession" required>
                    <option value="">------------ Select Here ------------</option>
                    @foreach($guessionsession_title as $id => $session_title)
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



@endsection