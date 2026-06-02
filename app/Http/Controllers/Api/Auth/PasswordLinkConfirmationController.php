<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PasswordLinkConfirmationController extends Controller
{
    /**
     * Handle the click from the password reset email.
     */
    public function verify(Request $request, $token)
    {
        // Mark this token as "clicked" in cache for 1 hour
        Cache::put("password_reset_clicked_{$token}", true, 3600);

        $frontendUrl = config('app.frontend_url');
        $email = $request->query('email');

        // Redirect to the frontend/app password reset page
        return redirect("{$frontendUrl}/password-reset/{$token}?email={$email}&verified=true");
    }
}
