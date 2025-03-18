<?php

namespace App\Policies;

use App\Enums\UserRolesEnum;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactPolicy
{
    public function index(User $user): Response
    {
        return $user->hasRole([UserRolesEnum::ADMIN->name, UserRolesEnum::OPERATOR->name])
            ? Response::allow()
            : Response::deny(__('You are not authorized to access this page.'));
    }

    public function store(User $user): Response
    {
        return $user->hasRole([UserRolesEnum::ADMIN->name, UserRolesEnum::OPERATOR->name])
            ? Response::allow()
            : Response::deny(__('You are not authorized to store this contact.'));
    }

    public function update(User $user, Contact $contact): Response
    {
        return ($user->hasRole([UserRolesEnum::ADMIN->name, UserRolesEnum::OPERATOR->name]) and $user->account_id == $contact->account_id)
            ? Response::allow()
            : Response::deny(__('You are not authorized to update this contact.'));
    }

    public function destroy(User $user, Contact $contact): Response
    {
        return ($user->hasRole([UserRolesEnum::ADMIN->name, UserRolesEnum::OPERATOR->name]) and $user->account_id == $contact->account_id)
            ? Response::allow()
            : Response::deny(__('You are not authorized to delete this contact.'));
    }
}
