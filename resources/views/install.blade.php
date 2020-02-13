@extends('layouts.app')

@section('title', 'Install')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card h-auto">
                <div class="card-header text-center">
                    <h3>Install</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('install') }}" method="post">
                        @csrf
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                            </div>
                            <label for="company" class="sr-only"></label>
                            <input id="company" type="text" class="form-control @error('company') is-invalid @enderror" name="company" placeholder="Company Name"
                                   value="{{ old('company') }}" required autocomplete="company" autofocus>

                            @error('company')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <label for="admin" class="sr-only"></label>
                            <input id="admin" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Admin Name"
                                   value="{{ old('name') }}" required autocomplete="Admin">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <label for="email" class="sr-only"></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Admin E-Mail"
                                   value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <label for="password" class="sr-only"></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                   name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <label for="password_confirmation" class="sr-only"></label>
                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password"
                                   name="password_confirmation" required autocomplete="current-password">
                        </div>


                        <div class="form-group text-center">
                            <input type="submit" value="Install" class="btn float-right login_btn mx-auto">
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link forgot-password-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
