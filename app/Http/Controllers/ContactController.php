<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contacts = Auth::user()->account->contacts()->paginate(10);

        return view('dashboard.contacts.index', compact('contacts'));
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $contact = Auth::user()->account->contacts()->withTrashed()->createOrFirst([
            'phone_number' => $request->validated()['phone_number'],
            'account_id' => Auth::user()->account_id,
        ], $request->validated());

        if ($contact->trashed()) {
            $contact->restore();
            $contact->update($request->validated());
        }

        return redirect()->route('contacts.index')->with('success', __('Contact created successfully.'));
    }

    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        return redirect()->route('contacts.index')->with('success', __('Contact updated successfully.'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', __('Contact deleted successfully.'));
    }
}
