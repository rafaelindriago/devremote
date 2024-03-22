<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ModelUniqueRule implements DataAwareRule, ValidationRule
{
    protected string $model;
    protected ?string $column;

    protected Model|string|null $ignore = null;
    protected ?Closure $using = null;

    protected array $data = [];

    public function __construct(string $model, ?string $column = null)
    {
        if ( ! is_subclass_of($model, Model::class)) {
            throw new InvalidArgumentException("[{$model}] is not a valid model class.");
        }

        $this->model = $model;
        $this->column = $column;
    }

    public static function for(string $model, ?string $column = null): static
    {
        return new static($model, $column);
    }

    public function ignore(Model|string|null $ignore = null): static
    {
        $this->ignore = $ignore;

        return $this;
    }

    public function using(?Closure $using = null): static
    {
        $this->using = $using;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = $this->model::query()
            ->when(
                $this->using instanceof Closure,
                fn(EloquentBuilder $builder) => ($this->using)($builder, $this->data)
            )
            ->when(
                $this->ignore !== null,
                fn(EloquentBuilder $builder) => $builder->whereKeyNot(
                    $this->ignore instanceof Model
                        ? $this->ignore->getKey()
                        : $this->ignore
                )
            )
            ->where(
                $this->column === null
                    ? $attribute
                    : $this->column,
                $value
            )
            ->count();

        if ($count >= 1) {
            $fail('validation.unique')
                ->translate();
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
