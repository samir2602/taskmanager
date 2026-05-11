@extends('layouts.app')

@section('title', 'Login - TaskManager')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold">✅ TaskManager</h2>
                <p class="text-muted">Login to manage your tasks</p>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">🔐 Login</h4>

                    <form method="POST" action="/login">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter your email" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        Don't have an account?
                        <a href="/register">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection