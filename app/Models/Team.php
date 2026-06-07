<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'coach_name',
        'description',
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_team');
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'team1_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class, 'team2_id');
    }

    // Helper to get all matches for a team
    public function getMatchesAttribute()
    {
        return $this->homeMatches->merge($this->awayMatches);
    }
}
