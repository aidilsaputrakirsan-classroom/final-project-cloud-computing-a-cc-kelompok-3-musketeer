@extends('layouts.main')

@section('title', 'Bantuan & FAQ | Chatter Box')

@section('content')
<style>
    body {
        background: #fff !important;
    }
    .dashboard-content {
        background: #fff !important;
    }
    h2 {
        color:#40A09C;
        margin-bottom:25px;
    }
    .faq-section {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px 25px;
        margin-bottom: 25px;
    }
    .faq-section h3 {
        color: #40A09C;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }
    .faq-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .faq-item {
        flex: 1 1 calc(50% - 10px);
        background: #fafafa;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #37474f;
        font-size: 0.96em;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .faq-item i {
        color: #40A09C;
        font-size: 1.1em;
    }
    .faq-item:hover {
        background: #e7f6f8;
        border-color: #bde2e3;
        transform: translateY(-2px);
    }
    .faq-footer {
        text-align: center;
        margin-top: 25px;
        font-size: 0.95em;
        color: #666;
    }
    .faq-footer a {
        background: #40A09C;
        color: #fff;
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 8px;
    }
    .faq-footer a:hover {
        background: #2f7f7a;
    }
</style>

<h2>Bantuan & FAQ</h2>

{{-- Akun & Profil --}}
<div class="faq-section">
    <h3><i class="fa fa-user-circle"></i> Akun & Profil</h3>
    <div class="faq-list">
        <a href="{{ route('faq.show', 0) }}" class="faq-item"><i class="fa fa-id-card"></i> Bagaimana cara mengubah nama profil?</a>
        <a href="{{ route('faq.show', 1) }}" class="faq-item"><i class="fa fa-envelope"></i> Bagaimana cara mengganti alamat email?</a>
    </div>
</div>

{{-- Postingan & Komentar --}}
<div class="faq-section">
    <h3><i class="fa fa-edit"></i> Postingan & Komentar</h3>
    <div class="faq-list">
        <a href="{{ route('faq.show', 2) }}" class="faq-item"><i class="fa fa-trash"></i> Bagaimana cara menghapus postingan?</a>
        <a href="{{ route('faq.show', 3) }}" class="faq-item"><i class="fa fa-pen"></i> Bagaimana cara mengedit postingan?</a>
    </div>
</div>

{{-- Pengaturan & Keamanan --}}
<div class="faq-section">
    <h3><i class="fa fa-shield-alt"></i> Pengaturan & Keamanan</h3>
    <div class="faq-list">
        <a href="{{ route('faq.show', 4) }}" class="faq-item"><i class="fa fa-lock"></i> Bagaimana cara mengatur ulang kata sandi?</a>
    </div>
</div>

{{-- Kendala Teknis --}}
<div class="faq-section">
    <h3><i class="fa fa-bug"></i> Kendala Teknis</h3>
    <div class="faq-list">
        <a href="{{ route('faq.show', 5) }}" class="faq-item"><i class="fa fa-exclamation-triangle"></i> Mengapa saya tidak bisa login?</a>
    </div>
</div>

{{-- Bantuan & Kontak --}}
<div class="faq-section">
    <h3><i class="fa fa-phone"></i> Bantuan & Kontak</h3>
    <div class="faq-list">
        <a href="{{ route('faq.show', 6) }}" class="faq-item"><i class="fa fa-headset"></i> Bagaimana cara menghubungi admin?</a>
        <a href="{{ route('faq.show', 7) }}" class="faq-item"><i class="fa fa-info-circle"></i> Apakah ada pusat bantuan resmi?</a>
    </div>
</div>

<div class="faq-footer">
    Masih butuh bantuan?<br>
    <a href="https://wa.me/6283140266116" target="_blank">💬 Hubungi Kami</a>
</div>
@endsection
