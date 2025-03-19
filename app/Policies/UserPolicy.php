<?php

namespace App\Policies;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->hasRole(UserRolesEnum::ADMIN->name)
            ? Response::allow()
            : Response::deny('You do not have permission to view any user.');
    }

    public function create(User $user): Response
    {
        return $user->hasRole(UserRolesEnum::ADMIN->name)
            ? Response::allow()
            : Response::deny('You do not have permission to create new user.');
    }

    public function update(User $user, User $model): Response
    {
        return ($user->hasRole(UserRolesEnum::ADMIN->name) and $user->account_id == $model->account_id)
            ? Response::allow()
            : Response::deny('You do not have permission to update user.');
    }

    public function delete(User $user, User $model): Response
    {
        return ($user->hasRole(UserRolesEnum::ADMIN->name) and $user->account_id == $model->account_id)
            ? Response::allow()
            : Response::deny('You do not have permission to delete user.');
    }
}
