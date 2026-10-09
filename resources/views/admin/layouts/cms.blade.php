<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin') - OSIS SMK Pertiwi Kuningan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --side:#2d6a9f; --navy:#0f4c81; --bg:#f2f8fd; --line:#e2e8f0; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Poppins',sans-serif; background:var(--bg); color:#1e293b; display:flex; min-height:100vh; }

        .sidebar { width:240px; background:var(--side); color:#fff; display:flex; flex-direction:column;
                   position:fixed; inset:0 auto 0 0; z-index:60; transition:.25s; }
        .side-brand { display:flex; gap:10px; align-items:center; padding:18px; border-bottom:1px solid rgba(255,255,255,.15); }
        .side-brand img { height:48px; }
        .side-brand b { display:block; font-size:16px; } .side-brand small { opacity:.8; font-size:12px; }
        .side-nav { flex:1; padding:14px 10px; overflow-y:auto; }
        .side-nav a, .side-nav summary { display:block; color:#fff; text-decoration:none; padding:11px 14px;
                     border-radius:10px; font-size:14px; font-weight:500; cursor:pointer; margin-bottom:2px; list-style:none; }
        .side-nav summary::-webkit-details-marker { display:none; }
        .side-nav summary::after { content:'▾'; float:right; opacity:.7; }
        .side-nav details[open] summary::after { content:'▴'; }
        .side-nav details a { padding-left:34px; font-size:13px; opacity:.92; }
        .side-nav a:hover, .side-nav summary:hover { background:rgba(255,255,255,.12); }
        .side-nav a.on { background:rgba(255,255,255,.22); font-weight:600; }
        .side-foot { padding:12px 10px; border-top:1px solid rgba(255,255,255,.15); }
        .side-foot a { color:#fff; text-decoration:none; padding:10px 14px; display:block; font-size:14px; }

        .main { flex:1; margin-left:240px; min-width:0; }
        .topbar { background:#fff; padding:14px 28px; display:flex; align-items:center; gap:14px;
                  border-bottom:1px solid var(--line); position:sticky; top:0; z-index:40; }
        .topbar h1 { font-size:17px; color:var(--navy); }
        .topbar small { color:#64748b; display:block; font-size:12px; }
        .burger { display:none; background:none; border:none; font-size:24px; cursor:pointer; }
        .content { padding:28px; max-width:1100px; }

        .page-title { font-size:24px; color:var(--navy); font-weight:700; margin-bottom:4px; }
        .page-sub { color:#64748b; font-size:14px; margin-bottom:22px; }
        .card { background:#fff; border-radius:16px; padding:22px; box-shadow:0 4px 18px rgba(2,132,199,.07); margin-bottom:18px; }
        .grid4 { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin-bottom:18px; }
        .stat b { font-size:30px; display:block; } .stat span { color:#64748b; font-size:13px; }

        .btn { display:inline-block; padding:9px 18px; border-radius:10px; border:none; cursor:pointer; font-family:inherit;
               font-size:13px; font-weight:600; text-decoration:none; background:#e2e8f0; color:#334155; }
        .btn-primary { background:linear-gradient(135deg,#00b4db,#0083b0); color:#fff; }
        .btn-danger { background:#fee2e2; color:#b91c1c; }
        .alert { background:#dcfce7; color:#166534; padding:12px 16px; border-radius:12px; margin-bottom:18px; font-size:14px; }
        .err { background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:12px; margin-bottom:18px; font-size:13px; }

        table { width:100%; border-collapse:collapse; font-size:14px; }
        th { text-align:left; color:#64748b; font-size:12px; text-transform:uppercase; padding:10px; border-bottom:1px solid var(--line); }
        td { padding:12px 10px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
        .thumb { width:52px; height:52px; border-radius:10px; object-fit:cover; background:#e0f2fe;
                 display:flex; align-items:center; justify-content:center; font-size:24px; }
        .badge { padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; }
        .b-on { background:#dcfce7; color:#166534; } .b-off { background:#f1f5f9; color:#64748b; }
        .tbl-wrap { overflow-x:auto; }

        .fg { margin-bottom:16px; }
        .fg label { display:block; font-size:12px; font-weight:600; color:#475569; margin-bottom:6px; text-transform:uppercase; }
        .fg input[type=text], .fg input[type=email], .fg input[type=date], .fg input[type=number], .fg textarea {
            width:100%; padding:11px 14px; border:1px solid var(--line); border-radius:10px; font-family:inherit; font-size:14px; background:#f8fafc; }
        .fg textarea { min-height:110px; resize:vertical; }
        .fg input:focus, .fg textarea:focus { outline:none; border-color:#38bdf8; background:#fff; }
        .fg small { color:#94a3b8; font-size:12px; }
        .row2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .actions { display:flex; gap:8px; }

        @media (max-width:900px){
            .sidebar { transform:translateX(-100%); } .sidebar.open { transform:none; }
            .main { margin-left:0; } .burger { display:block; } .row2 { grid-template-columns:1fr; }
            .content { padding:18px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">@include('admin.partials.sidebar-cms')</aside>

    <div class="main">
        <header class="topbar">
            <button class="burger" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <div>
                <h1>OSIS SMK Pertiwi Kuningan</h1>
                <small>Panel Admin Website OSIS</small>
            </div>
        </header>

        <main class="content">
            @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
            @if($errors->any())
                <div class="err">@foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>