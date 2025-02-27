@extends('layouts.mentor')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="academic-record">
    <h4>Quiz Progress</h4>
    <div class="toggle-buttons">
        <button class="btn btn-primary" id="overallBtn">Overall Progress</button>
        <button class="btn btn-secondary" id="modulewiseBtn">Modulewise Progress</button>
        <button class="btn btn-success" id="tableViewBtn">Table View</button>
    </div>
    <canvas id="quizChart" class="chart-size"></canvas>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Modulewise Quiz Progress</h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Module</th>
                        <th>Completed</th>
                        <th>Pending</th>
                        <!-- <th>Score</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $status)
                        <tr>
                            <td>{{ $status['module_name'] }}</td>
                            <td>{{ $status['completed_count'] }}</td>
                            <td>{{ $status['pending_count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
</div>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap");

    * {
        font-family: "Ubuntu", sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --blue: #2a2185;
        --white: #fff;
        --gray: #f5f5f5;
        --black1: #222;
        --black2: #999;
    }

    body {
        min-height: 100vh;
        overflow-x: hidden;
        background: var(--gray);
    }

    .container {
        position: relative;
        width: 100%;
    }

    .navigation {
        position: fixed;
        width: 270px;
        height: 100%;
        background: var(--black1);
        transition: 0.5s;
        overflow: hidden;
    }

    .navigation.active {
        width: 80px;
    }

    .navigation ul {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    .navigation ul li {
        position: relative;
        width: 100%;
        list-style: none;
    }

    .navigation ul li a {
        position: relative;
        display: block;
        width: 100%;
        display: flex;
        text-decoration: none;
        color: var(--white);
        padding: 8px 20px;
        transition: background-color 0.3s, color 0.3s;
    }

    .navigation ul li a:hover {
        color: var(--black1);
        background-color: #ffffff;
    }

    .navigation ul li a .icon {
        display: block;
        min-width: 60px;
        height: 60px;
        line-height: 60px;
        text-align: center;
    }

    .navigation ul li a .title {
        display: block;
        padding: 0 10px;
        height: 60px;
        line-height: 60px;
        text-align: start;
        white-space: nowrap;
    }

    .main {
        margin-left: 270px;
        transition: 0.5s;
    }

    .main.active {
        margin-left: 80px;
    }

    .topbar {
        width: 100%;
        height: 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        background: var(--white);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .toggle {
        font-size: 1.5rem;
        cursor: pointer;
    }

    .search {
        width: 400px;
        position: relative;
    }

    .search input {
        width: 100%;
        height: 40px;
        border-radius: 20px;
        padding: 0 20px;
        padding-left: 40px;
        font-size: 16px;
        border: 1px solid var(--black2);
        outline: none;
    }

    .search ion-icon {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 1.2rem;
    }

    .user {
        width: 40px;
        height: 40px;
        cursor: pointer;
    }

    .user img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    a {
        text-decoration: none;
    }

    .chart-size {
        width: 70% !important;
        height: 400px !important;
    }

    .academic-record,
    .quiz-progress {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 15px;
    }

    .toggle-buttons {
        margin-bottom: 20px;
    }

    .panel-default {
        margin: 20px 0;
        padding: 20px;
        width: 1000px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        display: none; /* Hide the table by default */
    }

    .panel-default h4 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .table thead {
        background-color: #343a40;
        color: #fff;
    }

    .table td,
    .table th {
        text-align: center;
        vertical-align: middle;
    }

    .table td[rowspan] {
        vertical-align: middle !important;
    }

    .hiddenRow {
        display: none;
    }

    @media (max-width: 768px) {
        .navigation {
            left: -300px;
        }

        .navigation.active {
            left: 0;
        }

        .main {
            margin-left: 0;
        }

        .main.active {
            margin-left: 300px;
        }
    }

    @media (max-width: 480px) {
        .navigation.active {
            width: 100%;
            left: 0;
        }

        .main.active {
            margin-left: 0;
        }

        .search {
            width: 100%;
            padding: 0 10px;
        }
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const overallCompletedQuizzes = @json($overallCompletedQuizzes);  // Overall completed quizzes
    const overallPendingQuizzes = @json($overallPendingQuizzes);      // Overall pending quizzes

    // Preparing data for each module's quiz progress
    const modules = @json($modules);

    const moduleLabels = modules.map(module => module.module_name);
    const completedData = modules.map(module => module.completed_count);
    const pendingData = modules.map(module => module.pending_count);
    
    let quizChart;
    const ctx = document.getElementById('quizChart').getContext('2d');

    function createOverallChart() {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                label: 'Overall Quiz Progress',
                data: [overallCompletedQuizzes, overallPendingQuizzes],
                backgroundColor: ['#4CAF50', '#F44336'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function createModulewiseChart() {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: moduleLabels,
            datasets: [
                {
                    label: 'Completed Quizzes',
                    data: completedData,
                    backgroundColor: '#4CAF50',
                    borderWidth: 1
                },
                {
                    label: 'Pending Quizzes',
                    data: pendingData,
                    backgroundColor: '#F44336',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}



    document.getElementById('overallBtn').addEventListener('click', function () {
        if (quizChart) {
            quizChart.destroy();
        }
        quizChart = createOverallChart();
    });

    document.getElementById('modulewiseBtn').addEventListener('click', function () {
        if (quizChart) {
            quizChart.destroy();
        }
        quizChart = createModulewiseChart();
    });

    document.getElementById('tableViewBtn').addEventListener('click', function () {
        const panel = document.querySelector('.panel-default');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });

    window.addEventListener('load', function () {
        quizChart = createOverallChart();
    });
</script>

@endsection
