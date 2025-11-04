@extends('layouts.main')

@section('title', 'Detail FAQ | Chatter Box')

@section('content')
<style>
    body {
        background: #fff !important;
    }
    .dashboard-content {
        background: #fff !important;
    }
    .faq-back {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #40A09C;
        text-decoration: none;
        font-size: 0.95em;
        margin-bottom: 20px;
        margin-top: 40px; /* agar tidak menempel ke atas */
    }
    .faq-back:hover {
        text-decoration: underline;
    }
    .faq-container {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 25px 30px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        color: #37474f;
        max-width: 900px;
        margin: 20px auto 0 auto; /* jarak dari atas */
    }
    .faq-question {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.3em;
        color: #37474f;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .faq-question i {
        color: #40A09C;
        font-size: 1.3em;
    }
    .faq-answer ol {
        margin-left: 20px;
        line-height: 1.7em;
        color: #37474f;
    }
    .faq-footer {
        text-align: center;
        margin-top: 60px; /* agak turun dari container */
        font-size: 0.95em;
        color: #666;
        margin-bottom: 40px; /* beri jarak dari bawah layar */
    }
    .faq-footer a {
        background: #40A09C;
        color: #fff;
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 8px;
        transition: background 0.2s ease;
    }
    .faq-footer a:hover {
        background: #2f7f7a;
    }
</style>

<a href="{{ route('faq.index') }}" class="faq-back">
    <i class="fa fa-arrow-left"></i> Kembali ke FAQ
</a>

<div class="faq-container">
    {{-- Pertanyaan --}}
    <div class="faq-question">
        <i class="fa 
        @switch($id)
            @case(0) fa-id-card @break
            @case(1) fa-envelope @break
            @case(2) fa-trash @break
            @case(3) fa-pen @break
            @case(4) fa-lock @break
            @case(5) fa-key @break
            @case(6) fa-wrench @break
            @case(7) fa-exclamation-triangle @break
            @case(8) fa-headset @break
            @case(9) fa-info-circle @break
        @endswitch
        "></i>
        {{ $faq['question'] }}
    </div>

    {{-- Jawaban --}}
    <div class="faq-answer">
        @switch($id)
            @case(0)
                <ol>
                    <li>Masuk ke halaman profil Anda.</li>
                    <li>Klik tombol "Edit Profil".</li>
                    <li>Ganti nama pada kolom "Nama Pengguna".</li>
                    <li>Klik "Simpan Perubahan".</li>
                </ol>
                @break

            @case(1)
                <ol>
                    <li>Buka menu Pengaturan.</li>
                    <li>Pilih bagian "Akun" → "Email".</li>
                    <li>Masukkan alamat email baru Anda.</li>
                    <li>Klik "Simpan" dan lakukan verifikasi jika diminta.</li>
                </ol>
                @break

            @case(2)
                <ol>
                    <li>Buka postingan yang ingin dihapus.</li>
                    <li>Klik ikon titik tiga di pojok kanan atas.</li>
                    <li>Pilih "Hapus Postingan".</li>
                    <li>Konfirmasi penghapusan saat diminta.</li>
                </ol>
                @break

            @case(3)
                <ol>
                    <li>Pilih postingan yang ingin Anda ubah.</li>
                    <li>Klik ikon pensil (Edit).</li>
                    <li>Ubah isi atau judul postingan.</li>
                    <li>Klik "Simpan Perubahan".</li>
                </ol>
                @break

            @case(4)
                <ol>
                    <li>Buka menu Pengaturan.</li>
                    <li>Pilih "Keamanan" → "Ubah Kata Sandi".</li>
                    <li>Masukkan kata sandi lama dan baru.</li>
                    <li>Klik "Simpan".</li>
                </ol>
                @break

            @case(5)
                <ol>
                    <li>Masuk ke menu Pengaturan.</li>
                    <li>Pilih “Keamanan Akun”.</li>
                    <li>Aktifkan opsi “Verifikasi Dua Langkah”.</li>
                    <li>Ikuti petunjuk untuk menautkan nomor telepon Anda.</li>
                </ol>
                @break

            @case(6)
                <ol>
                    <li>Buka menu “Bantuan & Kontak”.</li>
                    <li>Pilih opsi “Laporkan Bug”.</li>
                    <li>Isi formulir laporan beserta deskripsi masalahnya.</li>
                    <li>Kirim laporan dan tunggu tanggapan tim teknis.</li>
                </ol>
                @break

            @case(7)
                <ol>
                    <li>Pastikan koneksi internet Anda stabil.</li>
                    <li>Periksa kembali email dan kata sandi.</li>
                    <li>Jika lupa kata sandi, gunakan fitur “Lupa Password”.</li>
                    <li>Hubungi admin jika masalah berlanjut.</li>
                </ol>
                @break

            @case(8)
                <ol>
                    <li>Buka halaman “Bantuan & Kontak”.</li>
                    <li>Klik tombol “Hubungi Kami”.</li>
                    <li>Pilih saluran komunikasi (Email / WhatsApp).</li>
                    <li>Kirim pertanyaan Anda ke admin.</li>
                </ol>
                @break

            @case(9)
                <ol>
                    <li>Ya, terdapat pusat bantuan resmi melalui menu “Bantuan & Kontak”.</li>
                    <li>Kami juga menyediakan halaman FAQ dan dukungan chat admin.</li>
                    <li>Klik tombol “Hubungi Kami” untuk informasi lebih lanjut.</li>
                </ol>
                @break
        @endswitch
    </div>
</div>

<div class="faq-footer">
    Masih butuh bantuan?<br>
    <a href="#">💬 Hubungi Kami</a>
</div>
@endsection
