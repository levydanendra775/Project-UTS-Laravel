<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $teams = Team::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('coach_name', 'like', '%' . $search . '%');
        })
        ->orderBy('name', 'asc')
        ->paginate(10)
        ->withQueryString();

        return view('teams.index', compact('teams', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'coach_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        Team::create([
            'name' => $request->name,
            'coach_name' => $request->coach_name,
            'logo' => $logoPath,
            'description' => $request->description,
        ]);

        return redirect()->route('teams.index')->with('success', 'Tim berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $team->load('players');
        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'coach_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);

        $logoPath = $team->logo;
        if ($request->hasFile('logo')) {
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $team->update([
            'name' => $request->name,
            'coach_name' => $request->coach_name,
            'logo' => $logoPath,
            'description' => $request->description,
        ]);

        return redirect()->route('teams.index')->with('success', 'Tim berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Tim berhasil dihapus.');
    }
}
