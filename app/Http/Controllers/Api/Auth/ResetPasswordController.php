<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {
        // Enforce that the link in the email must have been clicked
        if (!Cache::has("password_reset_clicked_{$request->token}")) {
            return $this->error(__('api.passwords.link_not_clicked'), 403);
        }

        $status = Password::broker()->reset(
            $request->validated(),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->success([], __('api.passwords.reset'))
            : $this->error(__("api." . $status));
    }
}
