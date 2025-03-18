<?php

namespace App\Policies;

use App\Enums\UserRolesEnum;
use App\Models\Number;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NumberPolicy
{
    public function index(User $user): Response
    {
        return $user->hasRole(UserRolesEnum::ADMIN->name)
            ? Response::allow()
            : Response::deny(__('You are not authorized to access this page.'));
    }

    public function store(User $user): Response
    {
        return $user->hasRole(UserRolesEnum::ADMIN->name)
            ? Response::allow()
            : Response::deny(__('You are not authorized to store this number.'));
    }

    public function update(User $user, Number $number): Response
    {
        return ($user->hasRole(UserRolesEnum::ADMIN->name) and $user->account_id == $number->account_id)
            ? Response::allow()
            : Response::deny(__('You are not authorized to update this number.'));
    }

    public function connect(User $user, Number $number): Response
    {
        return ($user->hasRole(UserRolesEnum::ADMIN->name) and $user->account_id == $number->account_id)
            ? Response::allow()
            : Response::deny(__('You are not authorized to connect numbers.'));
    }


    public function disconnect(User $user, Number $number): Response
    {
        return ($user->hasRole(UserRolesEnum::ADMIN->name) and $user->account_id == $number->account_id)
            ? Response::allow()
            : Response::deny(__('You are not authorized to disconnect numbers.'));
    }

    public function destroy(User $user, Number $number): Response
    {
        return ($user->hasRole(UserRolesEnum::ADMIN->name) and $user->account_id == $number->account_id)
            ? Response::allow()
            : Response::deny(__('You are not authorized to delete numbers.'));
    }
}
