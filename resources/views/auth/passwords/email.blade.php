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
                        @if (session('status'))
                            <div class="alert alert-success"
                                 role="alert">
                                <span class="fas fa-fw fa-check-circle"></span>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST"
                              action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label"
                                       for="email">{{ __('Email Address') }}</label>

                                <input class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       type="text"
                                       maxlength="128"
                                       value="{{ old('email') }}"
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

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary"
                                        type="submit">
                                    <span class="fas fa-fw fa-key"></span>
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
