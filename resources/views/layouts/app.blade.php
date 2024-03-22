<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss'])

    @livewireStyles
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand"
                   href="{{ route('home') }}"
                   wire:navigate>
                    <span class="fas fa-fw fa-computer"></span>
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbar-supported-content"
                        aria-controls="navbar-supported-content"
                        aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse"
                     id="navbar-supported-content">
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('login') }}">
                                        <span class="fas fa-fw fa-sign-in"></span>
                                        {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('register') }}">
                                        <span class="fas fa-fw fa-user-plus"></span>
                                        {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle p-0"
                                   id="navbar-dropdown"
                                   href="#"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false"
                                   v-pre>
                                    <livewire:components.avatar />
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end mt-2"
                                    aria-labelledby="navbar-dropdown">
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('resume') }}"
                                           wire:navigate>
                                            <span class="fas fa-fw fa-file-alt"></span>
                                            {{ __('Resume') }}
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li>
                                        <form action="{{ route('logout') }}"
                                              method="POST">
                                            @csrf
                                            <div class="d-grid gap-2 px-2">
                                                <button class="btn btn-danger text-nowrap"
                                                        type="submit">
                                                    <span class="fas fa-fw fa-sign-out"></span>
                                                    {{ __('Logout') }}
                                                </button>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')

            @persist('toast')
                <livewire:components.toast />
            @endpersist
        </main>
    </div>

    <script data-navigate-once>
        document.addEventListener('livewire:navigated', () => {
            const autofocus = document.querySelector('[autofocus]')

            if (autofocus) {
                autofocus.focus()
            }
        })
    </script>

    @vite(['resources/js/app.js'])

    @livewireScriptConfig

    @stack('scripts')
</body>

</html>
