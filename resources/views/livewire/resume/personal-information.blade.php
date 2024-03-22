<div class="card">
    <h5 class="card-header">
        <span class="fas fa-fw fa-id-card"></span>
        {{ __("Personal Information") }}
    </h5>

    <div class="card-body">
        <form method="POST"
              wire:submit="save">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"
                               for="name">{{ __("Name") }}</label>

                        <input class="form-control @error("name") is-invalid @enderror"
                               id="name"
                               type="text"
                               maxlength="50"
                               placeholder="{{ __("John") }}"
                               autofocus
                               wire:model="name">

                        @error("name")
                            <div class="invalid-feedback"
                                 role="alert">
                                <strong>
                                    <span class="fas fa-fw fa-exclamation-circle"></span>
                                    {{ $message }}
                                </strong>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"
                               for="last-name">{{ __("Last Name") }}</label>

                        <input class="form-control @error("lastName") is-invalid @enderror"
                               id="last-name"
                               type="text"
                               maxlength="50"
                               placeholder="{{ __("Doe") }}"
                               wire:model="lastName">

                        @error("lastName")
                            <div class="invalid-feedback"
                                 role="alert">
                                <strong>
                                    <span class="fas fa-fw fa-exclamation-circle"></span>
                                    {{ $message }}
                                </strong>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label"
                               for="birth-at">{{ __("Birth Date") }}</label>

                        <input class="form-control @error("birthAt") is-invalid @enderror"
                               id="birth-at"
                               type="text"
                               maxlength="10"
                               placeholder="{{ __("DD-MM-YYYY") }}"
                               inputmode="numeric"
                               wire:model="birthAt"
                               x-data
                               x-mask="99-99-9999">

                        @error("birthAt")
                            <div class="invalid-feedback"
                                 role="alert">
                                <strong>
                                    <span class="fas fa-fw fa-exclamation-circle"></span>
                                    {{ $message }}
                                </strong>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"
                               for="email">{{ __("Email Address") }}</label>

                        <input class="form-control @error("email") is-invalid @enderror"
                               id="email"
                               type="text"
                               maxlength="100"
                               placeholder="{{ __("example@domain.com") }}"
                               wire:model="email">

                        @error("email")
                            <div class="invalid-feedback"
                                 role="alert">
                                <strong>
                                    <span class="fas fa-fw fa-exclamation-circle"></span>
                                    {{ $message }}
                                </strong>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label"
                               for="phone">{{ __("Phone Number") }}</label>

                        <input class="form-control @error("phone") is-invalid @enderror"
                               id="phone"
                               type="text"
                               maxlength="15"
                               placeholder="+000 0000000000"
                               inputmode="numeric"
                               wire:model="phone"
                               x-data="{
                                   mask(input) {
                                       if (input.length == 1) { return '+' }
                                       for (let count = 1; count <= 3; count++) {
                                           if ((new RegExp(`^\\+[\\d]\{${count}\}[\\s]`)).test(input)) {
                                               return (new String('+')).padEnd(count + 1, '9')
                                                   .concat(' ').padEnd(count + 12, '9')
                                           }
                                       }
                                       return '+999 999999999'
                                   }
                               }"
                               x-mask:dynamic="$data.mask">

                        @error("phone")
                            <div class="invalid-feedback"
                                 role="alert">
                                <strong>
                                    <span class="fas fa-fw fa-exclamation-circle"></span>
                                    {{ $message }}
                                </strong>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"
                               for="country-code">{{ __("Country") }}</label>

                        <div class="dropdown"
                             x-data="{
                                 value: $wire.entangle('countryCode'),
                                 selectedIndex: -1,
                                 options: @js($countries),
                                 init() {
                                     $watch('value', () => $data.selectByValue())
                                     $data.selectByValue()
                                 },
                                 selectByValue() {
                                     if ($data.value.length == 0) { $data.selectedIndex = -1 } else {
                                         for (const index in $data.options) {
                                             if ($data.options[index].code == $data.value) {
                                                 $data.selectedIndex = index
                                                 break
                                             }
                                         }
                                     }
                                 },
                                 focusToActive() {
                                     const active = $root.querySelector('.dropdown-menu .active')
                                     if (active) { active.focus() }
                                 },
                                 selectByKeyboard(initial) {
                                     for (const index in $data.options) {
                                         if ($data.options[index].name.startsWith(initial.toUpperCase())) {
                                             $data.value = $data.options[index].code
                                             $data.selectedIndex = index
                                             $nextTick(() => $data.focusToActive())
                                             break
                                         }
                                     }
                                 }
                             }"
                             x-on:shown-bs-dropdown.dot="focusToActive()"
                             x-on:keydown="selectByKeyboard($event.key)"
                             x-on:hidden-bs-dropdown.dot="$refs.input.focus()">

                            <button class="form-select text-start @error("countryCode") is-invalid @enderror"
                                    id="country-code"
                                    data-bs-toggle="dropdown"
                                    type="button"
                                    x-ref="input">
                                <span class="text-muted"
                                      x-show="selectedIndex == -1">
                                    {{ __("Choose an Option") }}
                                </span>

                                <template x-if="selectedIndex >= 0">
                                    <span>
                                        <img alt="{{ __("Flag") }}"
                                             width="25"
                                             height="15"
                                             x-bind:src="options[selectedIndex].flag">
                                        <span class="ms-1"
                                              x-text="options[selectedIndex].name"></span>
                                    </span>
                                </template>
                            </button>

                            @error("countryCode")
                                <div class="invalid-feedback"
                                     role="alert">
                                    <strong>
                                        <span class="fas fa-fw fa-exclamation-circle"></span>
                                        {{ $message }}
                                    </strong>
                                </div>
                            @enderror

                            <ul class="dropdown-menu w-100 overflow-y-scroll"
                                style="max-height: 250px;">
                                <li>
                                    <a class="dropdown-item"
                                       href="#"
                                       x-on:click.prevent="value = ''">
                                        {{ __("None") }}
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <template x-for="(option, index) in options">
                                    <li>
                                        <a class="dropdown-item"
                                           href="#"
                                           x-bind:id="option.code"
                                           x-bind:class="{ 'active': value == option.code }"
                                           x-on:click.prevent="value = option.code">
                                            <img alt="{{ __("Flag") }}"
                                                 width="25"
                                                 height="15"
                                                 x-bind:src="option.flag">
                                            <span class="ms-1"
                                                  x-text="option.name"></span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-0">
                <button class="btn btn-primary"
                        type="submit">
                    <span class="fas fa-fw fa-save"
                          wire:loading.remove
                          wire:target="save"></span>
                    <span class="fas fa-fw fa-circle-notch fa-spin"
                          wire:loading
                          wire:target="save"></span>
                    {{ __("Save") }}
                </button>

                <button class="btn btn-secondary"
                        type="button"
                        aria-label="{{ __("Reset") }}"
                        wire:click="resetForm"
                        wire:loading.attr="disabled"
                        wire:target="resetForm">
                    <span class="fas fa-fw fa-undo-alt"
                          wire:loading.remove
                          wire:target="resetForm"></span>
                    <span class="fas fa-fw fa-circle-notch fa-spin"
                          wire:loading
                          wire:target="resetForm"></span>
                </button>
            </div>
        </form>
    </div>
</div>
