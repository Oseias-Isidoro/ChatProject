<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreUserRequest, UpdateUserRequest};
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{Auth, Hash};

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewAny,App\Models\User')->only('index');
        $this->middleware('can:delete,user')->only('destroy');
    }

    public function index(): View
    {
        $users = Auth::user()->account->users()->paginate(10);

        return view('dashboard.users.index', compact('users'));
    }


    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = Auth::user()->account->users()->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roles);

        $user->numbers()->sync($request->numbers);

        event(new Registered($user));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());
        $user->numbers()->sync($request->numbers);
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User has been deleted.');
    }
}
