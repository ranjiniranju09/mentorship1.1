@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Add Resource</h1>
    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        {{--
        <div class="form-group">
            <label for="file">File</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>--}}
        <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="module">Module</option>
                <option value="normal">Normal</option>
            </select>
        </div>
        <div class="form-group">
            <label for="module_id">Module (if applicable)</label>
            <select class="form-control" id="module_id" name="module_id">
                <option value="">Select Module</option>
                @foreach($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="visibility">Visibility</label>
            <select class="form-control" id="visibility" name="visibility">
                <option value="assigned">Only assigned mentees</option>
                <option value="all">All mentees</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Resource</button>
    </form>
</div>
@endsection
