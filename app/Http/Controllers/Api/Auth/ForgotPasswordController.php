<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();

        $broker = Password::broker();

        // Check if a token was recently created to respect throttling
        if ($broker->getRepository()->recentlyCreatedToken($user)) {
            return $this->error(__('api.passwords.throttled'), 429);
        }

        // Generate Token
        $token = $broker->createToken($user);

        // Send the notification manually to bypass the broker's second throttle check
        $user->sendPasswordResetNotification($token);

        return $this->success(['token' => $token], __('api.passwords.sent'));
    }
}
