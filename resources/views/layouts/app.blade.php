<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | NetReport</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --sidebar-w: 260px;
            --bg: #0f172a;
            --sidebar: #1a2744;
            --card: #1e293b;
            --border: #334155;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #3b82f6;
            --accent2: #1d4ed8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .sidebar-logo .app-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .sidebar-logo .app-sub {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 2px;
        }

        .sidebar-user {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .sidebar-user .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d4ed8, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .sidebar-user .uname {
            font-size: 0.82rem;
            font-weight: 600;
        }

        .sidebar-user .urole {
            font-size: 0.68rem;
            color: #94a3b8;
        }

        .role-badge {
            display: inline-block;
            font-size: 0.6rem;
            padding: 1px 7px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-admin {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .role-manager {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .nav-section {
            padding: 10px 14px 4px;
            font-size: 0.62rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            font-size: 0.82rem;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active {
            color: #fff;
            background: rgba(59, 130, 246, 0.12);
            border-left-color: var(--accent);
        }

        .nav-item i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .sidebar-footer form button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            color: #f87171;
            font-size: 0.82rem;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s;
        }

        .sidebar-footer form button:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* ── Main Layout ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-heading h1 {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .breadcrumb {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 2px;
        }

        .clock {
            font-size: 0.78rem;
            color: var(--muted);
            background: rgba(255, 255, 255, 0.05);
            padding: 5px 12px;
            border-radius: 20px;
            font-variant-numeric: tabular-nums;
        }

        .content {
            padding: 24px 28px;
            flex: 1;
        }

        /* ── Alert ── */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        /* ── Cards ── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 20px;
        }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-label {
            font-size: 0.72rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stat-value {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-sub {
            font-size: 0.72rem;
            color: #64748b;
        }

        .icon-bg {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        /* ── Grid ── */
        .grid {
            display: grid;
            gap: 18px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        /* ── Table ── */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.83rem;
        }

        thead tr {
            background: rgba(255, 255, 255, 0.04);
        }

        th {
            padding: 10px 14px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 11px 14px;
            border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            vertical-align: middle;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .badge-info {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .badge-secondary {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
        }

        /* ── Grade Big Badge ── */
        .grade-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.4rem;
            font-weight: 900;
        }

        .grade-A {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .grade-B {
            background: rgba(6, 182, 212, 0.2);
            color: #06b6d4;
        }

        .grade-C {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .grade-D {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-sm {
            padding: 5px 11px;
            font-size: 0.76rem;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent2);
        }

        .btn-outline {
            background: transparent;
            color: var(--muted);
            border-color: var(--border);
        }

        .btn-outline:hover {
            color: #fff;
            border-color: #64748b;
        }

        .btn-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            background: rgba(245, 158, 11, 0.3);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .btn-success {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            background: rgba(16, 185, 129, 0.3);
        }

        /* ── Forms ── */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.85rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control option {
            background: #1e2d3d;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 90px;
        }

        .form-row {
            display: grid;
            gap: 16px;
        }

        .cols-2 {
            grid-template-columns: 1fr 1fr;
        }

        .cols-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .error-msg {
            color: #f87171;
            font-size: 0.73rem;
            margin-top: 4px;
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        /* ── Misc ── */
        .text-muted {
            color: var(--muted);
        }

        .mb-16 {
            margin-bottom: 16px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 20px 0;
        }

        .info-box {
            background: rgba(59, 130, 246, 0.07);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 10px;
            padding: 14px 16px;
        }

        .qos-section {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .qos-section h6 {
            font-size: 0.8rem;
            color: var(--accent);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .delta-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .delta-positive {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .delta-negative {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        @media (max-width: 900px) {
            .grid-4 {
                grid-template-columns: 1fr 1fr;
            }

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="sidebar-logo">
            <div style="display:flex;align-items:center;gap:10px">
                <div
                    style="width:32px;height:32px;background:linear-gradient(135deg,#1d4ed8,#06b6d4);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-router" style="color:#fff;font-size:1rem"></i>
                </div>
                <div>
                    <div class="app-name">NetReport</div>
                    <div class="app-sub">Network Report System</div>
                </div>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}</div>
            <div>
                <div class="uname">{{ auth()->user()->nama }}</div>
                <span class="role-badge role-{{ auth()->user()->role }}">{{ auth()->user()->role }}</span>
            </div>
        </div>

        {{-- Menu Admin --}}
        @if(auth()->user()->role === 'admin')
            <div class="nav-section">Utama</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <div class="nav-section">Jaringan</div>
            <a href="{{ route('admin.konfigurasi') }}"
                class="nav-item {{ request()->routeIs('admin.konfigurasi*') ? 'active' : '' }}">
                <i class="bi bi-gear-wide-connected"></i> Konfigurasi Jaringan
            </a>
            <a href="{{ route('admin.vlan.index') }}"
                class="nav-item {{ request()->routeIs('admin.vlan*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i> Manajemen VLAN
            </a>
            <div class="nav-section">Laporan</div>
            <a href="{{ route('admin.laporan.create') }}"
                class="nav-item {{ request()->routeIs('admin.laporan.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> Buat Laporan Harian
            </a>
            <a href="{{ route('admin.laporan.index') }}"
                class="nav-item {{ request()->routeIs('admin.laporan.index') || request()->routeIs('admin.laporan.show') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Riwayat Laporan
            </a>
            <a href="{{ route('admin.failover.index') }}"
                class="nav-item {{ request()->routeIs('admin.failover*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Log Failover ISP
            </a>
        @endif

        {{-- Menu Manager --}}
        @if(auth()->user()->role === 'manager')
            <div class="nav-section">Utama</div>
            <a href="{{ route('manager.dashboard') }}"
                class="nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <div class="nav-section">Laporan</div>
            <a href="{{ route('manager.laporan') }}"
                class="nav-item {{ request()->routeIs('manager.laporan*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Riwayat Laporan
            </a>
            <a href="{{ route('manager.failover') }}"
                class="nav-item {{ request()->routeIs('manager.failover') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Log Failover ISP
            </a>
            <div class="nav-section">Info</div>
            <a href="{{ route('manager.konfigurasi') }}"
                class="nav-item {{ request()->routeIs('manager.konfigurasi') ? 'active' : '' }}">
                <i class="bi bi-gear-wide-connected"></i> Konfigurasi Jaringan
            </a>
        @endif

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="bi bi-box-arrow-left"></i> Logout</button>
            </form>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="main">
        <div class="topbar">
            <div class="page-heading">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="breadcrumb"><i class="bi bi-house"></i> Home › @yield('breadcrumb', 'Dashboard')</div>
            </div>
            <div id="clock" class="clock">13:00:00</div>
        </div>
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script>
        // Live clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent =
                now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        updateClock(); setInterval(updateClock, 1000);
    </script>
    @stack('scripts')
</body>

</html>