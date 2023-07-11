<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateTokenAction;
use App\Actions\Auth\RefreshTokenAction;
use App\Actions\Users\FindUserAction;
use App\Traits\Common\HasApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    use HasApiResponses;

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, CreateTokenAction $action): JsonResponse
    {
        $request->authenticate();

        if (!$token = auth()->attempt($request->validated())) {
            return $this->errorResponse("Invalid credentials.");
        }

        return $this->successResponse($action->run($token));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        auth()->logout();

        return $this->successResponse(message: "User logged out successfully.");
    }

    public function refresh(RefreshTokenAction $action): JsonResponse
    {
        return $this->successResponse($action->run());
    }

    public function getProfile(FindUserAction $action)
    {
        $action->enableQueryBuilder();
        return $action->individualResource($action->findOrFail(auth()->id()));
    }
}
