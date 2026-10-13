@extends('admin.layouts.cms')
@section('title', 'Kontak')

@section('content')
    @include('admin.partials.page-head', [
        'judul'   => 'Kontak',
        'sub'     => 'Alamat dan akun resmi OSIS yang tampil di halaman Kontak dan bagian bawah website.',
        'kembali' => true,
    ])

    <div class="two" style="gap:20px;align-items:start">
        <form class="card" method="POST" action="{{ route('admin.kontak.update') }}">
            @csrf
            <h3 style="margin-bottom:16px">Edit Kontak</h3>

            <div class="fg"><label class="lb" for="kontak_alamat">Alamat</label>
                <input type="text" id="kontak_alamat" name="kontak_alamat" value="{{ old('kontak_alamat', $data['kontak_alamat'] ?? '') }}" placeholder="Alamat sekolah"></div>

            <div class="row2">
                <div class="fg"><label class="lb" for="kontak_email">Email</label>
                    <input type="email" id="kontak_email" name="kontak_email" value="{{ old('kontak_email', $data['kontak_email'] ?? '') }}" placeholder="email OSIS"></div>
                <div class="fg"><label class="lb" for="kontak_instagram">Instagram</label>
                    <input type="text" id="kontak_instagram" name="kontak_instagram" value="{{ old('kontak_instagram', $data['kontak_instagram'] ?? '') }}" placeholder="@akun_instagram"></div>
            </div>

            <div class="fg"><label class="lb" for="kontak_whatsapp">WhatsApp (opsional)</label>
                <input type="text" id="kontak_whatsapp" name="kontak_whatsapp" value="{{ old('kontak_whatsapp', $data['kontak_whatsapp'] ?? '') }}" placeholder="08xxxxxxxxxx">
                <small>Boleh diawali 08 atau 62.</small></div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                <a class="btn" href="{{ route('admin.kontak') }}">Batal</a>
            </div>
        </form>

        {{-- pratinjau --}}
        <div class="card" style="background:#e4f2fb;box-shadow:none">
            <div style="font-size:11px;font-weight:600;color:var(--navy);text-transform:uppercase;letter-spacing:.04em;margin-bottom:12px">Pratinjau di website</div>
            <div style="background:#fff;border-radius:18px;padding:22px;text-align:center">
                <h3 style="font-size:20px;margin-bottom:10px">Kontak</h3>
                <p class="pv" id="pvAlamat">📍 <span></span></p>
                <p class="pv" id="pvEmail">✉️ <span></span></p>
                <p class="pv" id="pvIg">📷 <span></span></p>
                <p class="pv" id="pvWa">💬 <span></span></p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>.pv { color:#64748b; font-size:14px; line-height:1.9; word-break:break-word; }</style>
@endpush

@push('scripts')
<script>
    (function () {
        var peta = [
            ['kontak_alamat', 'pvAlamat', 'Alamat sekolah'],
            ['kontak_email', 'pvEmail', 'email OSIS'],
            ['kontak_instagram', 'pvIg', '@akun_instagram'],
            ['kontak_whatsapp', 'pvWa', null]   // opsional: disembunyikan kalau kosong
        ];
        peta.forEach(function (p) {
            var inp = document.getElementById(p[0]), box = document.getElementById(p[1]);
            function ubah() {
                var v = inp.value.trim();
                if (p[2] === null) { box.hidden = (v === ''); }
                box.querySelector('span').textContent = v !== '' ? v : (p[2] || '');
            }
            inp.addEventListener('input', ubah);
            ubah();
        });
    })();
</script>
@endpush