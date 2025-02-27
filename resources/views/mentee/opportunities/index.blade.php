@extends('layouts.new_mentee')
@section('content')
<style type="text/css">
    body {
        font-family: "Ubuntu", sans-serif;
        background-color: #f4f7f6;
    }
    /* .sidebar {
        position: fixed;
        width: 250px;
        height: 100%;
        background: linear-gradient(to bottom, #000000, #4ca1af);
        transition: 0.5s;
        overflow: hidden;
    }
    .sidebar a {
        position: relative;
        display: block;
        width: 100%;
        text-decoration: none;
        color: white;
        padding: 15px 10px;
        margin-bottom: 10px;
        align-content: center;
        transition: background-color 0.3s, color 0.3s;
    }
    .sidebar a:hover {
        color: #cc66ff;
        background-color: rgba(255, 255, 255, 0.1);
    }
    .sidebar .hovered a {
        color: blue;
    }
    .sidebar a i {
        margin-right: 15px;
    } */
    .content {
        margin-left: 250px;
        padding: 20px;
        transition: margin-left 0.5s;
    }
    .topbar, .dashboard-header-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .topbar {
        width: 100%;
        height: 60px;
        padding: 0 10px;
    }
    .toggle {
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2.5rem;
        cursor: pointer;
    }

    .greeting {
        font-size: 24px;
        color: green;
        font-weight: bold;
    }
    .jobs {
        display: flex;
        color: #0400ff;
        justify-content: space-between;
    }
    .jobs h2 {
        margin: 0;
        flex: 1;
    }
    .search {
        width: 400px;
        margin: 0 10px;
        position: relative;
    }
    .search label {
        width: 100%;
    }
    .search label input {
        width: 100%;
        height: 40px;
        border-radius: 40px;
        padding: 5px 20px;
        padding-left: 35px;
        font-size: 18px;
        outline: none;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .search label ion-icon {
        position: absolute;
        top: 50%;
        left: 10px;
        font-size: 1.2rem;
        transform: translateY(-50%);
    }
    .filter-btns {
        text-align: center;
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .filter-btn {
        padding: 10px 10px;
        background-color: white;
        color: black;
        border: none;
        border-radius: 30%;
        width: 10%;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .filter-btn:hover {
        background-color: #2196f3;
    }
    .filter-btn.active {
        background-color: #2196f3;
    }
    .opportunity-list {
        list-style: none;
        padding: 0;
    }
    .opportunity-item {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s;
    }
    .opportunity-item:hover {
        background-color: #f0f0f0;
    }
    .opportunity-info {
        display: flex;
        align-items: center;
    }
    .opportunity-icon {
        font-size: 36px;
        margin-right: 20px;
    }
    .opportunity-action {
        text-align: right;
    }
    @media (max-width: 768px) {
        .sidebar {
            width: 250px;
            height: 100%;
            position: fixed;
            left: -250px;
            z-index: 1000;
        }
        .sidebar.active {
            left: 0;
        }
        .content {
            margin-left: 0;
        }
        .toggle {
            display: block;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="jobs">
            <h2>Opportunities for you</h2>
            <div class="search">
                <label>
                    <input type="text" placeholder="Search here">
                </label>
            </div>
            <hr>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="filter-btns rounded">
            <button class="filter-btn active" onclick="filterOpportunities('all')">All</button>
            <button class="filter-btn" onclick="filterOpportunities('jobs')">Jobs</button>
            <button class="filter-btn" onclick="filterOpportunities('Internships')">Internships</button>
            <button class="filter-btn" onclick="filterOpportunities('Fellowships')">Fellowships</button>
            <!-- <button class="filter-btn" onclick="filterOpportunities('competition')">Competitions</button> -->
            <button class="filter-btn" onclick="filterOpportunities('others')">Others</button>
        </div>
        <hr>
        <ul class="opportunity-list">
            @foreach ($opportunity as $op)
                <li class="opportunity-item {{ strtolower($op->opportunity_type) }}">
                    <div class="opportunity-info">
                        <i class="fa-brands fa-js opportunity-icon" style="color: #FFD43B;"></i>
                        <h5>{{ $op->title }}</h5>
                    </div>
                    <div class="opportunity-action">
                        <a href="{{ $op->link }}" class="btn btn-primary" target="_blank" >Explore</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    function filterOpportunities(type) {
        const items = document.querySelectorAll('.opportunity-item');
        items.forEach(item => {
            if (type === 'all') {
                item.style.display = 'flex';
            } else if (item.classList.contains(type)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`.filter-btn[onclick="filterOpportunities('${type}')"]`).classList.add('active');
    }
</script>
<script>
    $(document).ready(function() {
        // Toggle sidebar on hamburger menu click
        $('#toggle-btn').click(function() {
            $('#sidebar').toggleClass('active');
        });
    });
</script>
@endsection
