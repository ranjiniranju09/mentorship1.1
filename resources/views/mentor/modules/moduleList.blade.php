@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- <h1>Hello</h1> -->


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Mentorship Meetings</div>
                <div class="card-body">
                   <table class="table table-striped ">
                       <thead>
                           <th scope="col">Module Name</th>
                           <th scope="col">Status</th>
                           <th scope="col">Date</th>
                           <th scope="col">Meeting Link</th>
                           <th>Recording</th>
                           <th>Action</th>
                       </thead>
                       <tbody>
                        @foreach($modules as $module)
                        <tr>
                            <td>{{ $module->name }}</td>
                            <td>
                                @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                    @foreach($sessions[$module->id] as $session)
                                        @if(!empty($session->sessiondatetime))
                                            <span class="badge badge-primary">Scheduled</span>
                                        @else
                                            <span class="badge badge-dark">Not Scheduled</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge badge-dark">Not Scheduled</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                        @foreach($sessions[$module->id] as $session)
                                            {{ $session->sessiondatetime }}<br>
                                         @endforeach
                                    @else
                                    
                                @endif
                            </td>
                            <td>
                                                                
                                    @if(isset($sessions[$module->id]) && count($sessions[$module->id]) > 0)
                                        @foreach($sessions[$module->id] as $session)
                                            <a href="{{ $session->sessionlink }}" target="_blank" class="btn btn-xs btn-primary">Meeting Link</a><br>
                                        @endforeach
                                    @endif

                                
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                <a href="{{ route('mentor.markChapterCompletion', $module->id) }}" class="btn btn-xs btn-primary">Mark Chapter Completions</a> 
                                
                            </td>
                        </tr>
                        @endforeach
                       
                       </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')

@endpush