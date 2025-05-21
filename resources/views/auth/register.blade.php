@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <!-- resources/views/auth/register.blade.php -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required>
                            @error('username')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required>
                            @error('password')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation">Confirm Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required>
                        </div>

                        <button type="submit">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
