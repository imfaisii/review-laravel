<?php

namespace App\Actions\Users;

use App\Actions\Common\AbstractFindAction;
use App\Models\User;

class FindUserAction extends AbstractFindAction
{
    protected string $modelClass = User::class;
}
