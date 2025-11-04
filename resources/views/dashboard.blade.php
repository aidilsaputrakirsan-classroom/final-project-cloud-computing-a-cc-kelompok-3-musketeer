@extends('layouts.main')

@section('title', 'Dashboard | Chatter Box')

@section('content')
<div class="dashboard-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
    <input type="text" class="search-bar" placeholder="Cari postingan ngtren saat ini?" style="width:270px;padding:7px 10px;border:1px solid #ddd;border-radius:7px;">
    <div class="user-info" style="display:flex;align-items:center;gap:15px;">
        <i class="fa fa-bell" style="color:#aaa;cursor:pointer;"></i>
        <div class="user-avatar" style="width:32px;height:32px;border-radius:50%;background:url('{{ asset('avatar.jpg') }}') center/cover;"></div>
    </div>
</div>

<div class="content-list-controls" style="display:flex;gap:7px;margin-bottom:12px;">
    <button style="background:#e7f6fa;color:#40a09c;border:none;padding:6px 17px;border-radius:16px;">Baru</button>
    <button style="background:#e7f6fa;color:#40a09c;border:none;padding:6px 17px;border-radius:16px;"><i class="fa fa-filter"></i> Kategori</button>
    <button style="background:#40A09C;color:white;border:none;padding:7px 17px;border-radius:7px;margin-left:auto;">+ Buat Postingan</button>
</div>

<div class="cards-list" style="display:flex;flex-direction:column;gap:25px;">
    <div class="post-card" style="background:white;border-radius:7px;box-shadow:0 1px 6px rgba(0,0,0,0.05);padding:20px;">
        <div class="post-title" style="font-weight:600;color:#4b5d6b;">Contoh Postingan Dashboard</div>
        <p style="color:#314057;">Isi postingan atau konten dashboard di sini.</p>
    </div>
</div>
@endsection
