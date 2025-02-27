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

    .display-resources {
        margin-left: 40px;
        width: 95%;
    }

    .btn-close {
        float: right;
        background: none;
        border: none;
        font-size: 1.5rem;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
    }

    .btn-close:hover {
        color: #000;
        text-decoration: none;
        opacity: .75;
    }

    .session-table {
        margin-top: 20px;
    }

    .status {
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
        text-transform: uppercase;
    }
</style>

<div class="display-resources">
    <h2>Knowledge Bank</h2>
    <span>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addResourceModal">
            Add Resource
        </button>
    </span>

    <div class="row">
        <!-- Add Resource Modal -->
        <div class="modal fade" id="addResourceModal" tabindex="-1" role="dialog" aria-labelledby="addResourceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addResourceModalLabel">Add New Resource</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addResourceForm" method="POST" action="{{ route('resources.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="resourceName">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="module_id">Select Module</label>
                                <select class="form-control" id="module_id" name="module_id" required>
                                    <option value="" disabled selected>------Select Here-----</option>
                                    @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file_path">Related Link:</label>
                                <input type="url" class="form-control" id="file_path" name="file_path">
                            </div>
                            <div class="form-group">
                                <label for="type">Category:</label>
                                <select class="form-control" id="type" name="type">
                                    <option>Public</option>
                                    <option>Private</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Resource</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered resource-table">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Link</th>
                <th>Type</th>
                <th>Module</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resources as $resource)
            <tr>
                <td>{{ $resource->id }}</td>
                <td>{{ $resource->title }}</td>
                <td>{{ $resource->description }}</td>
                <td><a href="{{ $resource->file_path }}" target="_blank">{{ $resource->file_path }}</a></td>
                <td>{{ $resource->type }}</td>
                <td>
                    @foreach($modules as $module)
                        @if($module->id == $resource->module_id)
                            {{ $module->name }}
                        @endif
                    @endforeach
                </td>
                <td>
                    <span>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editResourceModal{{ $resource->id }}">
                            Edit
                        </button>
                    </span>
                    <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this resource?')">
                        <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Edit Resource Modal -->
            <div class="modal fade" id="editResourceModal{{ $resource->id }}" tabindex="-1" role="dialog" aria-labelledby="editResourceModalLabel{{ $resource->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editResourceModalLabel{{ $resource->id }}">Edit Resource</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editResourceForm{{ $resource->id }}" method="POST" action="{{ route('resources.update', $resource->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $resource->id }}">
                                <div class="form-group">
                                    <label for="editResourceName">Title</label>
                                    <input type="text" class="form-control" id="editResourceName" name="editResourceName" value="{{ $resource->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="module_id">Select Module</label>
                                    <select class="form-control" id="module_id" name="module_id" required>
                                        <option value="" disabled>Select Module</option>
                                        @foreach($modules as $module)
                                            <option value="{{ $module->id }}" {{ $resource->module_id == $module->id ? 'selected' : '' }}>
                                                {{ $module->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editdescription">Description:</label>
                                    <textarea class="form-control" id="editdescription" name="editdescription" rows="3" required>{{ $resource->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="file_path">Related Link:</label>
                                    <input type="url" class="form-control" id="file_path" name="file_path" value="{{ $resource->file_path }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="edittype">Type:</label>
                                    <select class="form-control" id="edittype" name="edittype">
                                        <option value="public" {{ $resource->type == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ $resource->type == 'private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
@endpush
