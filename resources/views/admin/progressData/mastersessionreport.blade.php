@extends('layouts.admin')
@section('content')

<style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .academic-record {
        margin-left: 50px;
    }

    .tableContainer {
        margin-top: 30px;
        width: 1000px;
        margin-left: 50px;
        display: none; /* Hidden by default */
    }

    .export-button-container {
        margin-top: 15px;
        text-align: right;
    }
</style>

<div class="card">
    <div class="card-header">
        Module Progress
    </div>

    <div class="card-body">
        <div class="academic-record">
            <!-- <h4>Master Session Report</h4> -->
            <div class="toggle-buttons">
                <button class="btn btn-success" id="tableViewBtn">Master Session Report</button>
            </div>

            <!-- Export Button -->
            <div class="export-button-container">
                <form action="{{ route('admin.mastersessionprogress.export') }}" method="get">
                    @csrf
                    <button type="submit" class="btn btn-success">Export to Excel</button>
                </form>
            </div>

        </div>

        <!-- Chapterwise Progress Table -->
        <div class="tableContainer" id="chapterwiseProgressTable">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Mentor</th>
                <th>Completed Module Name</th>
                <th>Pending Modules Name</th>
                <th>Total Hours Engaged</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessionProgressData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->mentor_name }}</td>
                    <td>{{ $data->completed_modules_names }}</td>
                    <td>{{ $data->pending_modules_names }}</td>
                    <td>{{ $data->total_hours_engaged ?? 0 }} hours</td> <!-- Displaying total hours engaged -->
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initially hide both tables (if needed)
        $('#chapterwiseProgressTable').hide();

        // Button click events
        $('#tableViewBtn').click(function() {
            $('#chapterwiseProgressTable').toggle(); // Toggle visibility
        });
    });
</script>
@endsection
