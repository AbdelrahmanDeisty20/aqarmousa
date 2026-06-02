<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Service\AuthService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->validated());
        $response['user'] = new UserResource($response['user']);
        return $this->success($response, __("api.register_success"), 201);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());
        $response['user'] = new UserResource($response['user']);
        return $this->success($response, __("api.login_success"));
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return $this->success([], __('api.logout_success'));
    }

    public function profile(Request $request)
    {
        return $this->success(new UserResource($request->user()));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $this->authService->updateProfile($request->user(), $request->validated());
        return $this->success(new UserResource($user), __('api.profile_updated'));
    }

    public function destroy(Request $request)
    {
        $this->authService->deleteProfile($request->user());
        return $this->success([], __('api.profile_deleted'));
    }

    public function refresh(Request $request)
    {
        $response = $this->authService->refreshToken($request->user());
        $response['user'] = new UserResource($response['user']);
        return $this->success($response, __('api.token_refreshed'));
    }
}
