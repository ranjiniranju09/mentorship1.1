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
            <h4>Overall Module Report</h4> 
            <div class="toggle-buttons">
                <button class="btn btn-success" id="tableViewBtn">Overall Module Report</button>
            </div>

            <!-- Export Button - visible for all tables -->
            <div class="export-button-container">
                <form action="{{ route('admin.modulereport.export') }}" method="get">
                    @csrf
                    <button type="submit" class="btn btn-success">Export to Excel</button>
                </form>
            </div>
        </div>

        <!-- Default Table (Module Progress) -->
        <div class="tableContainer" id="moduleProgressTable">
            <h3>Chapterwise Completion Report</h3>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Mentee Name</th>
                        <th>Mentor Name</th>
                        <th>Module Name</th>
                        <th>Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($progressData as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->mentee_name }}</td>
                            <td>{{ $data->mentor_name }}</td>
                            <td>{{ $data->module_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->completed_at)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

<!-- JavaScript -->
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tableViewBtn').on('click', function () {
            $('#moduleProgressTable').toggle(); // Toggle visibility of the table
        });
    });
</script>
@endsection
