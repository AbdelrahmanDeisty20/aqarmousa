<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data)
    {
        $avatarPath = null;
        $idPhotoPath = null;

        if (isset($data['avatar']) && $data['avatar'] !== null) {
            $avatarPath = $data['avatar']->store('avatars', 'public');
        }

        if (isset($data['id_photo']) && $data['id_photo'] !== null) {
            $idPhotoPath = $data['id_photo']->store('id_photos', 'public');
        }

        $status = ($data['role'] ?? 'buyer') === 'seller' ? 'pending' : 'approved';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'buyer',
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'status' => $status,
            'avatar' => $avatarPath,
            'id_photo' => $idPhotoPath,
            'governorate_id' => $data['governorate_id'] ?? null,
        ]);

        $user->sendEmailVerificationNotification();

        $user->avatar_url = $user->avatar ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) : null;
        $user->id_photo_url = $user->id_photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->id_photo) : null;

        return [
            'user' => $user,
            'requires_verification' => true
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [__('api.verification.not_verified')],
            ]);
        }

        return $this->generateTokenResponse($user);
    }

    public function logout(User $user)
    {
        $user->currentAccessToken()->delete();
    }

    public function updateProfile(User $user, array $data)
    {
        // Filter out empty or null values to keep old values
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        // Ensure email is not updated
        unset($data['email']);

        if (isset($data['avatar'])) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $data['avatar']->store('avatars', 'public');
        }

        if (isset($data['id_photo'])) {
            if ($user->id_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->id_photo);
            }
            $data['id_photo'] = $data['id_photo']->store('id_photos', 'public');
        }

        if (isset($data['password'])) {
            // Verify current password
            if (!isset($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => [__('api.current_password_incorrect')],
                ]);
            }
            $data['password'] = Hash::make($data['password']);
        }

        // Remove current_password from $data before update
        unset($data['current_password']);

        $user->update($data);

        $user->avatar_url = $user->avatar ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) : null;

        return $user;
    }

    public function deleteProfile(User $user)
    {
        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        // Optional: Delete tokens before deleting user
        $user->tokens()->delete();

        return $user->delete();
    }

    public function refreshToken(User $user)
    {
        // Delete the current token (optional: strictly rotating)
        $user->currentAccessToken()->delete();

        return $this->generateTokenResponse($user);
    }

    protected function generateTokenResponse(User $user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->avatar_url = $user->avatar ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) : null;
        $user->id_photo_url = $user->id_photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->id_photo) : null;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }
}
