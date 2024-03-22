@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-4">
                <div class="card">
                    <h5 class="card-header">
                        <span class="fas fa-fw fa-key"></span>
                        {{ __('Reset Password') }}
                    </h5>

                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden"
                                   name="token"
                                   value="{{ request()->token }}">

                            <div class="mb-3">
                                <label class="form-label"
                                       for="email">{{ __('Email Address') }}</label>

                                <input class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       type="text"
                                       maxlength="128"
                                       value="{{ $email ?? old('email') }}"
                                       autofocus>

                                @error('email')
                                    <span class="invalid-feedback"
                                          role="alert">
                                        <strong>
                                            <span class="fas fa-fw fa-exclamation-circle"></span>
                                            {{ $message }}
                                        </strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label"
                                       for="password">{{ __('Password') }}</label>

                                <input class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       id="password"
                                       type="password"
                                       maxlength="32">

                                @error('password')
                                    <span class="invalid-feedback"
                                          role="alert">
                                        <strong>
                                            <span class="fas fa-fw fa-exclamation-circle"></span>
                                            {{ $message }}
                                        </strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label"
                                       for="password-confirm">{{ __('Confirm Password') }}</label>

                                <input class="form-control"
                                       id="password-confirm"
                                       name="password_confirmation"
                                       type="password"
                                       maxlength="32">
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary"
                                        type="submit">
                                    <span class="fas fa-fw fa-key"></span>
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
