@extends('layouts.new_mentee')

@section('content')
<script>
    window._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>
<style>
        body {
            font-family: "Ubuntu", sans-serif;
            background-color: #f4f7f6;
        }
        /* .sidebar {
            position: fixed;
            width: 250px;
            height: 100%;
            background: linear-gradient(to bottom, #000000, #4ca1af);
            transition: 0.5s;
            overflow: hidden;
        }
        .sidebar a {
            position: relative;
            display: block;
            width: 100%;
            text-decoration: none;
            color: white;
            padding: 15px 10px;
            margin-bottom: 10px;
            align-content: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar a:hover {
            color: #cc66ff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .hovered a {
            color: blue;
        }
        .sidebar a i {
            margin-right: 15px;
        } */
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.5s;
        }
        .topbar, .dashboard-header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar {
            width: 100%;
            height: 60px;
            padding: 0 10px;
        }
        .toggle {
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            cursor: pointer;
        }
        .search {
            width: 400px;
            margin: 0 10px;
            position: relative;
        }
        .search label {
            width: 100%;
        }
        .search label input {
            width: 100%;
            height: 40px;
            border-radius: 40px;
            padding: 5px 20px;
            padding-left: 35px;
            font-size: 18px;
            outline: none;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .search label ion-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            font-size: 1.2rem;
            transform: translateY(-50%);
        }
        .user {
            width: 100px;
            height: 50px;
            cursor: pointer;
        }
        .user img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .greeting {
            font-size: 24px;
            color: green;
            font-weight: bold;
        }
        .start-btn {
            background-color: #5cb85c;
            color: #fff;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .start-btn:hover {
            background-color: #4cae4c;
        }
        .dashboard-header-wrapper {
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                height: 100%;
                position: fixed;
                left: -250px;
                z-index: 1000;
            }
            .sidebar.active {
                left: 0;
            }
            .content {
                margin-left: 0;
            }
            .toggle {
                display: block;
            }
        }
        .academic-record, .assigned-tasks, .calendar, .mentor-details, .notifications, .recent-activities, .meetings {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .academic-record:hover, .assigned-tasks:hover, .mentor-details:hover, .notifications:hover, .recent-activities:hover, .meetings:hover {
            transform: translateY(-5px);
        }
        .progress {
            height: 20px;
            border-radius: 10px;
        }
        .module-name {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        .calendar-item, .notification-item, .mentor-detailsitems, .activity-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease-in-out;
        }
        .calendar-item:last-child, .notification-item:last-child, .activity-item:last-child {
            border-bottom: none;
        }
        .calendar-item:hover, .notification-item:hover, .activity-item:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .calendar-time, .notification-time, .activity-time {
            font-size: 18px;
            color: #333;
            display: block;
            font-weight: bold;
        }
        .calendar-event, .notification-event, .activity-event {
            font-size: 16px;
            color: #666;
            display: block;
        }
        .top-performer {
            text-align: center;
            margin-top: 30px;
        }
        .top-performer img {
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .top-performer h3 {
            margin-top: 10px;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
        .top-performer p {
            color: #666;
        }
        .card-icon {
            font-size: 36px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .custom-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .custom-card:hover {
            transform: translateY(-5px);
        }
        .custom-card.project {
            border-left: 5px solid #007bff;
        }
        .custom-card.task {
            border-left: 5px solid #28a745;
        }
        .custom-card.quizzes {
            border-left: 5px solid #ffc107;
        }
        .custom-card.project .card-icon {
            color: #007bff;
        }
        .custom-card.task .card-icon {
            color: #28a745;
        }
        .custom-card.quizzes .card-icon {
            color: #ffc107;
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e9f7fe;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table-container {
            padding: 10px;
            margin-bottom: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            background-color: #f8f9fa;
        }

        .modal-footer {
            background-color: #f8f9fa;
        }

        /* .table-container {
            margin-top: 20px;
        } */

    </style>
<div class="container">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<center><h1>Assignments</h1></center>
<hr>
    <h2>Pending Assignments</h2>
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Assign_task File</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unsubmittedTasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->assigned_date)->format('F j, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('F j, Y') }}</td>
                    <td>
                        @if($task->files)
                            <a href="{{ $task->files }}" class="btn btn-primary" download>Download</a>
                        @else
                            No files available
                        @endif
                    </td>
                    <td>{{ $task->status }}</td>
                    <td>
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalAssigned{{ $task->id }}">View</button>
                    </td>
                </tr>

                <!-- Modal for Assigned Task -->
                <div class="modal fade" id="modalAssigned{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <form method="POST" action="{{ route('tasks.submit') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Task Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5>Task Title</h5>
                                <p>{{ $task->title }}</p>
                                <hr>
                                <h5>Task Due Date</h5>
                                <p>{{ \Carbon\Carbon::parse($task->due_date)->format('F j, Y') }}</p>
                                <hr>
                                <h5>Task Status</h5>
                                <p>{{ $task->status }}</p>
                                <hr>
                                <h5>Task Description</h5>
                                <label>{!! $task->description !!}</label>
                                <hr>
                                <h5>Task Response</h5>
                                <div class="form-group">
                                    <textarea class="form-control ckeditor {{ $errors->has('task_response') ? 'is-invalid' : '' }}" name="task_response" id="task_response">{!! old('task_response') !!}</textarea>
                                    @if($errors->has('task_response'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('task_response') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- File Upload Section -->
                                <div class="form-group">
                                    <label class="required" for="attachments">{{ trans('cruds.assignTask.fields.attachments') }}</label>
                                    <input type="file" name="submitted_files[]" class="form-control {{ $errors->has('attachments') ? 'is-invalid' : '' }}" multiple>
                                    @if($errors->has('attachments'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('attachments') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.assignTask.fields.attachments_helper') }}</span>
                                </div>

                                <input type="hidden" name="mentee_id" value="{{ $mentee->id }}">
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
    <hr>
    <h2>Submitted Assignments</h2>
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Response</th>
                    <th>Submitted File</th> <!-- New Column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submittedTasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->start_date_time)->format('F j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->end_date_time)->format('F j, Y') }}</td>
                        <td>{{ $task->completed ? 'Completed' : 'Pending' }}</td>
                        <td>{{ $task->task_response }}</td> <!-- Task Response Column -->
                        <td>
                            @if($task->submitted_file)
                                <a href="{{ $task->submitted_file }}" target="_blank">Download File</a>
                            @else
                                No File Submitted
                            @endif
                        </td>

                        <td>
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalAssigned{{ $task->id }}">View</button>
                        </td>
                    </tr>

                    <!-- Modal for Task Details -->
                    <div class="modal fade" id="modalAssigned{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('tasks.submit') }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Task Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Task Title</h5>
                                        <p>{{ $task->title }}</p>
                                        <hr>
                                        <h5>Task Due Date</h5>
                                        <p>{{ \Carbon\Carbon::parse($task->end_date_time)->format('F j, Y') }}</p>
                                        <hr>
                                        <h5>Task Status</h5>
                                        <p>{{ $task->completed ? 'Completed' : 'Pending' }}</p>
                                        <hr>
                                        <h5>Task Description</h5>
                                        <label>{!! $task->description !!}</label>
                                        <hr> 
                                        <h5>Submitted Response</h5>
                                        <div class="form-group">
                                            <textarea class="form-control" readonly>{!! $task->task_response !!}</textarea>
                                        </div>
                                        <hr>
                                        <h5>Submitted File</h5>
                                        @if($task->submitted_file)
                                            <a href="{{ asset('storage/' . $task->submitted_file) }}" target="_blank">Download File</a>
                                        @else
                                            <p>No File Submitted</p>
                                        @endif
                                        <input type="hidden" name="mentee_id" value="{{ $mentee->id }}">
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // CKEditor Upload Adapter
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return {
                    upload: function() {
                        return loader.file
                            .then(function (file) {
                                return new Promise(function(resolve, reject) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('tasks.storeCKEditorImages') }}', true);
                                    xhr.setRequestHeader('x-csrf-token', window._token);
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.responseType = 'json';

                                    var genericErrorText = `Couldn't upload file: ${file.name}.`;
                                    xhr.addEventListener('error', function() { reject(genericErrorText) });
                                    xhr.addEventListener('abort', function() { reject() });
                                    xhr.addEventListener('load', function() {
                                        var response = xhr.response;

                                        if (!response || xhr.status !== 201) {
                                            return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                                        }

                                        // Append hidden input to store CKEditor media ID
                                        $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                                        resolve({ default: response.url });
                                    });

                                    if (xhr.upload) {
                                        xhr.upload.addEventListener('progress', function(e) {
                                            if (e.lengthComputable) {
                                                loader.uploadTotal = e.total;
                                                loader.uploaded = e.loaded;
                                            }
                                        });
                                    }

                                    var data = new FormData();
                                    data.append('upload', file);
                                    data.append('crud_id', '{{ $assignTask->id ?? 0 }}');
                                    xhr.send(data);
                                });
                            })
                    }
                };
            }
        }

        // Initialize CKEditor for all elements with class "ckeditor"
        var allEditors = document.querySelectorAll('.ckeditor');
        for (var i = 0; i < allEditors.length; ++i) {
            ClassicEditor.create(
                allEditors[i], {
                    extraPlugins: [SimpleUploadAdapter]
                }
            );
        }
    });

    Dropzone.options.attachmentsDropzone = {
        url: '/tasks/submit', 
        paramName: 'submitted_files[]',
        maxFilesize: 5, 
        acceptedFiles: '.jpeg,.jpg,.png,.pdf,.doc,.docx',
        addRemoveLinks: true,
        success: function(file, response) {
            console.log(response);
        },
        error: function(file, errorMessage) {
            console.log(errorMessage);
        },
        removedfile: function(file) {
            file.previewElement.remove();
            var name = '';
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name;
            } else {
                name = uploadedAttachmentsMap[file.name];
            }
            $('form').find('input[name="attachments[]"][value="' + name + '"]').remove();
        },
        init: function() {
            @if(isset($assignTask) && $assignTask->attachments)
                var files = {!! json_encode($assignTask->attachments) !!};
                for (var i in files) {
                    var file = files[i];
                    this.options.addedfile.call(this, file);
                    file.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">');
                }
            @endif
        }
    };

</script>

@endsection
