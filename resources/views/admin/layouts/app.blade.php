<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vivah — Matrimony Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --rose:        #c8715a;
            --rose-light:  #f0b9ad;
            --rose-deep:   #a0503a;
            --gold:        #c9a86c;
            --gold-light:  #e8d5b0;
            --gold-dark:   #9a7a40;
            --cream:       #fdf8f3;
            --parchment:   #f5ede0;
            --ivory:       #fffcf8;
            --blush:       #fae9e3;
            --sidebar-bg:  #1c1410;
            --sidebar-surface: #2a1f19;
            --sidebar-text: #c8b49a;
            --sidebar-active: #f5ede0;
            --text-primary: #2c1a10;
            --text-secondary: #6b4c38;
            --text-muted:  #a08070;
            --border:      #e8d5c4;
            --card-bg:     #ffffff;
            --card-shadow: rgba(180,100,60,0.08);
            --navbar-bg:   #fffcf8;
            --bg-main:     #fdf6ee;
            --bg-secondary:#f7ede0;
            --success:     #4a8c6a;
            --warning:     #c9a86c;
            --danger:      #b84c4c;
            --info:        #5a7caa;
            --verified:    #4a8c6a;
            --premium-grad: linear-gradient(135deg, #c9a86c 0%, #e8d5b0 50%, #c9a86c 100%);
            --rose-grad:   linear-gradient(135deg, #c8715a 0%, #f0b9ad 100%);
        }
        [data-theme="dark"] {
            --cream:       #1a110c;
            --parchment:   #221610;
            --ivory:       #1a110c;
            --blush:       #2a1a14;
            --sidebar-bg:  #100a06;
            --sidebar-surface: #1e1208;
            --sidebar-text: #9a7a5a;
            --sidebar-active: #f0d8b8;
            --text-primary: #f0e0cc;
            --text-secondary: #c0956a;
            --text-muted:  #806050;
            --border:      #3a2a1e;
            --card-bg:     #221610;
            --card-shadow: rgba(0,0,0,0.3);
            --navbar-bg:   #1a110c;
            --bg-main:     #150e09;
            --bg-secondary:#1e1208;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* ── SIDEBAR ─────────────────────────────────────────── */
        .sidebar {
            position: fixed; top:0; bottom:0; left:0; z-index:1000;
            width: 268px;
            background: var(--sidebar-bg);
            overflow-y: auto; overflow-x: hidden;
            transition: transform .35s cubic-bezier(.4,0,.2,1);
        }
        .sidebar::-webkit-scrollbar { width:4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(200,113,90,.3); border-radius:4px; }

        .sidebar-header {
            padding: 2rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(200,113,90,.15);
            display: flex; align-items: center; gap: 1rem;
        }
        .logo-mark {
            width: 48px; height: 48px; border-radius: 14px; flex-shrink:0;
            background: var(--rose-grad);
            display: flex; align-items:center; justify-content:center;
            box-shadow: 0 6px 18px rgba(200,113,90,.4);
        }
        .logo-mark i { color:#fff; font-size:1.375rem; }
        .brand h4 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem; font-weight:700; letter-spacing:.5px;
            color: var(--sidebar-active); margin:0;
        }
        .brand span {
            font-size:.7rem; font-weight:600; text-transform:uppercase;
            letter-spacing:1.5px; color: var(--sidebar-text);
        }

        .nav-section { padding: 1.25rem 0; }
        .nav-section-title {
            font-size:.65rem; font-weight:700; text-transform:uppercase;
            letter-spacing:2px; color: rgba(200,180,154,.4);
            padding: 0 1.5rem; margin-bottom:.5rem;
        }
        .nav { list-style:none; }
        .nav-item { margin:.15rem 0; }
        .nav-link {
            color: var(--sidebar-text);
            padding: .8rem 1.5rem;
            display: flex; align-items:center; gap:.875rem;
            text-decoration:none;
            font-size:.875rem; font-weight:500;
            position:relative; border-radius:0;
            transition: all .2s ease;
        }
        .nav-link::after {
            content:''; position:absolute; left:0; top:50%; transform:translateY(-50%);
            width:3px; height:0; background:var(--rose-grad);
            border-radius:0 4px 4px 0; transition: height .2s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(200,113,90,.1);
            color: var(--sidebar-active);
        }
        .nav-link.active::after { height:60%; }
        .nav-link i { width:18px; text-align:center; font-size:1rem; }
        .nav-badge {
            margin-left:auto; padding:.2rem .55rem;
            background: rgba(200,113,90,.2); color: var(--rose);
            border-radius:10px; font-size:.7rem; font-weight:700;
        }
        .nav-badge.gold-badge {
            background: rgba(201,168,108,.2); color: var(--gold);
        }

        /* ── MAIN ────────────────────────────────────────────── */
        .main-content { margin-left:268px; min-height:100vh; }

        /* ── NAVBAR ──────────────────────────────────────────── */
        .navbar {
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--border);
            padding: .875rem 2rem;
            position: sticky; top:0; z-index:999;
            display: flex; align-items:center; justify-content:space-between;
        }
        .navbar-left { display:flex; align-items:center; gap:1rem; }
        .mobile-toggle {
            display:none; width:36px; height:36px; border-radius:8px;
            border:1px solid var(--border); background:var(--card-bg);
            color:var(--text-primary); cursor:pointer; align-items:center;
            justify-content:center; font-size:1.1rem;
        }
        .breadcrumb { display:flex; align-items:center; gap:.5rem; }
        .breadcrumb span { font-size:.8rem; color:var(--text-muted); }
        .breadcrumb .current { color:var(--rose); font-weight:600; }

        .navbar-right { display:flex; align-items:center; gap:.75rem; }
        .icon-btn {
            width:36px; height:36px; border-radius:9px;
            border:1px solid var(--border); background:var(--card-bg);
            color:var(--text-secondary); display:flex; align-items:center;
            justify-content:center; cursor:pointer; position:relative;
            transition:all .2s ease; font-size:.9rem;
        }
        .icon-btn:hover { background:var(--bg-secondary); transform:translateY(-1px); }
        .notif-dot {
            position:absolute; top:6px; right:6px;
            width:7px; height:7px; border-radius:50%;
            background:var(--rose); border:2px solid var(--navbar-bg);
        }
        .user-chip {
            display:flex; align-items:center; gap:.625rem;
            padding:.4rem .875rem .4rem .4rem;
            border:1px solid var(--border); border-radius:50px;
            background:var(--card-bg); cursor:pointer;
            transition:all .2s ease;
        }
        .user-chip:hover { background:var(--bg-secondary); }
        .avatar {
            width:28px; height:28px; border-radius:50%;
            background:var(--rose-grad);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:.75rem; font-weight:700;
        }
        .user-chip-name { font-size:.8rem; font-weight:600; color:var(--text-primary); }

        /* ── MAIN CONTENT ────────────────────────────────────── */
        main { padding: 2rem; }

        .page-header { margin-bottom:2rem; }
        .page-header-inner { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; }
        .page-eyebrow {
            font-size:.7rem; font-weight:700; text-transform:uppercase;
            letter-spacing:2px; color:var(--rose); margin-bottom:.35rem;
        }
        .page-title {
            font-family:'Cormorant Garamond',serif;
            font-size:2.5rem; font-weight:600; color:var(--text-primary);
            line-height:1; letter-spacing:-.5px;
        }
        .page-title em { font-style:italic; color:var(--rose); }
        .page-subtitle { margin-top:.35rem; font-size:.875rem; color:var(--text-muted); }
        .date-chip {
            padding:.5rem 1rem; border:1px solid var(--border);
            border-radius:50px; font-size:.8rem; color:var(--text-secondary);
            background:var(--card-bg); display:flex; align-items:center; gap:.5rem;
            white-space:nowrap;
        }
        .date-chip i { color:var(--rose); }

        /* ── STATS GRID ──────────────────────────────────────── */
        .stats-grid {
            display:grid;
            grid-template-columns: repeat(4,1fr);
            gap:1.25rem;
            margin-bottom:2rem;
        }
        .stat-card {
            background:var(--card-bg);
            border:1px solid var(--border);
            border-radius:18px;
            padding:1.5rem;
            box-shadow:0 2px 12px var(--card-shadow);
            position:relative; overflow:hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px var(--card-shadow); }
        .stat-card::before {
            content:''; position:absolute; top:0; left:0; right:0;
            height:3px; border-radius:18px 18px 0 0;
        }
        .stat-card.rose::before  { background:var(--rose-grad); }
        .stat-card.gold::before  { background:var(--premium-grad); }
        .stat-card.green::before { background:linear-gradient(135deg,#4a8c6a,#7dc4a0); }
        .stat-card.blue::before  { background:linear-gradient(135deg,#5a7caa,#90b0d8); }
        .stat-card.purple::before{ background:linear-gradient(135deg,#8a5aaa,#c090d8); }
        .stat-card.teal::before  { background:linear-gradient(135deg,#3a9090,#70c4c4); }

        .stat-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1rem; }
        .stat-icon {
            width:44px; height:44px; border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            font-size:1.2rem; color:#fff;
        }
        .stat-icon.rose   { background:var(--rose-grad); }
        .stat-icon.gold   { background:var(--premium-grad); }
        .stat-icon.green  { background:linear-gradient(135deg,#4a8c6a,#7dc4a0); }
        .stat-icon.blue   { background:linear-gradient(135deg,#5a7caa,#90b0d8); }
        .stat-icon.purple { background:linear-gradient(135deg,#8a5aaa,#c090d8); }
        .stat-icon.teal   { background:linear-gradient(135deg,#3a9090,#70c4c4); }

        .stat-trend {
            display:flex; align-items:center; gap:.25rem;
            font-size:.7rem; font-weight:700; padding:.25rem .5rem; border-radius:6px;
        }
        .trend-up   { background:rgba(74,140,106,.12); color:var(--success); }
        .trend-down { background:rgba(184,76,76,.12);  color:var(--danger); }
        .trend-neu  { background:rgba(90,124,170,.1);  color:var(--info); }

        .stat-label { font-size:.8rem; color:var(--text-muted); font-weight:500; margin-bottom:.35rem; }
        .stat-value { font-family:'Cormorant Garamond',serif; font-size:2.25rem; font-weight:700; color:var(--text-primary); line-height:1; }
        .stat-sub   { margin-top:.5rem; font-size:.75rem; color:var(--text-muted); }

        /* ── SECONDARY GRID ──────────────────────────────────── */
        .grid-2 { display:grid; grid-template-columns:1.4fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }

        /* ── CARD ────────────────────────────────────────────── */
        .card {
            background:var(--card-bg); border:1px solid var(--border);
            border-radius:18px; box-shadow:0 2px 12px var(--card-shadow);
            overflow:hidden;
        }
        .card-header {
            padding:1.25rem 1.5rem;
            border-bottom:1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between;
        }
        .card-title {
            font-family:'Cormorant Garamond',serif;
            font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0;
            display:flex; align-items:center; gap:.5rem;
        }
        .card-title i { font-size:1rem; color:var(--rose); }
        .card-body { padding:1.5rem; }
        .card-actions { display:flex; gap:.5rem; }

        /* ── BUTTONS ─────────────────────────────────────────── */
        .btn {
            padding:.55rem 1.125rem; border:none; border-radius:9px;
            font-family:'DM Sans',sans-serif; font-size:.8rem; font-weight:600;
            cursor:pointer; display:inline-flex; align-items:center; gap:.375rem;
            transition:all .2s ease; text-decoration:none;
        }
        .btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.12); }
        .btn-rose    { background:var(--rose-grad); color:#fff; }
        .btn-gold    { background:var(--premium-grad); color:var(--text-primary); }
        .btn-outline { background:transparent; border:1.5px solid var(--border); color:var(--text-secondary); }
        .btn-outline:hover { background:var(--bg-secondary); }
        .btn-sm  { padding:.4rem .875rem; font-size:.75rem; }
        .btn-ghost { background:transparent; border:none; color:var(--text-muted); padding:.4rem .6rem; }
        .btn-ghost:hover { color:var(--rose); background:var(--blush); }

        /* ── TABLE ───────────────────────────────────────────── */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead tr { background:var(--bg-secondary); }
        th {
            padding:.875rem 1.25rem;
            text-align:left; font-size:.7rem; font-weight:700;
            text-transform:uppercase; letter-spacing:1px;
            color:var(--text-muted); border-bottom:1px solid var(--border);
        }
        td {
            padding:.9rem 1.25rem;
            font-size:.8rem; color:var(--text-primary);
            border-bottom:1px solid var(--border);
            vertical-align:middle;
        }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover td { background:var(--bg-secondary); }

        .user-cell { display:flex; align-items:center; gap:.75rem; }
        .user-cell-avatar {
            width:34px; height:34px; border-radius:50%; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-size:.8rem; font-weight:700; color:#fff;
        }
        .user-cell-name  { font-weight:600; font-size:.825rem; line-height:1.3; }
        .user-cell-sub   { font-size:.72rem; color:var(--text-muted); }

        /* ── BADGE ───────────────────────────────────────────── */
        .badge {
            display:inline-flex; align-items:center; gap:.25rem;
            padding:.25rem .65rem; border-radius:50px;
            font-size:.7rem; font-weight:700;
        }
        .badge-active    { background:rgba(74,140,106,.12); color:var(--success); }
        .badge-pending   { background:rgba(201,168,108,.15); color:var(--gold-dark); }
        .badge-inactive  { background:rgba(160,128,112,.1);  color:var(--text-muted); }
        .badge-premium   { background:rgba(201,168,108,.2);  color:var(--gold-dark); }
        .badge-matched   { background:rgba(200,113,90,.12);  color:var(--rose); }
        .badge-verified  { background:rgba(74,140,106,.12);  color:var(--verified); }
        .badge-blocked   { background:rgba(184,76,76,.1);    color:var(--danger); }
        .badge-free      { background:rgba(90,124,170,.1);   color:var(--info); }

        /* ── DONUT CHART PLACEHOLDER ─────────────────────────── */
        .donut-wrap { display:flex; align-items:center; gap:1.5rem; }
        .donut {
            width:110px; height:110px; flex-shrink:0; border-radius:50%;
            background: conic-gradient(
                var(--rose) 0deg 158deg,
                var(--gold) 158deg 244deg,
                #5a7caa 244deg 302deg,
                #4a8c6a 302deg 360deg
            );
            display:flex; align-items:center; justify-content:center;
            position:relative;
        }
        .donut::after {
            content:''; position:absolute;
            width:70px; height:70px; border-radius:50%;
            background:var(--card-bg);
        }
        .donut-legend { flex:1; }
        .legend-item { display:flex; align-items:center; gap:.5rem; margin-bottom:.5rem; font-size:.78rem; }
        .legend-dot   { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .legend-label { color:var(--text-secondary); flex:1; }
        .legend-val   { font-weight:700; color:var(--text-primary); }

        /* ── MINI ACTIVITY ───────────────────────────────────── */
        .activity-list { display:flex; flex-direction:column; gap:.875rem; }
        .activity-item { display:flex; align-items:flex-start; gap:.875rem; }
        .activity-icon {
            width:32px; height:32px; border-radius:9px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-size:.875rem; color:#fff;
        }
        .activity-icon.rose   { background:rgba(200,113,90,.15); color:var(--rose); }
        .activity-icon.gold   { background:rgba(201,168,108,.15); color:var(--gold-dark); }
        .activity-icon.green  { background:rgba(74,140,106,.15);  color:var(--success); }
        .activity-icon.blue   { background:rgba(90,124,170,.15);  color:var(--info); }
        .activity-text  { flex:1; font-size:.8rem; color:var(--text-primary); line-height:1.5; }
        .activity-text strong { font-weight:600; }
        .activity-time  { font-size:.7rem; color:var(--text-muted); white-space:nowrap; }

        /* ── QUICK STATS PILLS ───────────────────────────────── */
        .pill-row { display:flex; gap:.75rem; flex-wrap:wrap; margin-bottom:2rem; }
        .pill {
            display:flex; align-items:center; gap:.5rem;
            padding:.5rem 1rem; border-radius:50px;
            background:var(--card-bg); border:1px solid var(--border);
            font-size:.78rem; font-weight:500; color:var(--text-secondary);
        }
        .pill i { font-size:.75rem; color:var(--rose); }
        .pill strong { color:var(--text-primary); }

        /* ── PROGRESS BAR ────────────────────────────────────── */
        .progress-row { margin-bottom:1rem; }
        .progress-top { display:flex; justify-content:space-between; margin-bottom:.35rem; }
        .progress-label { font-size:.8rem; color:var(--text-secondary); font-weight:500; }
        .progress-val   { font-size:.8rem; font-weight:700; color:var(--text-primary); }
        .progress-bar   { height:6px; background:var(--bg-secondary); border-radius:4px; overflow:hidden; }
        .progress-fill  { height:100%; border-radius:4px; }
        .fill-rose   { background:var(--rose-grad); }
        .fill-gold   { background:var(--premium-grad); }
        .fill-green  { background:linear-gradient(90deg,#4a8c6a,#7dc4a0); }
        .fill-blue   { background:linear-gradient(90deg,#5a7caa,#90b0d8); }

        /* ── DIVIDER ─────────────────────────────────────────── */
        .divider { border:none; border-top:1px solid var(--border); margin:1.25rem 0; }

        /* ── RESPONSIVE ──────────────────────────────────────── */
        @media (max-width:1280px) {
            .stats-grid { grid-template-columns:repeat(3,1fr); }
        }
        @media (max-width:1100px) {
            .stats-grid { grid-template-columns:repeat(2,1fr); }
            .grid-2, .grid-3 { grid-template-columns:1fr; }
        }
        @media (max-width:900px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-content { margin-left:0; }
            .mobile-toggle { display:flex; }
            .sidebar-overlay {
                display:none; position:fixed; inset:0;
                background:rgba(0,0,0,.5); z-index:999;
            }
            .sidebar-overlay.show { display:block; }
        }
        @media (max-width:600px) {
            .stats-grid { grid-template-columns:1fr; }
            main { padding:1rem; }
            .page-header-inner { flex-direction:column; align-items:flex-start; }
        }

        /* ── ORNAMENT / FLOURISH ─────────────────────────────── */
        .ornament {
            display:inline-block; font-style:italic;
            font-family:'Cormorant Garamond',serif;
            color:var(--rose); font-size:.875rem; opacity:.7;
        }

        /* ── TOAST ───────────────────────────────────────────── */
        #toastContainer {
            position:fixed; top:5rem; right:1.5rem; z-index:9999;
            display:flex; flex-direction:column; gap:.75rem;
            pointer-events:none;
        }
        .toast {
            pointer-events:auto;
            background:var(--card-bg); border:1px solid var(--border);
            border-radius:14px; padding:.875rem 1.25rem;
            box-shadow:0 8px 30px rgba(0,0,0,.15);
            display:flex; align-items:center; gap:.75rem;
            min-width:300px; animation:toastIn .3s ease;
        }
        @keyframes toastIn {
            from { transform:translateX(120%); opacity:0; }
            to   { transform:translateX(0);    opacity:1; }
        }
        .toast-ico { width:32px; height:32px; border-radius:8px;
            display:flex; align-items:center; justify-content:center; color:#fff; }
        .toast-ico.rose  { background:var(--rose-grad); }
        .toast-ico.gold  { background:var(--premium-grad); color:var(--text-primary); }
        .toast-ico.green { background:linear-gradient(135deg,#4a8c6a,#7dc4a0); }
        .toast-title { font-weight:700; font-size:.825rem; color:var(--text-primary); }
        .toast-msg   { font-size:.775rem; color:var(--text-muted); }
        .toast-close { margin-left:auto; background:none; border:none;
            color:var(--text-muted); cursor:pointer; font-size:1rem; }

        /* ── SECTION SEPARATOR ───────────────────────────────── */
        .section-sep {
            display:flex; align-items:center; gap:.875rem; margin:1.75rem 0 1.25rem;
        }
        .section-sep-line { flex:1; height:1px; background:var(--border); }
        .section-sep-label {
            font-size:.7rem; font-weight:700; text-transform:uppercase;
            letter-spacing:2px; color:var(--text-muted);
            white-space:nowrap;
        }

        /* animate on load */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .stats-grid .stat-card { animation: fadeUp .5s ease both; }
        .stats-grid .stat-card:nth-child(1) { animation-delay:.05s; }
        .stats-grid .stat-card:nth-child(2) { animation-delay:.10s; }
        .stats-grid .stat-card:nth-child(3) { animation-delay:.15s; }
        .stats-grid .stat-card:nth-child(4) { animation-delay:.20s; }
        .stats-grid .stat-card:nth-child(5) { animation-delay:.25s; }
        .stats-grid .stat-card:nth-child(6) { animation-delay:.30s; }
        .stats-grid .stat-card:nth-child(7) { animation-delay:.35s; }
        .stats-grid .stat-card:nth-child(8) { animation-delay:.40s; }

        .card { animation: fadeUp .5s ease both; animation-delay:.3s; }

        /* suppress the global transition on these specific props */
        a, button, .nav-link { transition: all .2s ease; }

        /* ── FORM ───────────────────────────────────────── */
        .form-card {
            max-width: 520px;
            margin: 2rem auto;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: .35rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: .7rem .9rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--card-bg);
            color: var(--text-primary);
            font-size: .85rem;
            transition: all .2s ease;
        }

        .form-control:focus {
            border-color: var(--rose);
            box-shadow: 0 0 0 2px rgba(200,113,90,.15);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
            font-size: .8rem;
        }

        .form-error {
            font-size: .7rem;
            color: var(--danger);
            margin-top: .25rem;
        }

        .form-actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            margin-bottom: .25rem;
        }

        .form-sub {
            font-size: .8rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
@auth
<div class="sidebar-overlay" id="overlay"></div>

<!-- SIDEBAR -->
  @include('admin.layouts.sidebar') 

<!-- MAIN -->
<div class="main-content">

    <!-- NAVBAR -->
 @include('admin.layouts.navbar')
@endauth
    <!-- CONTENT -->
    <main>

  @yield('content')

    </main>
    @auth
</div><!-- /main-content -->

<!-- TOAST CONTAINER -->
<div id="toastContainer"></div>
@endauth
<script>
    // ── THEME TOGGLE ──────────────────────────────────────────
    const html = document.documentElement;
    const themeBtn = document.getElementById('themeToggle');
    const saved = localStorage.getItem('theme') ||
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    html.setAttribute('data-theme', saved);
    updateIcon(saved);

    themeBtn.addEventListener('click', () => {
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        updateIcon(next);
    });

    function updateIcon(t) {
        themeBtn.querySelector('i').className = t === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    // ── MOBILE SIDEBAR ────────────────────────────────────────
    const menuToggle = document.getElementById('menuToggle');
    const sidebar    = document.getElementById('sidebar');
    const overlay    = document.getElementById('overlay');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });

    // ── DATE ──────────────────────────────────────────────────
    const d = new Date('2026-04-22');
    document.getElementById('currentDate').textContent =
        d.toLocaleDateString('en-IN', { weekday:'long', day:'numeric', month:'long', year:'numeric' });

    // ── TOAST ─────────────────────────────────────────────────
    function showToast(title, msg, type='rose') {
        const icons = { rose:'fa-heart', gold:'fa-crown', green:'fa-circle-check' };
        const t = document.createElement('div');
        t.className = 'toast';
        t.innerHTML = `
            <div class="toast-ico ${type}"><i class="fas ${icons[type]||'fa-info-circle'}"></i></div>
            <div style="flex:1"><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div>
            <button class="toast-close" onclick="this.closest('.toast').remove()">×</button>
        `;
        document.getElementById('toastContainer').appendChild(t);
        setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 4500);
    }

    // Welcome toast on load
    window.addEventListener('load', () => {
        setTimeout(() => showToast('Welcome back!', '24 verifications pending your review.', 'rose'), 800);
        setTimeout(() => showToast('Revenue Update', 'MRR crossed ₹4L this month 🎉', 'gold'), 2000);
    });

    // ── NAV ACTIVE STATE ──────────────────────────────────────
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ── VERIFY BUTTONS ────────────────────────────────────────
    document.querySelectorAll('.btn-rose').forEach(btn => {
        if (btn.textContent.trim() === 'Verify') {
            btn.addEventListener('click', function() {
                const row = this.closest('.activity-item');
                const name = row.querySelector('strong').textContent;
                row.style.opacity = '.4';
                showToast('Verified!', `${name}'s profile has been verified.`, 'green');
            });
        }
    });
</script>
</body>
</html>
