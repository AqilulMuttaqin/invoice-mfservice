@extends('auth.app')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control form-control-lg" type="email" name="email" id="email"
                placeholder="Enter your email" required />
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control form-control-lg" type="password" name="password" id="password"
                placeholder="Enter your password" required />
        </div>
        <div>
            <div class="d-flex align-items-center justify-content-end mb-2">
                <a href="{{ route('password.request') }}">Forgot Password ?</a>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-lg btn-primary">Sign in</button>
        </div>
    </form>
@endsection
