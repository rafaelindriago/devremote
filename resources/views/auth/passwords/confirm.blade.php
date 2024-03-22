@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-4">
                <div class="card">
                    <h5 class="card-header">
                        <span class="fas fa-fw fa-lock"></span>
                        {{ __('Confirm Password') }}
                    </h5>

                    <div class="card-body">
                        <div class="alert alert-info"
                             role="alert">
                            <span class="fas fa-fw fa-info-circle"></span>
                            {{ __('Please confirm your password before continuing.') }}
                        </div>

                        <form method="POST"
                              action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label"
                                       for="password">{{ __('Password') }}</label>

                                <input class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       type="password"
                                       maxlength="32"
                                       autofocus>

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

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary"
                                        type="submit">
                                    <span class="fas fa-fw fa-lock"></span>
                                    {{ __('Confirm Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
