<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $players = Player::with('team')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('position', 'like', '%' . $search . '%')
                      ->orWhereHas('team', function ($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('players.index', compact('players', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::orderBy('name', 'asc')->get();
        return view('players.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'back_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Player::where('team_id', $request->team_id)
                        ->where('back_number', $value)
                        ->exists();
                    if ($exists) {
                        $fail('Nomor punggung ini sudah digunakan di tim ini.');
                    }
                }
            ],
            'position' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        Player::create($request->all());

        return redirect()->route('players.index')->with('success', 'Pemain berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return redirect()->route('players.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        $teams = Team::orderBy('name', 'asc')->get();
        return view('players.edit', compact('player', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'back_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                function ($attribute, $value, $fail) use ($request, $player) {
                    $exists = Player::where('team_id', $request->team_id)
                        ->where('back_number', $value)
                        ->where('id', '!=', $player->id)
                        ->exists();
                    if ($exists) {
                        $fail('Nomor punggung ini sudah digunakan di tim ini.');
                    }
                }
            ],
            'position' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $player->update($request->all());

        return redirect()->route('players.index')->with('success', 'Pemain berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('success', 'Pemain berhasil dihapus.');
    }
}
