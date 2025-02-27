@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Resource Approvals</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Added By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvals as $approval)
            <tr>
                <td>{{ $approval->resource->title }}</td>
                <td>{{ ucfirst($approval->resource->type) }}</td>
                <td>{{ $approval->resource->added_by }}</td>
                <td>
                    <form action="{{ route('resource_approvals.approve', $approval) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
