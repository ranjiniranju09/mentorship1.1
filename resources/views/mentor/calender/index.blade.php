@extends('layouts.mentor')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet" />

    <div class="container">
        <!-- <h3>Calendar</h3> -->
        <div id="calendar"></div> 
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize the calendar
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($calendarSessions) // Pass the sessions data to FullCalendar
            });

            calendar.render();
        });
    </script>
@endsection
