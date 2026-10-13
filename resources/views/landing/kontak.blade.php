@extends('landing.layout')
@section('title', 'Kontak — OSIS SMK Pertiwi Kuningan')

@push('styles')
<style>
    .kontak-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:22px; }
    .kontak-item h3 { margin-bottom:6px; }
    .kontak-item p { white-space:normal; word-break:break-word; margin-bottom:16px; }
    .kontak-item .btn { padding:10px 22px; font-size:14px; }
</style>
@endpush

@section('content')

    @php
        $alamat = $p['kontak_alamat']    ?? '';
        $email  = $p['kontak_email']     ?? '';
        $ig     = $p['kontak_instagram'] ?? '';
        $wa     = $p['kontak_whatsapp']  ?? '';

        // WhatsApp: 08xxx -> 628xxx
        $waUrl = '';
        if ($wa !== '') {
            $nomor = preg_replace('/\D+/', '', $wa);
            if (\Illuminate\Support\Str::startsWith($nomor, '0')) { $nomor = '62' . substr($nomor, 1); }
            $waUrl = 'https://wa.me/' . $nomor;
        }

        // Instagram boleh diisi "@osis..." atau link lengkap
        $igUrl = '';
        if ($ig !== '') {
            $igUrl = \Illuminate\Support\Str::startsWith($ig, ['http://', 'https://'])
                ? $ig
                : 'https://instagram.com/' . ltrim($ig, '@');
        }
    @endphp

    @include('landing.partials.banner', [
        'judul' => 'Kontak',
        'sub'   => 'Punya pertanyaan, saran, atau ingin bekerja sama? Hubungi kami lewat salah satu cara di bawah.',
        'crumb' => 'Kontak',
    ])

    <div class="container">
        <section>
            <div class="kontak-grid">
                <div class="card hover kontak-item">
                    <div class="ikon">📍</div>
                    <h3>Alamat</h3>
                    <p>{{ $alamat !== '' ? $alamat : 'Belum diisi.' }}</p>
                </div>

                <div class="card hover kontak-item">
                    <div class="ikon">✉️</div>
                    <h3>Email</h3>
                    <p>{{ $email !== '' ? $email : 'Belum diisi.' }}</p>
                    @if($email !== '')
                        <a href="mailto:{{ $email }}" class="btn">Kirim email</a>
                    @endif
                </div>

                <div class="card hover kontak-item">
                    <div class="ikon">📷</div>
                    <h3>Instagram</h3>
                    <p>{{ $ig !== '' ? $ig : 'Belum diisi.' }}</p>
                    @if($igUrl !== '')
                        <a href="{{ $igUrl }}" target="_blank" rel="noopener" class="btn">Buka Instagram</a>
                    @endif
                </div>

                @if($wa !== '')
                <div class="card hover kontak-item">
                    <div class="ikon">💬</div>
                    <h3>WhatsApp</h3>
                    <p>{{ $wa }}</p>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn">Chat WhatsApp</a>
                </div>
                @endif
            </div>
        </section>
    </div>

@endsection