<?php

declare(strict_types=1);

namespace App\Livewire\Resume;

use App\Livewire\Traits\WithTrimStrings;
use App\Models\Language;
use App\Models\Skill;
use App\Rules\ModelExistsRule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read EloquentCollection $languages
 */
class Skills extends Component
{
    use WithTrimStrings;

    public string $key = '';
    public string $languageKey = '';
    public string $languageName = '';
    public string $languageIcon = '';
    public string $level = '';
    public string $levelTranslated = '';
    public string $description = '';

    public array $skills = [];
    public array $skillsTrash = [];

    #[Computed(cache: true)]
    public function languages(): EloquentCollection
    {
        $builder = Language::query();

        $builder->getQuery()
            ->select(['id', 'name', 'data->class as class'])
            ->orderBy('name');

        return $builder->get();
    }

    public function mount(): void
    {
        /**
         * @var \App\Models\Developer
         */
        $developer = Auth::user()->developer;

        $this->fill([
            'skills' => Skill::query()
                ->with('language')
                ->whereBelongsTo($developer)
                ->get()
                ->map(function (Skill $skill) {
                    return [
                        'languageKey' => $skill->language->id,
                        'languageName' => $skill->language->name,
                        'languageIcon' => $skill->language->data->class,
                        'level' => $skill->level,
                        'levelTranslated' => __($skill->level),
                        'description' => $skill->description,
                        'key' => $skill->id,
                    ];
                })
                ->toArray(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.resume.skills')
            ->with('languages', $this->languages);
    }

    public function updatedLanguageKey(string $languageKey): void
    {
        if ($this->languages->contains($languageKey)) {
            $this->fill([
                'languageName' => $this->languages->find($languageKey)
                    ->getAttribute('name'),
                'languageIcon' => $this->languages->find($languageKey)
                    ->getAttribute('class'),
            ]);
        } else {
            $this->reset([
                'languageName',
                'languageIcon',
            ]);
        }
    }

    public function updatedLevel(string $level): void
    {
        $this->fill([
            'levelTranslated' => __($level),
        ]);
    }

    public function save(): void
    {
        /**
         * @var \App\Models\Developer
         */
        $developer = Auth::user()->developer;

        $this->validate([
            'languageKey' => [
                'required', 'string', 'uuid',
                ModelExistsRule::for(Language::class),
            ],
            'level' => [
                'required', 'string', 'in:Beginner,Intermediate,Expert',
            ],
            'description' => [
                'required', 'string', 'max:256',
            ],
            'key' => [
                'nullable', 'string', 'uuid',
                ModelExistsRule::for(Skill::class)
                    ->using(function (EloquentBuilder $builder) use ($developer): void {
                        $builder->whereBelongsTo($developer);
                    }),
            ],
        ]);

        if ($this->skills === []) {
            $this->skills[] = $this->only([
                'languageKey',
                'languageName',
                'languageIcon',
                'level',
                'levelTranslated',
                'description',
                'key',
            ]);
        } else {

            foreach ($this->skills as $key => $item) {
                if (Arr::get($item, 'languageKey') === $this->languageKey) {
                    $this->skills[$key] = $this->only([
                        'languageKey',
                        'languageName',
                        'languageIcon',
                        'level',
                        'levelTranslated',
                        'description',
                        'key',
                    ]);

                    $this->dispatch('livewire-skills-skill-edited');

                    break;
                }

                if (Arr::last($this->skills) === $item) {
                    $this->skills[] = $this->only([
                        'languageKey',
                        'languageName',
                        'languageIcon',
                        'level',
                        'levelTranslated',
                        'description',
                        'key',
                    ]);
                }
            }
        }

        $this->reset([
            'languageKey',
            'languageName',
            'languageIcon',
            'level',
            'levelTranslated',
            'description',
            'key',
        ]);
    }

    public function resetForm(bool $force = false): void
    {
        $this->resetValidation([
            'languageKey',
            'level',
            'description',
        ]);

        if ($this->key && ! $force) {
            $skill = Arr::first(
                $this->skills,
                fn(array $item) => Arr::get($item, 'key') === $this->key
            );

            $this->fill(Arr::only($skill, [
                'languageKey',
                'languageName',
                'languageIcon',
                'level',
                'levelTranslated',
                'description',
                'key',
            ]));
        } else {

            $this->reset([
                'languageKey',
                'languageName',
                'languageIcon',
                'level',
                'levelTranslated',
                'description',
                'key',
            ]);
        }
    }

    public function edit(int $key): void
    {
        if (Arr::has($this->skills, $key)) {
            $this->fill(Arr::only($this->skills[$key], [
                'languageKey',
                'languageName',
                'languageIcon',
                'level',
                'levelTranslated',
                'description',
                'key',
            ]));

            $this->dispatch('livewire-skills-skill-editing');
        }
    }

    public function delete(int $key): void
    {
        if (Arr::get($this->skills, "{$key}.key")) {
            $this->skillsTrash[] = Arr::pull($this->skills, $key);
        } else {
            Arr::forget($this->skills, $key);
        }

        $this->dispatch('livewire-skills-skill-removed');
    }

    public function saveAll(): void
    {
        /**
         * @var \App\Models\Developer
         */
        $developer = Auth::user()->developer;

        $this->validate([
            'skills' => [
                'nullable', 'array', 'max:8',
            ],
            'skills.*.languageKey' => [
                'required', 'string', 'uuid', 'distinct',
                ModelExistsRule::for(Language::class),
            ],
            'skills.*.level' => [
                'required', 'string', 'in:Beginner,Intermediate,Expert',
            ],
            'skills.*.description' => [
                'required', 'string', 'max:256',
            ],
            'skills.*.key' => [
                'nullable', 'string', 'uuid',
                ModelExistsRule::for(Skill::class)
                    ->using(function (EloquentBuilder $builder) use ($developer): void {
                        $builder->whereBelongsTo($developer);
                    }),
            ],
        ]);

        foreach ($this->skills as $item) {
            if (Arr::get($item, 'key')) {

                $skill = Skill::query()
                    ->whereRelation('language', 'id', Arr::get($item, 'languageKey'))
                    ->whereBelongsTo($developer)
                    ->find($item['key']);

                if ($skill) {
                    $skill->fill(Arr::only($item, [
                        'level',
                        'description',
                    ]))
                        ->save();
                }
            } else {

                $skill = Skill::make(Arr::only($item, [
                    'level',
                    'description',
                ]))
                    ->language()
                    ->associate(Arr::get($item, 'languageKey'))
                    ->developer()
                    ->associate($developer);

                $skill->save();
            }
        }

        foreach ($this->skillsTrash as $item) {
            if (Arr::get($item, 'key')) {

                Skill::query()
                    ->whereBelongsTo($developer)
                    ->whereKey($item['key'])
                    ->delete();
            }
        }

        $this->resetValidation([
            'skills.*',
        ]);

        $this->fill([
            'skills' => Skill::query()
                ->with('language')
                ->whereBelongsTo($developer)
                ->get()
                ->map(function (Skill $skill) {
                    return [
                        'languageKey' => $skill->language->id,
                        'languageName' => $skill->language->name,
                        'languageIcon' => $skill->language->data->class,
                        'level' => $skill->level,
                        'levelTranslated' => __($skill->level),
                        'description' => $skill->description,
                        'key' => $skill->id,
                    ];
                })
                ->toArray(),
        ]);

        $this->reset([
            'skillsTrash',
        ]);

        $this->dispatch('livewire-toast-show', ...[
            'type' => 'success',
            'message' => __('The changes has been saved.'),
        ]);
    }

    public function resetFormAll(): void
    {
        /**
         * @var \App\Models\Developer
         */
        $developer = Auth::user()->developer;

        $this->resetValidation([
            'skills.*',
        ]);

        $this->fill([
            'skills' => Skill::query()
                ->with('language')
                ->whereBelongsTo($developer)
                ->get()
                ->map(function (Skill $skill) {
                    return [
                        'languageKey' => $skill->language->id,
                        'languageName' => $skill->language->name,
                        'languageIcon' => $skill->language->data->class,
                        'level' => $skill->level,
                        'levelTranslated' => __($skill->level),
                        'description' => $skill->description,
                        'key' => $skill->id,
                    ];
                })
                ->toArray(),
        ]);

        $this->reset([
            'skillsTrash',
        ]);
    }
}
