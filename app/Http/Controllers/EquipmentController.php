<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $equipment = Equipment::active()
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%'.$request->string('search').'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('description', 'like', $term));
            })
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('equipment.index', [
            'equipment' => $equipment,
            'categories' => Equipment::CATEGORIES,
        ]);
    }

    public function show(Equipment $equipment)
    {
        $upcoming = $equipment->items()
            ->with(['booking'])
            ->whereHas('booking', function ($query) {
                $query->occupying()->whereDate('event_date', '>=', today());
            })
            ->get()
            ->pluck('booking')
            ->sortBy('event_date')
            ->take(5);

        return view('equipment.show', compact('equipment', 'upcoming'));
    }

    public function create()
    {
        return view('equipment.create', [
            'categories' => Equipment::CATEGORIES,
            'statuses' => Equipment::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Equipment::create($data);

        return redirect()->route('equipment.index')->with('success', 'Equipment added successfully.');
    }

    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', [
            'equipment' => $equipment,
            'categories' => Equipment::CATEGORIES,
            'statuses' => Equipment::STATUSES,
        ]);
    }

    public function update(Request $request, Equipment $equipment)
    {
        $equipment->update($this->validated($request));

        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->items()->exists()) {
            $equipment->update(['status' => 'retired']);

            return redirect()->route('equipment.index')
                ->with('status', 'This item has booking history, so it was archived instead of deleted.');
        }

        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipment deleted.');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:'.implode(',', array_keys(Equipment::CATEGORIES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'status' => ['required', 'in:'.implode(',', array_keys(Equipment::STATUSES))],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function validated(Request $request): array
    {
        return $request->validate($this->rules());
    }
}
