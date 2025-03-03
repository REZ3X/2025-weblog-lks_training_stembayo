<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AssignUserRole
{
    public function handle(Registered $event)
    {
        $userRole = Role::findByName('user');
        $event->user->assignRole($userRole);
    }
}