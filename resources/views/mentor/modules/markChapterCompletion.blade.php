@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th scope="col">Chapter Name</th>
                        <th>Current Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chapters as $chapter)
                    <tr>
                        <td>{{ $chapter->chaptername }}</td>
                        <td>
                             @if(in_array($chapter->id, $completedChapters))
                                <span class="badge badge-success">Completed</span>
                            @else
                                <span class="badge badge-warning">Not Completed</span>
                            @endif
                        </td>
                        <td>
                            @if(!in_array($chapter->id, $completedChapters))
                            <form method="POST" action="{{ route('chapter.markCompleted') }}">
                                @csrf
                                <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                                <input type="hidden" name="module_id" value="{{ $moduleId }}">
                                <input type="hidden" name="mentee_id" value="{{ $mentee_id }}">
                                <button type="submit" class="btn btn-xs btn-primary">Mark as Completed</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')

@endpush
