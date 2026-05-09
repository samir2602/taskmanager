<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskManager')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-color: #f0f2f5; }
        .task-card { transition: transform 0.2s; }
        .task-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand fw-bold" href="/">✅ TaskManager</a>
        <div class="navbar-nav ms-auto d-flex flex-row gap-3 align-items-center">
            @auth
                <a class="nav-link text-white" href="/tasks">My Tasks</a>
                <a class="btn btn-primary btn-sm" href="/tasks/create">+ New Task</a>
                <form method="POST" action="/logout" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @endauth

            @guest
                <a class="nav-link text-white" href="/login">Login</a>
                <a class="btn btn-primary btn-sm" href="/register">Register</a>
            @endguest
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

    <footer class="text-center py-4 mt-5 border-top text-muted">
        <small>TaskManager &copy; {{ date('Y') }} — Built with Laravel ❤️</small>
    </footer>
</body>
</html>