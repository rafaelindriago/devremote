@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-4">
                <div class="card">
                    <h5 class="card-header">
                        <span class="fas fa-fw fa-user-plus"></span>
                        {{ __('Register') }}
                    </h5>

                    <div class="card-body">
                        <form method="POST"
                              action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label"
                                       for="name">{{ __('Name') }}</label>

                                <input class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       type="text"
                                       maxlength="64"
                                       value="{{ old('name') }}"
                                       autofocus>

                                @error('name')
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
                                <label class="form-label"
                                       for="email">{{ __('Email Address') }}</label>

                                <input class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       type="text"
                                       maxlength="128"
                                       value="{{ old('email') }}">

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
                                <label class="form-label"
                                       for="password">{{ __('Password') }}</label>

                                <input class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
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
                                    <span class="fas fa-fw fa-user-plus"></span>
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
