<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function dashboard()
    {
        $allTasks = Task::where('user_id', auth()->id())->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'completed')->count();
        $pendingTasks = $allTasks->where('status', 'pending')->count();
        $inProgressTasks = $allTasks->where('status', 'in_progress')->count();
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $overdueTasks = $allTasks->filter(function($task){
            return $task->due_date && $task->due_date->isPast() && $task->status != 'completed';
        })->count();

        $urgentTasks = Task::where('user_id', auth()->id())
        ->where('status', '!=', 'completed')
        ->where('priority', 'high')
        ->where('due_date', 'asc')        
        ->take(5)
        ->get();

        return view('tasks.dashboard', [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'percentage' => $percentage,
            'overdueTasks' => $overdueTasks,
            'urgentTasks' => $urgentTasks,
        ]);
    }

    public function index(Request $request)
    {
        $status = $request->status;
        $priority = $request->priority;
        $search = $request->search;

        $allTasks = Task::where('user_id', auth()->id())->get();
        $totalTasks = $allTasks->count();
        $completedTasks = $allTasks->where('status', 'completed')->count();
        $pendingTasks = $allTasks->where('status', 'pending')->count();
        $inProgressTasks = $allTasks->where('status', 'in_progress')->count();
        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $tasks = Task::where('user_id', auth()->id())
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($priority, function($query) use ($priority) {
                $query->where('priority', $priority);
            })
            ->when($search, function($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%' );
            })
            ->orderBy('due_date', 'asc')
            ->get();
        
        return view('tasks.index', [
            'tasks' => $tasks,
            'status' => $status,
            'search' => $search,
            'priority' => $priority,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'percentage' => $percentage,
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'user_id' => auth()->id(),
        ]);

        return redirect('/tasks')->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        return redirect('/tasks')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect('/tasks')->with('success', 'Task deleted successfully!');
    }

    public function complate(Task $task){
        $this->authorize('complete', $task);

        $newStatus = $task->status === 'completed' ? 'pending' : 'completed';


        $task->update([
            'status' => $newStatus
        ]);

        return redirect('/tasks')->with('success', $task->status === 'completed' ? 'Task marked as pending!' : 'Task completed! 🎉');
    }
}