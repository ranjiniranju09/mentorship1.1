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
                    <th>Mentee Name</th>
                    <th>Description</th>
                    <th>Created on</th>
                    <th>File</th>
                    <th>Response</th>
                    <th>Resolved on</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($tickets->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-muted">No tickets found for your mapped mentees.</td>
                    </tr>
                @endif

                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->category }}</td>
                        <td>{{ $ticket->user_name }}</td>
                        <td>{{ $ticket->ticket_description }}</td>
                        <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('Y-m-d') }}</td>
                        <td>
                            @if ($ticket->attachment_url)
                                <a href="{{ $ticket->attachment_url }}" target="_blank">View File</a>
                            @else
                                No file
                            @endif
                        </td>
                        <td>{{ $ticket->response ?? 'No Response Yet' }}</td>
                        <td>
                            @if ($ticket->resolved_on)
                                {{ \Carbon\Carbon::parse($ticket->resolved_on)->format('Y-m-d') }}
                            @else
                                Pending
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('mentor.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill shadow-sm">🗑️</button>
                                </form>

                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#editTicketModal{{ $ticket->id }}">
                                    ✏️
                                </button>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        @foreach ($tickets as $ticket)
        <!-- Edit Modal -->
        <div class="modal fade" id="editTicketModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="editTicketModalLabel{{ $ticket->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('mentor.tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTicketModalLabel{{ $ticket->id }}">Respond to Ticket</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Category:</strong> {{ $ticket->category }}</p>
                            <p><strong>Mentee:</strong> {{ $ticket->user_name }}</p>
                            <p><strong>Description:</strong> {{ $ticket->ticket_description }}</p>
                            <div class="mb-3">
                                <label for="responseTextarea{{ $ticket->id }}" class="form-label">Response</label>
                                <textarea class="form-control" name="response" id="responseTextarea{{ $ticket->id }}" rows="4">{{ $ticket->response }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update Response</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

        <br><hr><br>
        <h4>Your Submitted Tickets</h4>
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
                        <th>Action</th> {{-- ✅ Add this --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($mentorTickets->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-muted">No tickets submitted by you.</td>
                        </tr>
                    @endif

                    @foreach ($mentorTickets as $ticket)
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
                            <td>{{ $ticket->response ?? 'No Response Yet' }}</td>
                            <td>
                                @if ($ticket->resolved_on)
                                    {{ \Carbon\Carbon::parse($ticket->resolved_on)->format('Y-m-d') }}
                                @else
                                    Pending
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('mentor.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill shadow-sm">🗑️</button>
                                    </form>

                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#editMentorTicketModal{{ $ticket->id }}">
                                        ✏️
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editMentorTicketModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="editMentorTicketModalLabel{{ $ticket->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('mentor.tickets.update', $ticket->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editMentorTicketModalLabel{{ $ticket->id }}">Edit Your Ticket</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="ticketDescription{{ $ticket->id }}" class="form-label">Description</label>
                                                <textarea name="ticket_description" id="ticketDescription{{ $ticket->id }}" class="form-control" rows="4">{{ $ticket->ticket_description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>

            </table>
        </div>


    </div>

</div>

@endsection

@section('scripts')
<script>
    // Optional JavaScript for additional functionality
</script>
@endsection
