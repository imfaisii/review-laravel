<?php

namespace App\Actions\Users;

use App\Actions\Common\AbstractDeleteAction;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserAction extends AbstractDeleteAction
{
    protected string $modelClass = User::class;
}
