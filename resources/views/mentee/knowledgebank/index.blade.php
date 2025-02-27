@extends('layouts.new_mentee')

@section('content')
<style>
    .container {
        margin-top: 30px;
    }
    .list-group-item {
        background-color: #f8f9fa;
        border: none;
        border-left: 4px solid transparent;
        color: #007bff;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .list-group-item:hover, .list-group-item.active {
        background-color: #007bff;
        color: #fff;
        border-left: 4px solid #0056b3;
    }
    .scrollspy-example {
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-height: 80vh;
        overflow-y: auto;
    }
    .scrollspy-example h4 {
        margin-top: 30px;
        font-size: 1.5rem;
        color: #343a40;
    }
    .scrollspy-example p {
        font-size: 1rem;
        color: #555;
        line-height: 1.6;
    }
    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    .nav-buttons .btn {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
        font-weight: bold;
    }
    .nav-buttons .btn:hover {
        background-color: #0056b3;
    }
</style>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div id="list-example" class="list-group">
                @foreach($resources as $resource)
                    <a class="list-group-item list-group-item-action" href="#list-item-{{ $resource->id }}" onclick="showResource({{ $resource->id }})">
                        {{ $resource->title }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-md-8">
            <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
                @foreach($resources as $resource)
                    <div class="resource-content" id="resource-{{ $resource->id }}" style="display:none;">
                        <h4>{{ $resource->title }}</h4>
                        <p>{{ $resource->description }}</p>
                        @if($resource->module_id)
                            <p><strong>Module Name:</strong> {{ $resource->module_name ?? 'Not Assigned' }}</p>
                        @else
                            <p><strong>Module Name:</strong> Not Assigned</p>
                        @endif
                        @if($resource->file_path)
                            <p><a href="{{ $resource->file_path }}" target="_blank">{{ $resource->file_path }}</a></p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#list-example'
        });
        
        // Initially show the first resource
        showResource({{ $resources->first()->id }});
    });

    function showResource(resourceId) {
        // Hide all resources
        var resources = document.querySelectorAll('.resource-content');
        resources.forEach(function(resource) {
            resource.style.display = 'none';
        });

        // Show the selected resource
        var selectedResource = document.getElementById('resource-' + resourceId);
        if (selectedResource) {
            selectedResource.style.display = 'block';
        }
    }
</script>
@endsection
