<?php

namespace App\Actions\Auth;

use Lorisleiva\Actions\Concerns\AsAction;

class RefreshTokenAction
{
    use AsAction;

    public function handle()
    {
        return CreateTokenAction::make()->run(auth()->refresh());
    }
}
