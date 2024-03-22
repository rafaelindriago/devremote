<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        $rules = [
            'current_password' => [
                'required', 'string', 'current_password:web',
            ],
            'password' => $this->passwordRules(),
        ];

        $messages = [
            'current_password.current_password' => __('The provided password does not match your current password.'),
        ];

        Validator::make($input, $rules, $messages)
            ->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => $input['password'],
        ])
            ->save();
    }
}
