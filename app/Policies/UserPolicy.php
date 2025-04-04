<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function deleteOthers(User $user)
    {
        return $user->email === 'nikiton.osipoff@gmail.com';
    }

    public function deleteSelf(User $user)
    {
        return $user->email === Auth::user()->email;
    }
}
