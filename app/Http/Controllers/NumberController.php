<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\StoreNumberRequest;
use App\Http\Requests\UpdateNumberRequest;
use App\Models\Number;
use App\Services\EvolutionApi\Endpoints\EvolutionInstanceService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NumberController extends Controller
{
    public function __construct(
        public EvolutionInstanceService $evolutionInstanceService,
    )
    {}

    public function index(): Factory|Application|View
    {
        Gate::authorize('index', Number::class);

        $numbers = Auth::user()->account
            ->numbers()
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('dashboard.numbers.index', compact('numbers'));
    }

    public function connect(Number $number): RedirectResponse
    {
        $this->authorize('connect', $number);

        $response = $this->evolutionInstanceService->connect($number->evolutionInstanceName);

        $this->evolutionInstanceService->setWebsocket($number->evolutionInstanceName)->body();

        if ($response->notFound()) {
            return redirect()->route('numbers.index')
                ->with('error', __('Number not found'));
        }

        if ($response->object()->instance ?? false and $response->object()->instance?->state == 'open') {

            $number->update([
                'status' => StatusEnum::ACTIVE,
            ]);

            return redirect()->route('numbers.index')
                ->with('instance_data', [
                    'status' => 'connected',
                    'instance_name' => $number->phone_number,
                ]);
        }

        return redirect()->route('numbers.index')
            ->with('instance_data', [
                'qrcode' => $response->object()->base64,
                'instance_name' => $number->evolutionInstanceName,
            ]);
    }

    public function disconnect(Number $number): RedirectResponse
    {
        $this->authorize('disconnect', $number);

        $response = $this->evolutionInstanceService->disconnect($number->evolutionInstanceName);

        if ($response->failed() and $response->status() !== 400) {
            return redirect()->route('numbers.index')->with('error', __('Not possible to disconnect number.'));
        }

        $number->update([
            'status' => StatusEnum::INACTIVE,
        ]);

        return redirect()->route('numbers.index')->with('success', __('Number disconnected.'));
    }

    public function store(StoreNumberRequest $request): RedirectResponse
    {
        $number = Auth::user()->account
            ->numbers()
            ->create($request->validated());

        $response = $this->evolutionInstanceService->create($number);

        if ($response->failed()) {
            $number->delete();
            return redirect()->route('numbers.index')->with('error', __('Error creating the number.'));
        }

        return redirect()->route('numbers.index')->with('success', __('Number created successfully.'));
    }

    public function update(UpdateNumberRequest $request, Number $number): RedirectResponse
    {
        $number->update($request->validated());

        return redirect()->route('numbers.index')->with('success', 'Number updated successfully.');
    }

    public function destroy(Number $number): RedirectResponse
    {
        $this->authorize('destroy', $number);

        if ($this->evolutionInstanceService->delete($number->evolutionInstanceName)->failed()) {
            return redirect()->route('numbers.index')->with('error', __('Not possible to delete number.'));
        }

        $number->delete();

        return redirect()->route('numbers.index')->with('success', 'Number deleted successfully.');
    }
}
