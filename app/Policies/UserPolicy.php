<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewNova($user)
    {
        Log::debug('Inside UserPolicy viewNova');
        return $user->roles->contains('name', 'admin');
    }
}
