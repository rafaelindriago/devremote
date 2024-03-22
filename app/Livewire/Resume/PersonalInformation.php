<?php

declare(strict_types=1);

namespace App\Livewire\Resume;

use App\Livewire\Traits\WithTrimStrings;
use App\Models\Country;
use App\Models\Developer;
use App\Rules\ModelExistsRule;
use App\Rules\ModelUniqueRule;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read EloquentCollection $countries
 */
class PersonalInformation extends Component
{
    use WithTrimStrings;

    public string $name = '';
    public string $lastName = '';
    public string $birthAt = '';
    public string $email = '';
    public string $phone = '';
    public string $countryCode = '';

    public function mount(): void
    {
        /**
         * @var Developer
         */
        $developer = Auth::user()->developer;

        if ($developer->exists) {
            $this->fill([
                'name' => $developer->name,
                'lastName' => $developer->last_name,
                'birthAt' => $developer->birth_at,
                'email' => $developer->email,
                'phone' => $developer->phone,
                'countryCode' => $developer->country_code,
            ]);
        }
    }

    #[Computed(cache: true)]
    public function countries(): EloquentCollection
    {
        $builder = Country::query();

        $builder->getQuery()
            ->select(['code', 'name', 'data->flags->svg as flag'])
            ->orderBy('name');

        return $builder->get();
    }

    public function render(): View
    {
        return view('livewire.resume.personal-information')
            ->with('countries', $this->countries);
    }

    public function save(): void
    {
        /**
         * @var Developer
         */
        $developer = Auth::user()->developer;

        $this->validate([
            'name' => [
                'required', 'string', 'max:50',
            ],
            'lastName' => [
                'required', 'string', 'max:50',
            ],
            'birthAt' => [
                'required', 'string', 'date',
            ],
            'email' => [
                'required', 'string', 'email:rfc,dns',
                ModelUniqueRule::for(Developer::class)
                    ->ignore($developer),
            ],
            'phone' => [
                'required', 'string', 'phone',
                ModelUniqueRule::for(Developer::class)
                    ->ignore($developer),
            ],
            'countryCode' => [
                'required', 'string',
                ModelExistsRule::for(Country::class, 'code'),
            ],
        ]);

        $developer->fill([
            'name' => $this->name,
            'last_name' => $this->lastName,
            'birth_at' => $this->birthAt,
            'email' => $this->email,
            'phone' => $this->phone,
        ])
            ->country()
            ->associate($this->countryCode)
            ->save();

        $this->dispatch('livewire-toast-show', ...[
            'type' => 'success',
            'message' => __('The changes has been saved.'),
        ]);
    }

    public function resetForm(): void
    {
        /**
         * @var Developer
         */
        $developer = Auth::user()->developer;

        if ($developer->exists) {
            $this->fill([
                'name' => $developer->name,
                'lastName' => $developer->last_name,
                'birthAt' => $developer->birth_at,
                'email' => $developer->email,
                'phone' => $developer->phone,
                'countryCode' => $developer->country_code,
            ]);
        } else {

            $this->reset([
                'name',
                'lastName',
                'birthAt',
                'email',
                'phone',
                'countryCode',
            ]);
        }

        $this->resetValidation();
    }
}
