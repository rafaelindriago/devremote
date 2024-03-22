<div class="container d-flex justify-content-center justify-content-md-end w-100"
     x-on:hide-bs-toast.dot="$wire.call('dismiss', $event.target.dataset.key)">

    <div class="toast-container position-fixed bottom-0 mb-3"
         @if (count($toasts) > 0) wire:poll.5000ms @endif>

        @foreach ($toasts as $key => $toast)
            <div class="toast"
                 data-key="{{ $key }}"
                 role="alert"
                 aria-live="assertive"
                 aria-atomic="true"
                 wire:ignore.self
                 wire:key="toast-{{ $key }}">

                <div class="toast-header">
                    <strong class="me-auto">
                        @switch(data_get($toast, 'type'))
                            @case("success")
                                <span class="fas fa-fw fa-check-circle text-success"></span>
                                {{ __("Success") }}
                            @break

                            @case("error")
                                <span class="fas fa-fw fa-times-circle text-danger"></span>
                                {{ __("Error") }}
                            @break

                            @case("warning")
                                <span class="fas fa-fw fa-exclamation-circle text-warning"></span>
                                {{ __("Warning") }}
                            @break

                            @case("info")
                                <span class="fas fa-fw fa-info-circle text-info"></span>
                                {{ __("Notice") }}
                            @break

                            @default
                                <span class="fas fa-fw fa-info-circle text-info"></span>
                                {{ __("Notice") }}
                            @break
                        @endswitch
                    </strong>

                    <small class="text-body-secondary">
                        {{ $diffForHumans(data_get($toast, "timestamp")) }}
                    </small>

                    <button class="btn-close"
                            data-bs-dismiss="toast"
                            type="button"
                            aria-label="{{ __("Close") }}"></button>
                </div>

                <div class="toast-body">
                    {{ data_get($toast, "message") }}
                </div>
            </div>
        @endforeach
    </div>
</div>

@script
    <script>
        Livewire.hook('morph.added', ({
            el
        }) => {
            if (el.classList.contains('toast')) {
                Toast.getOrCreateInstance(el, {
                        delay: 60000
                    })
                    .show()
            }
        })
    </script>
@endscript
