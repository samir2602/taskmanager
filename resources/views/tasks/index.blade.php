@extends('layouts.app')

@section('title', 'My Tasks - TaskManager')

@section('content')
    <div class="container py-4">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="fw-bold">📋 My Tasks</h1>
            </div>
            <div class="col-md-4 text-end">
                <a href="/tasks/create" class="btn btn-primary">+ New Task</a>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="fw-bold text-primary">{{ $totalTasks }}</h3>
                        <small class="text-muted">Total Tasks</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="fw-bold text-warning">{{ $pendingTasks }}</h3>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="fw-bold text-info">{{ $inProgressTasks }}</h3>
                        <small class="text-muted">In Progress</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="fw-bold text-success">{{ $completedTasks }}</h3>
                        <small class="text-muted">Completed</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        @if($totalTasks > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold">Overall Progress</span>
                        <span class="text-muted">{{ $completedTasks }}/{{ $totalTasks }} tasks completed</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div
                            class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-primary' }}"
                            style="width: {{ $percentage }}%"
                        >
                            {{ $percentage }}%
                        </div>
                    </div>
                    @if($percentage == 100)
                        <p class="text-success text-center mt-2 mb-0 fw-semibold">
                            🎉 All tasks completed! Great job!
                        </p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Filters --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="/tasks" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="{{ $search }}"/>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="priority" class="form-select">
                            <option value="">All Priority</option>
                            <option value="high" {{ $priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ $priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ $priority == 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tasks List --}}
        <div class="row">
            @forelse($tasks as $task)
                <div class="col-md-6 mb-3">
                    <div class="card task-card shadow-sm priority-{{ $task->priority }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title {{ $task->status == 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $task->title }}
                                </h5>
                                <span class="badge {{ $task->priority == 'high' ? 'bg-danger' : ($task->priority == 'medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            @if($task->description)
                                <p class="card-text text-muted small">{{ Str::limit($task->description, 80) }}</p>
                            @endif

                            <div class="d-flex gap-2 mb-2">
                                <span class="badge {{ $task->status == 'completed' ? 'bg-success' : ($task->status == 'in_progress' ? 'bg-info' : 'bg-secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                @if($task->due_date)                                    
                                    <span class="badge {{ $task->due_date->isPast() && $task->status != 'completed' ? 'bg-danger' : ($task->due_date->isToday() ? 'bg-warning text-dark' : 'bg-light text-dark') }}">
                                        {{ $task->due_date->isPast() && $task->status != 'completed' ? '⚠️ Overdue' : '📅' }} 
                                        {{ $task->due_date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                <form method="post" action="/tasks/{{ $task->id }}/complate">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $task->status === 'completed' ? 'btn-success' : 'btn-outline-success' }}">
                                    {{ $task->status === 'completed' ? '✅ Done' : '○ Complete'}}
                                </button>
                                </form>
                                <a href="/tasks/{{ $task->id }}/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="/tasks/{{ $task->id }}" onsubmit="return confirc('Are you sure you want to delete this task?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No tasks found! <a href="/tasks/create">Create your first task!</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection