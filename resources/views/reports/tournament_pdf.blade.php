<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Turnamen - {{ $tournament->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 6px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 24px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            text-transform: uppercase;
        }
        .group-title {
            font-size: 12px;
            font-weight: bold;
            color: #0b0f19;
            margin-top: 14px;
            margin-bottom: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            background-color: #eee;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Resmi Turnamen Futsal</h1>
        <p class="font-bold">{{ $tournament->name }}</p>
        <p>Tanggal: {{ $tournament->start_date->format('d M Y') }} s/d {{ $tournament->end_date->format('d M Y') }} | Status: {{ strtoupper($tournament->status) }}</p>
    </div>

    <!-- Group Standings Section -->
    <div class="section-title">Klasemen Akhir Babak Grup</div>
    @forelse($tournament->groups as $group)
        <div class="group-title">{{ $group->name }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">Pos</th>
                    <th>Nama Tim</th>
                    <th style="width: 50px;" class="text-center">Main</th>
                    <th style="width: 50px;" class="text-center">Menang</th>
                    <th style="width: 50px;" class="text-center">Seri</th>
                    <th style="width: 50px;" class="text-center">Kalah</th>
                    <th style="width: 40px;" class="text-center">GF</th>
                    <th style="width: 40px;" class="text-center">GA</th>
                    <th style="width: 40px;" class="text-center">GD</th>
                    <th style="width: 50px;" class="text-center">Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($group->standings as $index => $standing)
                    <tr>
                        <td class="text-center font-bold">{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $standing->team->name }}</td>
                        <td class="text-center">{{ $standing->played }}</td>
                        <td class="text-center">{{ $standing->won }}</td>
                        <td class="text-center">{{ $standing->drawn }}</td>
                        <td class="text-center">{{ $standing->lost }}</td>
                        <td class="text-center">{{ $standing->goals_for }}</td>
                        <td class="text-center">{{ $standing->goals_against }}</td>
                        <td class="text-center">{{ $standing->goals_difference }}</td>
                        <td class="text-center font-bold" style="background-color: #fafafa;">{{ $standing->points }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada tim terdaftar di grup ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @empty
        <p class="text-center">Tidak ada grup terdaftar.</p>
    @endforelse

    <div class="page-break"></div>

    <!-- Match Results Section -->
    <div class="section-title">Hasil Pertandingan Babak Penyisihan Grup</div>
    <table>
        <thead>
            <tr>
                <th style="width: 110px;">Tanggal & Waktu</th>
                <th style="width: 60px;">Grup</th>
                <th class="text-right">Tim Home</th>
                <th style="width: 80px;" class="text-center">Skor</th>
                <th>Tim Away</th>
                <th style="width: 70px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groupMatches as $match)
                <tr>
                    <td>{{ $match->match_date->format('d M Y H:i') }}</td>
                    <td>{{ $match->group->name }}</td>
                    <td class="text-right font-bold">{{ $match->team1->name }}</td>
                    <td class="text-center font-bold" style="background-color: #fafafa;">
                        @if($match->status === 'played')
                            {{ $match->team1_score }} - {{ $match->team2_score }}
                        @else
                            VS
                        @endif
                    </td>
                    <td class="font-bold">{{ $match->team2->name }}</td>
                    <td class="text-center">
                        <span class="badge">{{ strtoupper($match->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada pertandingan penyisihan grup terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Knockout Stage Results Section -->
    <div class="section-title">Hasil Pertandingan Babak Knockout</div>
    <table>
        <thead>
            <tr>
                <th style="width: 110px;">Tanggal & Waktu</th>
                <th style="width: 90px;">Babak</th>
                <th class="text-right">Tim 1</th>
                <th style="width: 80px;" class="text-center">Skor</th>
                <th>Tim 2</th>
                <th>Pemenang (Lolos)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $knockoutMatches = array_merge(
                    $quarterfinals->all(),
                    $semifinals->all(),
                    $final ? [$final] : []
                );
            @endphp
            @forelse($knockoutMatches as $match)
                <tr>
                    <td>{{ $match->match_date->format('d M Y H:i') }}</td>
                    <td class="font-bold">
                        @if($match->round === 'quarterfinal')
                            Perempat Final
                        @elseif($match->round === 'semifinal')
                            Semifinal
                        @else
                            Final
                        @endif
                    </td>
                    <td class="text-right font-bold">{{ $match->team1->name }}</td>
                    <td class="text-center font-bold" style="background-color: #fafafa;">
                        @if($match->status === 'played')
                            {{ $match->team1_score }} - {{ $match->team2_score }}
                        @else
                            VS
                        @endif
                    </td>
                    <td class="font-bold">{{ $match->team2->name }}</td>
                    <td class="font-bold" style="color: green;">
                        @if($match->status === 'played' && $match->winner)
                            {{ $match->winner->name }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada pertandingan babak knockout terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Champion Box -->
    @if($final && $final->status === 'played' && $final->winner)
        <div style="border: 2px solid #333; background-color: #fcf8e3; padding: 12px; margin-top: 30px; text-align: center; border-radius: 6px;">
            <div style="font-size: 14px; font-weight: bold; margin-bottom: 6px; text-transform: uppercase;">🏆 JUARA TURNAMEN 🏆</div>
            <div style="font-size: 18px; font-weight: bold; color: #b91c1c;">{{ $final->winner->name }}</div>
            <div style="font-size: 11px; margin-top: 4px; color: #555;">Pelatih: {{ $final->winner->coach_name }}</div>
        </div>
    @endif

</body>
</html>
