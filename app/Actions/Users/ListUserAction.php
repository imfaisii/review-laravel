<?php

namespace App\Actions\Users;

use App\Actions\Common\AbstractListAction;
use App\Models\User;

class ListUserAction extends AbstractListAction
{
    protected string $modelClass = User::class;
}
