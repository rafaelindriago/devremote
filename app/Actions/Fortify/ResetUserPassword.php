<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        $rules = [
            'password' => $this->passwordRules(),
        ];

        Validator::make($input, $rules)
            ->validate();

        $user->forceFill([
            'password' => $input['password'],
        ])
            ->save();
    }
}
