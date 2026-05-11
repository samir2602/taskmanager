@extends('layouts.app')

@section('title', 'Register - TaskManager')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <h2 class="fw-bold">✅ TaskManager</h2>
                <p class="text-muted">Create an account to get started</p>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">📝 Create Account</h4>

                    <form method="POST" action="/register">
                        @csrf

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your name" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"  value="{{ old('email') }}" placeholder="Enter your email" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required />
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Create Account
                            </button>
                        </div>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        Already have an account?
                        <a href="/login">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection