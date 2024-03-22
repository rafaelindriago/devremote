@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-4">
                <div class="card">
                    <h5 class="card-header">
                        <span class="fas fa-fw fa-sign-in"></span>
                        {{ __('Login') }}
                    </h5>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success"
                                 role="alert">
                                <span class="fas fa-fw fa-check-circle"></span>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST"
                              action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email"
                                       class="form-label">{{ __('Email Address') }}</label>

                                <input class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       type="text"
                                       maxlength="128"
                                       value="{{ old('email') }}"
                                       autofocus>

                                @error('email')
                                    <div class="invalid-feedback"
                                         role="alert">
                                        <strong>
                                            <span class="fas fa-fw fa-exclamation-circle"></span>
                                            {{ $message }}
                                        </strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                       class="form-label">{{ __('Password') }}</label>

                                <input class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       id="password"
                                       type="password"
                                       maxlength="32">

                                @error('password')
                                    <div class="invalid-feedback"
                                         role="alert">
                                        <strong>
                                            <span class="fas fa-fw fa-exclamation-circle"></span>
                                            {{ $message }}
                                        </strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           id="remember"
                                           name="remember"
                                           type="checkbox"
                                           @checked(old('remember'))>

                                    <label class="form-check-label"
                                           for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-block btn-primary"
                                            type="submit">
                                        <span class="fas fa-fw fa-sign-in"></span>
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="mb-0 text-center">
                                    <a class="btn btn-link"
                                       href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            @endif
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
