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
                <button class="btn btn-success" id="tableViewBtn">Quiz Completion Report</button>
            </div>

            <!-- Export Button -->
            <div class="export-button-container">
                <form action="{{ route('admin.QuizCompleted.export') }}" method="get">
                    @csrf
                    <button type="submit" class="btn btn-success">Export to Excel</button>
                </form>
            </div>

        </div>

        <!-- Chapterwise Progress Table -->
        <div class="tableContainer" id="mentorwiseSessionTable">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Mentee</th>
                        <th>Module</th>
                        <th>Completed Quiz</th>
                        <th>Pending Quiz</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizProgressData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->mentee_name }}</td>
                            <td>{{ $data->module_name }}</td>
                            <td>{{ $data->completed_quiz }}</td>
                            <td>{{ $data->pending_quiz }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No data available</td>
                        </tr>
                    @endforelse
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
        $('#mentorwiseSessionTable').hide();

        // Button click events
        $('#tableViewBtn').click(function() {
            $('#mentorwiseSessionTable').toggle(); // Toggle visibility
        });
    });
</script>
@endsection
