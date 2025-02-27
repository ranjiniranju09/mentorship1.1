@extends('layouts.mentor')

@section('content')
<style>
    .container {
        margin-top: 30px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .header h2 {
        font-size: 2rem;
        color: #343a40;
    }
    .header .btn {
        background-color:rgb(27, 122, 247);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
        font-weight: bold;
    }
    .header .btn:hover {
        background-color: #0056b3;
    }
    .filter {
        margin-bottom: 20px;
    }
    .table-responsive {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .table thead th {
        background-color:rgb(24, 24, 24);
        color: #fff;
        border: none;
    }
    .table tbody tr {
        transition: background-color 0.3s;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .table tbody td {
        border: none;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
    }
    .status-open {
        background-color: #28a745;
        color: #fff;
    }
    .status-closed {
        background-color: #dc3545;
        color: #fff;
    }
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
</style>

<div class="container">
    <div class="header">
        <h2>Tickets</h2>
        <a href="{{ route('mentor.support.create') }}" class="btn">Create New Ticket</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Created on</th>
                    <th>File</th>
                    <th>Response</th>
                    <th>Resolved on</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->category }}</td>
                        <td>{{ $ticket->ticket_description }}</td>
                        <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('Y-m-d') }}</td>
                        <td>
                            @if ($ticket->attachment_url)
                                <a href="{{ $ticket->attachment_url }}" target="_blank">View File</a>
                            @else
                                No file
                            @endif
                        </td>
                        <td>{{ $ticket->response ?? 'No Response Yet' }}</td> <!-- ✅ Show response -->
                        <td>
                            {{--@if ($ticket->resolved_at)
                                {{ \Carbon\Carbon::parse($ticket->resolved_at)->format('Y-m-d') }}
                            @else
                                Pending
                            @endif--}}
                        </td>
                        <td>
                            <!-- Delete Form -->
                            <form action="{{ route('mentor.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">X</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Optional JavaScript for additional functionality
</script>
@endsection
