<div class="card">
    <h5 class="card-header">
        <span class="fas fa-fw fa-book"></span>
        {{ __("Skills") }}
    </h5>

    <div class="card-body">
        <form method="POST"
              wire:submit="saveAll">
            <div class="mb-3">
                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#skills-skill-form"
                        type="button"
                        @disabled(count($skills) >= 8)>
                    <span class="fas fa-fw fa-pen"></span>
                    {{ __("Add") }}
                </button>
            </div>

            @empty($skills)
                <div class="alert alert-info"
                     role="alert">
                    <span class="fas fa-fw fa-info-circle"></span>
                    {{ __("Complete your profile by adding your skills here.") }}
                </div>
            @endempty

            @foreach ($skills as $skillKey => $skill)
                <div class="card mb-3"
                     wire:key="skill-{{ $skillKey }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="language-{{ $skillKey }}">
                                        {{ __("Language") }}
                                    </label>

                                    <div class="form-control @error("skills.{$skillKey}.languageKey") is-invalid @enderror"
                                         id="language-{{ $skillKey }}"
                                         tabindex="0"
                                         contenteditable="false">
                                        <span class="{{ $skill["languageIcon"] }}"></span>
                                        {{ $skill["languageName"] }}
                                    </div>

                                    @error("skills.{$skillKey}.languageKey")
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

                            <div class="col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="level-{{ $skillKey }}">
                                        {{ __("Level") }}
                                    </label>

                                    <input class="form-control @error("skills.{$skillKey}.level") is-invalid @enderror"
                                           id="level-{{ $skillKey }}"
                                           type="text"
                                           readonly
                                           wire:model="skills.{{ $skillKey }}.levelTranslated">

                                    @error("skills.{$skillKey}.level")
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

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="description-{{ $skillKey }}">
                                        {{ __("Description") }}
                                    </label>

                                    <textarea class="form-control @error("skills.{$skillKey}.description") is-invalid @enderror"
                                              id="description-{{ $skillKey }}"
                                              readonly
                                              wire:model="skills.{{ $skillKey }}.description"></textarea>

                                    @error("skills.{$skillKey}.description")
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

                        <button class="btn btn-primary"
                                type="button"
                                wire:click="edit({{ $skillKey }})"
                                wire:loading.attr="disabled"
                                wire:target="edit({{ $skillKey }})">
                            <span class="fas fa-fw fa-edit"
                                  wire:loading.remove
                                  wire:target="edit({{ $skillKey }})"></span>
                            <span class="fas fa-fw fa-circle-notch fa-spin"
                                  wire:loading
                                  wire:target="edit({{ $skillKey }})"></span>
                            {{ __("Edit") }}
                        </button>

                        <button class="btn btn-danger"
                                data-key="{{ $skillKey }}"
                                data-bs-toggle="modal"
                                data-bs-target="#skill-delete-confirmation"
                                type="button"
                                aria-label="{{ __("Delete") }}">
                            <span class="fas fa-fw fa-trash"></span>
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="row">
                <div class="col">
                    <div class="mb-0">
                        <button class="btn btn-primary"
                                type="submit">
                            <span class="fas fa-fw fa-save"
                                  wire:loading.remove
                                  wire:target="saveAll"></span>
                            <span class="fas fa-fw fa-circle-notch fa-spin"
                                  wire:loading
                                  wire:target="saveAll"></span>
                            {{ __("Save") }}
                        </button>

                        <button class="btn btn-secondary"
                                type="button"
                                aria-label="{{ __("Reset") }}"
                                wire:click="resetFormAll"
                                wire:loading.attr="disabled"
                                wire:target="resetFormAll">
                            <span class="fas fa-fw fa-undo-alt"
                                  wire:loading.remove
                                  wire:target="resetFormAll"></span>
                            <span class="fas fa-fw fa-circle-notch fa-spin"
                                  wire:loading
                                  wire:target="resetFormAll"></span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade"
         id="skills-skill-form"
         aria-labelledby="skills-skill-form-title"
         tabindex="-1"
         wire:ignore.self
         x-data="{ modal: Modal.getOrCreateInstance($root) }"
         x-on:livewire-skills-skill-editing.window="modal.show()"
         x-on:livewire-skills-skill-edited.window="modal.hide()"
         x-on:shown-bs-modal.dot="$root.querySelector('#language-key').focus()"
         x-on:hidden-bs-modal.dot="$wire.call('resetForm', true)">

        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="skills-skill-form-title">
                        <span class="fas fa-fw fa-book"></span>
                        {{ __("Add Skill") }}
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal"
                            type="button"
                            aria-label="{{ __("Close") }}"
                            wire:loading.attr="disabled"
                            wire:target="save,resetForm"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label class="form-label"
                                       for="language-key">
                                    {{ __("Language") }}
                                </label>

                                <div class="dropdown"
                                     x-data="{
                                         value: $wire.entangle('languageKey'),
                                         selectedIndex: -1,
                                         filter: '',
                                         options: @js($languages),
                                         get filteredOptions() {
                                             if ($data.filter.length >= 1) {
                                                 return $data.options.filter((option) => {
                                                     return option.name.toLowerCase()
                                                         .indexOf($data.filter.toLowerCase().trim()) >= 0
                                                 })
                                             } else { return [] }
                                         },
                                         init() {
                                             $watch('value', () => $data.selectByValue())
                                             $data.selectByValue()
                                         },
                                         selectByValue() {
                                             if ($data.value.length == 0) { $data.selectedIndex = -1 } else {
                                                 for (const index in $data.options) {
                                                     if ($data.options[index].id == $data.value) {
                                                         $data.selectedIndex = index
                                                         break
                                                     }
                                                 }
                                             }
                                         },
                                         focusToFirst() {
                                             const first = $root.querySelector('.dropdown-item')
                                             if (first) { first.focus() }
                                         }
                                     }"
                                     x-on:shown-bs-dropdown.dot="$refs.filter.focus()"
                                     x-on:hidden-bs-dropdown.dot="$refs.input.focus()">

                                    <button class="form-select text-start @error("languageKey") is-invalid @enderror"
                                            id="language-key"
                                            data-bs-toggle="dropdown"
                                            type="button"
                                            @disabled($key)
                                            x-ref="input">
                                        <span class="text-muted"
                                              x-show="selectedIndex == -1">
                                            {{ __("Choose an Option") }}
                                        </span>

                                        <span x-show="selectedIndex >= 0">
                                            <span style="font-size: 16px;"
                                                  x-bind:class="selectedIndex >= 0 && options[selectedIndex].class"></span>
                                            <span x-text="selectedIndex >= 0 && options[selectedIndex].name"></span>
                                        </span>
                                    </button>

                                    @error("languageKey")
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
                                            <div class="dropdown-header">
                                                <input class="form-control form-control-sm"
                                                       type="text"
                                                       maxlength="128"
                                                       placeholder="{{ __("Type to Filter Options") }}"
                                                       x-ref="filter"
                                                       x-model="filter"
                                                       x-on:focusin="$event.target.select()"
                                                       x-on:keydown.down.prevent="focusToFirst()">
                                            </div>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <a class="dropdown-item text-muted"
                                               href="#"
                                               x-on:click.prevent="value = ''">
                                                {{ __("None") }}
                                            </a>
                                        </li>

                                        <template x-for="(option, index) in filteredOptions">
                                            <li>
                                                <a class="dropdown-item"
                                                   href="#"
                                                   x-bind:class="{ 'active': value == option.id }"
                                                   x-on:click.prevent="value = option.id">
                                                    <span style="font-size: 16px;"
                                                          x-bind:class="option.class"></span>
                                                    <span x-text="option.name"></span>
                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ __("Level") }}
                                </label>

                                @foreach (["Beginner", "Intermediate", "Expert"] as $level)
                                    <div class="form-check">
                                        <input class="form-check-input @error("level") is-invalid @enderror"
                                               id="level-{{ strtolower($level) }}"
                                               type="radio"
                                               value="{{ $level }}"
                                               wire:model="level">
                                        <label class="form-check-label"
                                               for="level-{{ strtolower($level) }}">
                                            {{ __($level) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                       for="description">
                                    {{ __("Description") }}
                                </label>

                                <textarea class="form-control @error("description") is-invalid @enderror"
                                          id="description"
                                          maxlength="256"
                                          wire:model="description"></textarea>

                                @error("description")
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
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary"
                            type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            wire:target="save">
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
            </div>
        </div>
    </div>

    <div class="modal fade"
         id="skill-delete-confirmation"
         tabindex="-1"
         wire:ignore.self
         x-data="{ modal: Modal.getOrCreateInstance($root), key: null }"
         x-on:show-bs-modal.dot="key = $event.relatedTarget.dataset.key"
         x-on:hide-bs-modal.dot="key = null"
         x-on:livewire-skills-skill-removed.window="modal.hide()">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="fas fa-fw fa-trash"></span>
                        {{ __("Delete Skill") }}
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal"
                            type="button"
                            aria-label="{{ __("Close") }}"
                            wire:loading.attr="disabled"
                            wire:target="delete"></button>
                </div>

                <div class="modal-body">
                    <strong>
                        {{ __("Are you sure?") }}
                    </strong>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger"
                            type="button"
                            wire:loading.attr="disabled"
                            wire:target="delete"
                            x-on:click="$wire.call('delete', key)">
                        <span class="fas fa-fw fa-trash"
                              wire:loading.remove
                              wire:target="delete"></span>
                        <span class="fas fa-fw fa-circle-notch fa-spin"
                              wire:loading
                              wire:target="delete"></span>
                        {{ __("Delete") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
