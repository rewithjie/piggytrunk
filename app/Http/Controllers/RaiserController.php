<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RaiserController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();
        
        // Fetch 3 Fattening and 2 Sow raisers for consistency with Dashboard
        $fatteningRaisers = Raiser::where('pig_type', 'Fattening')->orderBy('name')->limit(3)->get();
        $sowRaisers = Raiser::where('pig_type', 'Sow')->orderBy('name')->limit(2)->get();
        $raisers = $fatteningRaisers->concat($sowRaisers);
        
        // Apply search filter if needed
        if ($query !== '') {
            $raisers = $raisers->filter(function ($raiser) use ($query) {
                return stripos($raiser->name, $query) !== false ||
                       stripos($raiser->code, $query) !== false ||
                       stripos($raiser->location, $query) !== false ||
                       stripos($raiser->batch, $query) !== false ||
                       stripos($raiser->pig_type, $query) !== false ||
                       stripos($raiser->status, $query) !== false;
            });
        }

        return view('pages.raisers.index', [
            'pageTitle' => 'Hog Raiser',
            'raisers' => $raisers,
            'query' => $query,
            'user' => $this->user(),
        ]);
    }

    public function create(): View
    {
        return view('pages.raisers.create', [
            'pageTitle' => 'Create New Raiser',
            'user' => $this->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $raiser = Raiser::create($this->validateRaiser($request));

        return redirect()
            ->route('raisers.index')
            ->with('status', "{$raiser->name} was added to the hog raisers.");
    }

    public function show(int $raiser): View
    {
        $record = Raiser::findOrFail($raiser);

        return view('pages.raisers.show', [
            'hideTopbarTitle' => true,
            'raiser' => $record,
            'user' => $this->user(),
        ]);
    }

    public function edit(int $raiser): View
    {
        $record = Raiser::findOrFail($raiser);

        return view('pages.raisers.edit', [
            'hideTopbarTitle' => true,
            'raiser' => $record,
            'user' => $this->user(),
        ]);
    }

    public function update(Request $request, int $raiser): RedirectResponse
    {
        $record = Raiser::findOrFail($raiser);
        $record->update($this->validateRaiser($request, $record->id));

        return redirect()
            ->route('raisers.edit', $record)
            ->with('status', 'Raiser account updated successfully.');
    }

    private function directoryQuery(string $query)
    {
        return Raiser::query()
            ->when($query !== '', function ($db) use ($query) {
                $db->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('code', 'like', "%{$query}%")
                        ->orWhere('location', 'like', "%{$query}%")
                        ->orWhere('batch', 'like', "%{$query}%")
                        ->orWhere('pig_type', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%");
                });
            })
            ->orderBy('name');
    }

    private function validateRaiser(Request $request, ?int $raiserId = null): array
    {
        $emailRule = $raiserId ? "unique:raisers,email,{$raiserId}" : 'unique:raisers,email';
        $codeRule = $raiserId ? "unique:raisers,code,{$raiserId}" : 'unique:raisers,code';

        return $request->validate([
            'code' => ['required', 'string', 'max:255', $codeRule],
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'location' => ['required', 'string', 'max:255'],
            'batch' => ['required', 'string', 'max:255'],
            'pig_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ]);
    }

    private function user(): array
    {
        return [
            'name' => 'De Luna Admin',
            'role' => 'System Administrator',
            'initials' => 'DL',
        ];
    }
}
