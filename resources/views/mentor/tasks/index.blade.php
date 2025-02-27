@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");
    * {
        font-family: "Ubuntu", sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    :root {
        --blue: #2a2185;
        --white: #fff;
        --gray: #f5f5f5;
        --black1: #222;
        --black2: #999;
    }
    body {
        min-height: 100vh;
        overflow-x: hidden;
        background: var(--gray);
    }
    .container {
        position: relative;
        width: 100%;
    }
    .content {
        margin-top: 15px;
        margin-left: 20px;
    }
</style>

<div class="content">
    <!-- In Progress Tasks -->
    <div class="display-tasks">
        <h2>In Progress Tasks</h2>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
        <table class="table table-bordered task-table">
            <thead class="table-dark">
                <tr>
                <th>Task ID</th>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Task Response</th>
                    <th>Submitted File</th>
                    <th>Action</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                   
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->start_date_time }}</td>
                            <td>{{ $task->end_date_time }}</td>
                            <td>
                                @if($task->files)
                                    <a href="{{ $task->files }}" target="_blank">Download</a>
                                @else
                                    No File
                                @endif
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-primary">{{ ucfirst($task->completed) }}</span>
                            </td>
                            <td>{{ $task->task_response }}</td>
                        <td>
                            @if($task->submitted_file)
                                <a href="{{ $task->submitted_file }}" target="_blank">Download</a>
                            @else
                                No Submitted File
                            @endif
                        </td>
                            <td>
                                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal{{ $task->id }}">Open</button> -->
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#edittaskModal{{ $task->id }}">Edit</button>
                               
                                <!-- Edit Task Modal -->
                                <form action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal fade" id="edittaskModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="edittaskModalLabel{{ $task->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="edittaskModalLabel{{ $task->id }}">Edit Task - {{ $task->title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="title">Task Name:</label>
                                                        <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">Description:</label>
                                                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $task->description }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="start_date_time">Start Date:</label>
                                                        @php
                                                            $startDateTime = \Carbon\Carbon::parse($task->start_date_time)->format('Y-m-d\TH:i');
                                                        @endphp
                                                        <input type="datetime-local" class="form-control" id="start_date_time" name="start_date_time" value="{{ $startDateTime }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="end_date_time">End Date:</label>
                                                        @php
                                                            $endDateTime = \Carbon\Carbon::parse($task->end_date_time)->format('Y-m-d\TH:i');
                                                        @endphp
                                                        <input type="datetime-local" class="form-control" id="end_date_time" name="end_date_time" value="{{ $endDateTime }}" required>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="attachment">File:</label>
                                                        <input type="file" class="form-control" id="attachment" name="file" >
                                                    </div>
                                                    <div>
                                                        <label for="task_response">Task Response:</label>
                                                        <input type="text" class="form-control" id="task_response" name="task_response" value="{{ $task->task_response }}">
                                                    </div>
                                                    <div>
                                                        <label for="mentors_reply">Mentors Reply:</label>
                                                        <input type="text" class="form-control" id="mentors_reply" name="mentors_reply" value="{{ $task->mentors_reply }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="completed">Status:</label>
                                                        <select class="form-control" id="completed" name="completed" required>
                                                            <option value="open" {{ $task->completed == 'open' ? 'selected' : '' }}>Open</option>
                                                            <option value="close" {{ $task->completed == 'close' ? 'selected' : '' }}>Close</option>
                                                        </select>
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
                            </td>
                            <td>
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </form>
                            </td>
                        </tr>
                    
                @empty
                    <tr>
                        <td colspan="9">No tasks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Completed Tasks -->
    <div class="display-tasks mt-5">
    <h2>Completed Tasks</h2>
    <table class="table table-bordered task-table">
        <thead class="table-dark">
            <tr>
                <th>Task ID</th>
                <th>Task Name</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>File</th>
                <th>Status</th>
                <th>Task Response</th>
                <th>Mentors Reply</th>
                <th>Submitted File</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @forelse($taskscomplete as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->start_date_time }}</td>
                    <td>{{ $task->end_date_time }}</td>
                    <td>
                        @if($task->files)
                            <a href="{{ $task->files }}" target="_blank">Download</a>
                        @else
                            No File
                        @endif
                    </td>
                    <td>
                        <span class="badge rounded-pill text-bg-success">{{ ucfirst($task->completed) }}</span>
                    </td>
                    <td>{{ $task->task_response }}</td>
                    <td>
                        @if($task->submitted_file)
                            <a href="{{ $task->submitted_file }}" target="_blank ">Download</a>
                        @else
                            No Submitted File
                        @endif
                    </td>{{ $task->mentors_reply}}<td>

                    </td>
                    <td>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No completed tasks found.</td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

</div>

<!-- Add Task Modal -->
<form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Task Name:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="start_date_time">Start Date:</label>
                        <input type="datetime-local" class="form-control" id="start_date_time" name="start_date_time" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date_time">End Date:</label>
                        <input type="datetime-local" class="form-control" id="end_date_time" name="end_date_time" required>
                    </div>
                    <div class="form-group">
                        <label for="attachment">File:</label>
                        <input type="file" class="form-control" id="attachment" name="attachment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
            $('.delete-task').on('click', function(e) {
                e.preventDefault();

                var taskId = $(this).data('task-id');

                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        url: '/tasks/' + taskId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Remove the task row from the table
                            $('a[data-task-id="' + taskId + '"]').closest('tr').remove();
                            alert('Task deleted successfully');
                        },
                        error: function(xhr) {
                            alert('An error occurred while deleting the task');
                        }
                    });
                }
            });
        });
</script>
@endsection
