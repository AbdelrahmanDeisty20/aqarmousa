<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;

class VerificationController extends Controller
{
    use ApiResponse;

    public function verify(Request $request, $id, $hash)
    {
        if (!$request->hasValidSignature()) {
            return redirect(config('app.frontend_url') . '/signin?invalid_link=1');
        }

        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return $this->error(__('api.verification.invalid_link'), 403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.frontend_url') . '/signin?already_verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return redirect(config('app.frontend_url') . '/signin?verified=1');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return $this->error(__('api.verification.already_verified'), 400);
        }

        $user->sendEmailVerificationNotification();

        return $this->success(new UserResource($user), __('api.verification.sent'));
    }
}
