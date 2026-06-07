<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Turnamen Futsal')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @auth
        <div class="app-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-brand">
                    <div class="sidebar-logo">F</div>
                    <span class="sidebar-title">FUTSAL MANAGER</span>
                </div>
                
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->isAdmin())
                        <li class="sidebar-menu-item {{ Request::routeIs('teams.*') ? 'active' : '' }}">
                            <a href="{{ route('teams.index') }}">
                                <i class="fa-solid fa-people-group"></i>
                                <span>Tim Futsal</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::routeIs('players.*') ? 'active' : '' }}">
                            <a href="{{ route('players.index') }}">
                                <i class="fa-solid fa-user-group"></i>
                                <span>Pemain</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::routeIs('tournaments.*') && !Request::routeIs('tournaments.show') && !Request::routeIs('tournaments.knockout') ? 'active' : '' }}">
                            <a href="{{ route('tournaments.index') }}">
                                <i class="fa-solid fa-trophy"></i>
                                <span>Turnamen</span>
                            </a>
                        </li>
                    @endif

                    <!-- Shared show/matches view for both Roles -->
                    @if(\App\Models\Tournament::where('status', 'ongoing')->exists())
                        @php $firstOngoing = \App\Models\Tournament::where('status', 'ongoing')->first(); @endphp
                        <li class="sidebar-menu-item {{ Request::routeIs('tournaments.show') && Request::route('tournament')?->id == $firstOngoing->id ? 'active' : '' }}">
                            <a href="{{ route('tournaments.show', $firstOngoing->id) }}">
                                <i class="fa-solid fa-circle-play"></i>
                                <span>Jadwal & Skor</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::routeIs('tournaments.knockout') && Request::route('tournament')?->id == $firstOngoing->id ? 'active' : '' }}">
                            <a href="{{ route('tournaments.knockout', $firstOngoing->id) }}">
                                <i class="fa-solid fa-sitemap"></i>
                                <span>Bagan Knockout</span>
                            </a>
                        </li>
                    @else
                        @php $firstTourney = \App\Models\Tournament::first(); @endphp
                        @if($firstTourney)
                            <li class="sidebar-menu-item {{ Request::routeIs('tournaments.show') ? 'active' : '' }}">
                                <a href="{{ route('tournaments.show', $firstTourney->id) }}">
                                    <i class="fa-solid fa-circle-play"></i>
                                    <span>Jadwal & Skor</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    
                    <li class="sidebar-menu-item">
                        <a href="{{ route('landing') }}" target="_blank">
                            <i class="fa-solid fa-globe"></i>
                            <span>Halaman Publik</span>
                        </a>
                    </li>
                </ul>
                
                <div class="sidebar-footer">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Panitia' }}</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success" id="alert-msg">
                        <span><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</span>
                        <button class="alert-close" onclick="document.getElementById('alert-msg').style.display='none'">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" id="alert-msg">
                        <span><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</span>
                        <button class="alert-close" onclick="document.getElementById('alert-msg').style.display='none'">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    @else
        <!-- For Guest Pages -->
        @yield('content')
    @endauth
</body>
</html>
