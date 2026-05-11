@extends('layouts.app')

@section('title', 'Dashboard - TaskManager')

@section('content')
    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm bg-dark text-white border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                            <p class="mb-0 opacity-75">Here's your task summary for today</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="/tasks/create" class="btn btn-primary">+ New Task</a>
                            <a href="/tasks" class="btn btn-outline-light ms-2">View All</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h2 class="fw-bold text-primary">{{ $totalTasks }}</h2>
                    <p class="text-muted mb-0">Total Tasks</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h2 class="fw-bold text-warning">{{ $pendingTasks }}</h2>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h2 class="fw-bold text-info">{{ $inProgressTasks }}</h2>
                    <p class="text-muted mb-0">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h2 class="fw-bold text-success">{{ $completedTasks }}</h2>
                    <p class="text-muted mb-0">Completed</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress & Overdue Row --}}
    <div class="row mb-4">
        <div class="col-md-8 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📊 Overall Progress</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ $completedTasks }}/{{ $totalTasks }} tasks completed</span>
                        <span class="fw-semibold">{{ $percentage }}%</span>
                    </div>
                    <div class="progress mb-2" style="height: 15px;">
                        <div
                            class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-primary' }}"
                            style="width: {{ $percentage }}%"
                        ></div>
                    </div>
                    @if($percentage == 100)
                        <p class="text-success text-center mt-2 mb-0 fw-semibold">
                            🎉 All tasks completed! Amazing work!
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 {{ $overdueTasks > 0 ? 'border-danger' : '' }}">
                <div class="card-body text-center">
                    <h2 class="fw-bold {{ $overdueTasks > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $overdueTasks }}
                    </h2>
                    <p class="text-muted mb-0">Overdue Tasks</p>
                    @if($overdueTasks > 0)
                        <a href="/tasks?status=pending" class="btn btn-danger btn-sm mt-2">
                            View Overdue
                        </a>
                    @else
                        <p class="text-success mt-2 mb-0">✅ No overdue tasks!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Urgent Tasks --}}
    @if($urgentTasks->count() > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">🔥 High Priority Tasks</h5>
            </div>
            <div class="card-body">
                @foreach($urgentTasks as $task)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="fw-semibold">{{ $task->title }}</span>
                            @if($task->due_date)
                                <small class="text-muted ms-2">
                                    📅 {{ $task->due_date->format('d M Y') }}
                                </small>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-danger">High</span>
                            <span class="badge {{ $task->status == 'in_progress' ? 'bg-info' : 'bg-secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                            <a href="/tasks/{{ $task->id }}/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </div>
                    </div>
                    @if(!$loop->last)<hr class="my-2">@endif
                @endforeach
            </div>
        </div>
    @endif

@endsection