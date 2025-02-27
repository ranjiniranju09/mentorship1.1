<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Chapters</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .course-header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .search-filter {
            margin: 20px 0;
        }
        .chapter-list {
            margin-top: 20px;
        }
        .chapter-item {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 20px;
            transition: transform 0.2s;
        }
        .chapter-item:hover {
            transform: translateY(-5px);
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .search-filter {
                flex-direction: column;
            }
            .search-filter .col-md-3,
            .search-filter .col-md-6 {
                margin-bottom: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="course-header">
        <h1>Course Title</h1>
        <p>Subtitle or description of the course.</p>
    </div>

    <div class="search-filter row">
        <div class="col-md-6">
            <input type="text" class="form-control" id="search-bar" placeholder="Search chapters...">
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filter-options">
                <option value="">Filter by...</option>
                <option value="completed">Completed</option>
                <option value="in-progress">In Progress</option>
                <option value="not-started">Not Started</option>
            </select>
        </div>
        <div class="col-md-3 text-right">
            <button class="btn btn-primary" onclick="searchChapters()">Search</button>
        </div>
    </div>

    <div class="chapter-list">
        <div class="chapter-item" data-status="completed">
            <h3>Chapter 1: Introduction</h3>
            <p>Description of Chapter 1.</p>
        </div>
        <div class="chapter-item" data-status="in-progress">
            <h3>Chapter 2: Basics</h3>
            <p>Description of Chapter 2.</p>
        </div>
        <div class="chapter-item" data-status="not-started">
            <h3>Chapter 3: Advanced Topics</h3>
            <p>Description of Chapter 3.</p>
        </div>
        <!-- Add more chapters as needed -->
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
</div>

<script>
    function searchChapters() {
        const searchTerm = document.getElementById('search-bar').value.toLowerCase();
        const filterOption = document.getElementById('filter-options').value;
        const chapters = document.querySelectorAll('.chapter-item');

        chapters.forEach(chapter => {
            const title = chapter.querySelector('h3').innerText.toLowerCase();
            const description = chapter.querySelector('p').innerText.toLowerCase();
            const status = chapter.getAttribute('data-status');
            let matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            let matchesFilter = !filterOption || status === filterOption;

            if (matchesSearch && matchesFilter) {
                chapter.style.display = '';
            } else {
                chapter.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
