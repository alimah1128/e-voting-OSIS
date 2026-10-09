@extends('landing.layout')
@section('title', 'Pemilihan - OSIS SMK Pertiwi Kuningan')

@push('styles')
<style>
    .head { text-align:center; margin-bottom:30px; }
    .head h1 { color:var(--navy); font-size:clamp(22px,4vw,32px); }
    .head p { color:#64748b; margin-top:6px; }
    .choices { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:24px; max-width:700px; margin:0 auto; }
    .choice { text-align:center; text-decoration:none; color:inherit; transition:.25s; display:block; }
    .choice:hover { transform:translateY(-6px); box-shadow:0 14px 32px rgba(2,132,199,.18); }
    .icon { width:72px; height:72px; margin:0 auto 16px; border-radius:50%;
            background:#e0f2fe; display:flex; align-items:center; justify-content:center; font-size:32px; }
    .choice h3 { color:var(--navy); margin-bottom:6px; }
    .choice p { color:#64748b; font-size:13px; margin-bottom:18px; }
    .choice .go { display:inline-block; padding:10px 24px; border-radius:999px; color:#fff; font-weight:600; font-size:14px;
                  background:linear-gradient(135deg,#00b4db,#0083b0); }
</style>
@endpush

@section('content')
<div class="container">
    <div class="head">
        <h1>E-Voting Pemilihan Ketua OSIS</h1>
        <p>Silakan pilih jenis akun untuk masuk</p>
    </div>

    <div class="choices">
        <a href="{{ route('login') }}" class="card choice">
            <div class="icon">🎓</div>
            <h3>Login sebagai Siswa</h3>
            <p>Gunakan akun yang diberikan panitia OSIS untuk memberikan suaramu.</p>
            <span class="go">Masuk sebagai Siswa</span>
        </a>

        <a href="{{ route('admin.login') }}" class="card choice">
            <div class="icon">🛡️</div>
            <h3>Login sebagai Admin</h3>
            <p>Khusus panitia untuk mengelola kandidat, data siswa, dan hasil.</p>
            <span class="go">Masuk sebagai Admin</span>
        </a>
    </div>
</div>
@endsection 