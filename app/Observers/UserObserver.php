<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{

    /**
     * Handle the User "deleting" event.
     */
    public function deleting(User $user): void
    {
        $user->syncPermissions([]);
        $user->roles()->detach();
    }
}
