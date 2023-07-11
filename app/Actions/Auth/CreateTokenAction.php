<?php

namespace App\Actions\Auth;

use Lorisleiva\Actions\Concerns\AsAction;

class CreateTokenAction
{
    use AsAction;

    protected int $expiresIn = 0;

    public function __construct()
    {
        $this->expiresIn = auth()->factory()->getTTL() * 60;
    }

    public function handle(mixed $token, int $expiresIn = null)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn ?? $this->expiresIn,
            'user' => auth()->user()
        ];
    }
}
