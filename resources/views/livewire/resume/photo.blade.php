<div class="card">
    <h5 class="card-header">
        <span class="fas fa-fw fa-image"></span>
        {{ __("Photo") }}
    </h5>

    <div class="card-body">
        <div class="mb-3 text-center">
            <img class="rounded-circle shadow-sm"
                 src="{{ URL::route("user.image") }}?ts={{ Auth::user()->updated_at->timestamp }}"
                 alt="{{ Auth::user()->name }}"
                 height="150">
        </div>

        <div class="mb-3 text-center">
            <button class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#photo-file-delete-confirmation"
                    type="button"
                    @disabled(Storage::missing("images/users/" . Auth::id()))>
                <span class="fas fa-fw fa-trash"></span>
                {{ __("Delete") }}
            </button>
        </div>

        <div class="mb-0"
             x-data="{ uploading: false, progress: 0 }"
             x-on:livewire-upload-start="uploading = true"
             x-on:livewire-upload-finish="uploading = false; progress = 0"
             x-on:livewire-upload-error="uploading = false; progress = 0"
             x-on:livewire-upload-progress="progress = $event.detail.progress">

            <input class="form-control @error("file") is-invalid @enderror"
                   type="file"
                   wire:model="file"
                   x-bind:disabled="uploading">

            @error("file")
                <div class="invalid-feedback"
                     role="alert">
                    <strong>
                        <span class="fas fa-fw fa-exclamation-circle"></span>
                        {{ $message }}
                    </strong>
                </div>
            @enderror

            <div class="progress mt-2"
                 role="progressbar"
                 x-show="uploading"
                 x-cloak>
                <div class="progress-bar"
                     x-bind:style="{ width: progress + '%' }"
                     x-text="progress + '%'"></div>
            </div>
        </div>
    </div>

    <div class="modal fade"
         id="photo-cropper-modal"
         aria-labelledby="photo-cropper-modal-title"
         tabindex="-1"
         wire:ignore.self
         x-data="{
             modal: Modal.getOrCreateInstance($root),
             cropper: new Cropper($refs.cropper, {
                 aspectRatio: 1 / 1,
                 viewMode: 2,
                 preview: $refs.preview,
                 dragMode: 'none',
                 autoCropArea: 0.99999,
                 movable: false,
                 rotatable: false,
                 scalable: false,
                 zoomable: false,
                 cropBoxResizable: false,
                 toggleDragModeOnDblclick: false
             })
         }"
         x-on:livewire-photo-cropper-start.window="cropper.replace($event.detail.temporaryUrl); modal.show()"
         x-on:livewire-photo-cropper-end.window="cropper.destroy(); modal.hide()">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="photo-cropper-modal-title">
                        <span class="fas fa-fw fa-crop"></span>
                        {{ __("Crop Photo") }}
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal"
                            type="button"
                            aria-label="{{ __("Close") }}"
                            wire:loading.attr="disabled"
                            wire:target="cropFile"></button>
                </div>

                <div class="modal-body">
                    <div wire:ignore>
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="cropper">
                                    <canvas x-ref="cropper"
                                            x-on:crop="$wire.set('cropperData', $event.detail, false)"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cropper-preview overflow-hidden rounded-circle mx-auto"
                                     x-ref="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary"
                            type="button"
                            wire:click="cropFile"
                            wire:loading.attr="disabled"
                            wire:target="cropFile">
                        <span class="fas fa-fw fa-crop"
                              wire:loading.remove
                              wire:target="cropFile"></span>
                        <span class="fas fa-fw fa-circle-notch fa-spin"
                              wire:loading
                              wire:target="cropFile"></span>
                        {{ __("Crop") }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade"
         id="photo-file-delete-confirmation"
         aria-labelledby="photo-file-delete-confirmation-title"
         tabindex="-1"
         wire:ignore.self
         x-data="{ modal: Modal.getOrCreateInstance($root) }"
         x-on:livewire-photo-file-deleted.window="modal.hide()">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"
                        id="photo-file-delete-confirmation-title">
                        <span class="fas fa-fw fa-trash"></span>
                        {{ __("Delete Photo") }}
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal"
                            type="button"
                            aria-label="{{ __("Close") }}"
                            wire:loading.attr="disabled"
                            wire:target="deleteFile"></button>
                </div>

                <div class="modal-body">
                    <strong>
                        {{ __("Are you sure?") }}
                    </strong>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger"
                            type="button"
                            wire:click="deleteFile"
                            wire:loading.attr="disabled"
                            wire:target="deleteFile">
                        <span class="fas fa-fw fa-trash"
                              wire:loading.remove
                              wire:target="deleteFile"></span>
                        <span class="fas fa-fw fa-circle-notch fa-spin"
                              wire:loading
                              wire:target="deleteFile"></span>
                        {{ __("Delete") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
