<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\Standing;
use App\Models\TournamentMatch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@futsal.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $panitia = User::create([
            'name' => 'Panitia Lapangan',
            'email' => 'panitia@futsal.com',
            'password' => Hash::make('panitia123'),
            'role' => 'panitia',
        ]);

        // 2. Seed Teams (8 Teams total for 2 Groups of 4 Teams)
        $teamsData = [
            ['name' => 'Kancil WHW', 'coach_name' => 'Wahyu Kocoy', 'desc' => 'Tim futsal asal Pontianak, Kalimantan Barat.'],
            ['name' => 'Bintang Timur Surabaya', 'coach_name' => 'Hector Souto', 'desc' => 'Juara bertahan Pro Futsal League asal Surabaya.'],
            ['name' => 'Black Steel Papua', 'coach_name' => 'Chema Jimenez', 'desc' => 'Tim futsal kuat asal Manokwari, Papua.'],
            ['name' => 'Cosmo JNE Jakarta', 'coach_name' => 'Deny Handoyo', 'desc' => 'Tim futsal konsisten asal Jakarta.'],
            ['name' => 'Pendekar United', 'coach_name' => 'Alvaro Martinez', 'desc' => 'Tim futsal milik Atta Halilintar.'],
            ['name' => 'Unggul FC Malang', 'coach_name' => 'Andri Irawan', 'desc' => 'Tim promosi kuat asal Malang.'],
            ['name' => 'Fafage Vamos', 'coach_name' => 'Doni Zola', 'desc' => 'Tim gabungan kuat asal Mataram/Banjarbaru.'],
            ['name' => 'Halus FC Jakarta', 'coach_name' => 'Yolla Medina', 'desc' => 'Tim taktis asal Jakarta.'],
        ];

        $teams = [];
        foreach ($teamsData as $data) {
            $teams[] = Team::create([
                'name' => $data['name'],
                'coach_name' => $data['coach_name'],
                'description' => $data['desc'],
                'logo' => null, // Uploaded manually later
            ]);
        }

        // 3. Seed Players for each Team - Nama pemain asli Pro Futsal League (Update Terbaru/Iconic 2025-2026)
        $playersData = [
            // Index 0 - Kancil WHW (Pangsuma FC)
            [
                ['name' => 'Muhammad Nizar Nayaruddin', 'back_number' => 21, 'position' => 'Goalkeeper', 'birth' => '1995-02-17'],
                ['name' => 'Romi Humandri',             'back_number' => 14, 'position' => 'Defender',   'birth' => '1997-11-14'],
                ['name' => 'Muhammad Syaifullah',       'back_number' => 7,  'position' => 'Flank',      'birth' => '1998-05-20'],
                ['name' => 'Filippo Inzaghi',           'back_number' => 10, 'position' => 'Pivot',      'birth' => '1999-07-07'],
                ['name' => 'Yogi Saputra',              'back_number' => 11, 'position' => 'Universal',  'birth' => '1996-03-24'],
            ],
            // Index 1 - Bintang Timur Surabaya (BTS)
            [
                ['name' => 'Ahmad Habibie',             'back_number' => 99, 'position' => 'Goalkeeper', 'birth' => '2000-06-25'],
                ['name' => 'Rio Pangestu Putra',         'back_number' => 4,  'position' => 'Defender',   'birth' => '1997-08-30'],
                ['name' => 'Ardiansyah Runtuboy',       'back_number' => 7,  'position' => 'Flank',      'birth' => '1998-07-15'],
                ['name' => 'Samuel Eko Putra',          'back_number' => 9,  'position' => 'Pivot',      'birth' => '1998-05-16'],
                ['name' => 'Firman Adriansyah',         'back_number' => 10, 'position' => 'Universal',  'birth' => '1997-02-09'],
            ],
            // Index 2 - Black Steel Papua
            [
                ['name' => 'M. Albagir',                'back_number' => 2,  'position' => 'Goalkeeper', 'birth' => '1997-12-13'],
                ['name' => 'Ardiansyah Nur',            'back_number' => 5,  'position' => 'Defender',   'birth' => '1997-08-27'],
                ['name' => 'W. Brian Ick',              'back_number' => 11, 'position' => 'Flank',      'birth' => '1999-10-18'],
                ['name' => 'Evan Soumilena',            'back_number' => 10, 'position' => 'Pivot',      'birth' => '1996-11-19'],
                ['name' => 'Piter E. Masriat',          'back_number' => 12, 'position' => 'Universal',  'birth' => '1997-06-03'],
            ],
            // Index 3 - Cosmo JNE Jakarta
            [
                ['name' => 'Muhammad Wildan',           'back_number' => 1,  'position' => 'Goalkeeper', 'birth' => '1999-03-21'],
                ['name' => 'Rizki Xavier',              'back_number' => 5,  'position' => 'Defender',   'birth' => '1999-01-15'],
                ['name' => 'Dewa Rizki Amanda',         'back_number' => 7,  'position' => 'Flank',      'birth' => '2001-01-16'],
                ['name' => 'Israr Megantara',           'back_number' => 9,  'position' => 'Pivot',      'birth' => '2002-05-19'],
                ['name' => 'Reza Gunawan',              'back_number' => 10, 'position' => 'Universal',  'birth' => '1998-10-25'],
            ],
            // Index 4 - Pendekar United (Iconic Squad)
            [
                ['name' => 'Moch Irfan',                'back_number' => 1,  'position' => 'Goalkeeper', 'birth' => '1996-05-20'],
                ['name' => 'Fufung Andi',               'back_number' => 4,  'position' => 'Defender',   'birth' => '1995-03-12'],
                ['name' => 'Ricardinho',                'back_number' => 20, 'position' => 'Flank',      'birth' => '1981-09-03'],
                ['name' => 'Subhan Faidasa',            'back_number' => 9,  'position' => 'Pivot',      'birth' => '1994-09-09'],
                ['name' => 'Atta Halilintar',           'back_number' => 11, 'position' => 'Universal',  'birth' => '1994-11-20'],
            ],
            // Index 5 - Unggul FC Malang
            [
                ['name' => 'Angga Ariansyah',           'back_number' => 1,  'position' => 'Goalkeeper', 'birth' => '1997-05-11'],
                ['name' => 'Anton Cahyo',               'back_number' => 5,  'position' => 'Defender',   'birth' => '1996-07-28'],
                ['name' => 'Ikrima Nofiansyah',         'back_number' => 7,  'position' => 'Flank',      'birth' => '1999-11-09'],
                ['name' => 'Armia Zainul Almaraghi',    'back_number' => 10, 'position' => 'Pivot',      'birth' => '2001-08-20'],
                ['name' => 'Gvin Blandino Laik',        'back_number' => 11, 'position' => 'Universal',  'birth' => '2000-01-23'],
            ],
            // Index 6 - Fafage Vamos (Fafage Banua)
            [
                ['name' => 'Muhammad Irfan',            'back_number' => 1,  'position' => 'Goalkeeper', 'birth' => '1997-10-18'],
                ['name' => 'Nandy Sukma',               'back_number' => 5,  'position' => 'Defender',   'birth' => '1996-06-25'],
                ['name' => 'Bambang Bayu Saptaji',      'back_number' => 12, 'position' => 'Flank',      'birth' => '1992-02-11'],
                ['name' => 'Caio Almeida',              'back_number' => 10, 'position' => 'Pivot',      'birth' => '1995-12-05'],
                ['name' => 'Ramadhan Saputra',          'back_number' => 7,  'position' => 'Universal',  'birth' => '1999-04-14'],
            ],
            // Index 7 - Halus FC Jakarta
            [
                ['name' => 'Jafar Sidik',               'back_number' => 1,  'position' => 'Goalkeeper', 'birth' => '1998-03-08'],
                ['name' => 'Muhammad Wildan',           'back_number' => 4,  'position' => 'Defender',   'birth' => '1997-09-12'],
                ['name' => 'Achmad Alfyanto',           'back_number' => 7,  'position' => 'Flank',      'birth' => '1997-12-16'],
                ['name' => 'Abdussalam',                'back_number' => 10, 'position' => 'Pivot',      'birth' => '1996-10-21'],
                ['name' => 'Defryan Ramadhan',          'back_number' => 15, 'position' => 'Universal',  'birth' => '1999-01-30'],
            ],
        ];

        foreach ($teams as $index => $team) {
            foreach ($playersData[$index] as $playerInfo) {
                Player::create([
                    'team_id'     => $team->id,
                    'name'        => $playerInfo['name'],
                    'back_number' => $playerInfo['back_number'],
                    'position'    => $playerInfo['position'],
                    'birth_date'  => Carbon::parse($playerInfo['birth']),
                ]);
            }
        }

        // 4. Seed Tournament
        $tournament = Tournament::create([
            'name' => 'Liga Futsal Nusantara 2026',
            'status' => 'ongoing',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(14),
        ]);

        // 5. Seed Groups
        $groupA = Group::create([
            'tournament_id' => $tournament->id,
            'name' => 'Grup A',
        ]);

        $groupB = Group::create([
            'tournament_id' => $tournament->id,
            'name' => 'Grup B',
        ]);

        // 6. Assign Teams to Groups (4 teams each)
        $groupA_teams = [$teams[0], $teams[1], $teams[2], $teams[3]];
        $groupB_teams = [$teams[4], $teams[5], $teams[6], $teams[7]];

        foreach ($groupA_teams as $team) {
            $groupA->teams()->attach($team->id);
            Standing::create([
                'tournament_id' => $tournament->id,
                'group_id' => $groupA->id,
                'team_id' => $team->id,
            ]);
        }

        foreach ($groupB_teams as $team) {
            $groupB->teams()->attach($team->id);
            Standing::create([
                'tournament_id' => $tournament->id,
                'group_id' => $groupB->id,
                'team_id' => $team->id,
            ]);
        }

        // 7. Seed Group Matches (Round Robin)
        $this->generateMatchesForGroup($tournament, $groupA, $groupA_teams);
        $this->generateMatchesForGroup($tournament, $groupB, $groupB_teams);

        // 8. Simulate Some Played Matches to Fill Standings
        $this->simulateMatchResults($groupA->id);
        $this->simulateMatchResults($groupB->id);
    }

    private function generateMatchesForGroup($tournament, $group, $teams)
    {
        $count = count($teams);
        $countGenerated = 0;
        
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'group_id' => $group->id,
                    'round' => 'group',
                    'team1_id' => $teams[$i]->id,
                    'team2_id' => $teams[$j]->id,
                    'match_date' => Carbon::now()->addHours(14 + $countGenerated),
                    'status' => 'scheduled',
                ]);
                $countGenerated++;
            }
        }
    }

    private function simulateMatchResults($groupId)
    {
        // Get first 2 matches of this group to simulate played
        $matches = TournamentMatch::where('group_id', $groupId)
            ->where('status', 'scheduled')
            ->take(2)
            ->get();

        $scores = [
            [3, 1], // Game 1
            [2, 2], // Game 2
        ];

        foreach ($matches as $index => $match) {
            $score1 = $scores[$index][0];
            $score2 = $scores[$index][1];
            $winner_id = null;

            if ($score1 > $score2) {
                $winner_id = $match->team1_id;
            } elseif ($score2 > $score1) {
                $winner_id = $match->team2_id;
            }

            $match->update([
                'team1_score' => $score1,
                'team2_score' => $score2,
                'winner_id' => $winner_id,
                'status' => 'played',
            ]);
        }

        // Recalculate standings for this group
        $this->recalculateGroupStandings($groupId);
    }

    private function recalculateGroupStandings($groupId)
    {
        // Reset
        Standing::where('group_id', $groupId)->update([
            'played' => 0,
            'won' => 0,
            'drawn' => 0,
            'lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goals_difference' => 0,
            'points' => 0,
        ]);

        $matches = TournamentMatch::where('group_id', $groupId)
            ->where('round', 'group')
            ->where('status', 'played')
            ->get();

        foreach ($matches as $match) {
            $t1 = $match->team1_id;
            $t2 = $match->team2_id;
            $s1 = $match->team1_score;
            $s2 = $match->team2_score;

            // Team 1
            $st1 = Standing::where('group_id', $groupId)->where('team_id', $t1)->first();
            if ($st1) {
                $st1->played += 1;
                $st1->goals_for += $s1;
                $st1->goals_against += $s2;
                $st1->goals_difference = $st1->goals_for - $st1->goals_against;

                if ($s1 > $s2) {
                    $st1->won += 1;
                    $st1->points += 3;
                } elseif ($s1 == $s2) {
                    $st1->drawn += 1;
                    $st1->points += 1;
                } else {
                    $st1->lost += 1;
                }
                $st1->save();
            }

            // Team 2
            $st2 = Standing::where('group_id', $groupId)->where('team_id', $t2)->first();
            if ($st2) {
                $st2->played += 1;
                $st2->goals_for += $s2;
                $st2->goals_against += $s1;
                $st2->goals_difference = $st2->goals_for - $st2->goals_against;

                if ($s2 > $s1) {
                    $st2->won += 1;
                    $st2->points += 3;
                } elseif ($s1 == $s2) {
                    $st2->drawn += 1;
                    $st2->points += 1;
                } else {
                    $st2->lost += 1;
                }
                $st2->save();
            }
        }
    }
}
