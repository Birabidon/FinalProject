<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */

    public function isAdmin(User $user)
    {
        return $user->email === 'nikiton.osipoff@gmail.com';
    }

    public function update(User $authUser, User $targetUser)
    {
        return ($authUser->email === $targetUser->email || $this->isAdmin($authUser));
    }

    public function delete(User $authUser, User $targetUser)
    {
        return ($authUser->email === $targetUser->email || $authUser->isAdmin());
    }


}
