<?php

declare(strict_types=1);

namespace App\Livewire\Resume;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\View\View;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class Photo extends Component
{
    use WithFileUploads;

    public ?UploadedFile $file = null;

    public array $cropperData = [];

    public function render(): View
    {
        return view('livewire.resume.photo');
    }

    public function updatedFile(): void
    {
        $this->withValidator(function (Validator $validator): void {
            $validator->after(function (Validator $validator): void {
                if ($validator->errors()->has('file')) {
                    $this->reset('file');
                }
            });
        });

        $this->validate([
            'file' => [
                'required', Rule::imageFile()
                    ->max('8mb')
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth(500)
                            ->minHeight(500)
                    ),
            ],
        ]);

        Image::make($this->file)
            ->resize(null, 500, function (Constraint $constraint): void {
                $constraint->aspectRatio();
            })
            ->save();

        $this->dispatch('livewire-photo-cropper-start', ...[
            'temporaryUrl' => $this->file->temporaryUrl(),
        ]);
    }

    public function cropFile(): void
    {
        $this->validate([
            'cropperData' => [
                'required', 'array:width,height,x,y',
            ],
            'cropperData.*' => [
                'required', 'numeric', 'min:0',
            ],
        ]);

        Image::make($this->file)
            ->crop(
                (int) $this->cropperData['width'],
                (int) $this->cropperData['height'],
                (int) $this->cropperData['x'],
                (int) $this->cropperData['y'],
            )
            ->save();

        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        Storage::putFileAs('images/users', $this->file, $user->id);

        $user->touch();

        $this->dispatch('livewire-photo-cropper-end');

        $this->dispatch('livewire-photo-file-stored');

        $this->dispatch('livewire-toast-show', ...[
            'type' => 'success',
            'message' => __('The photo has been uploaded.'),
        ]);
    }

    public function deleteFile(): void
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        Storage::delete("images/users/{$user->id}");

        $user->touch();

        $this->dispatch('livewire-photo-file-deleted');

        $this->dispatch('livewire-toast-show', ...[
            'type' => 'notice',
            'message' => __('The photo has been deleted.'),
        ]);
    }
}
