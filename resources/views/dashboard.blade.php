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

    {{-- Postingan 1 --}}
    <div class="post-card" style="background:white;border-radius:10px;box-shadow:0 1px 6px rgba(0,0,0,0.05);padding:20px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:5px;">
            <span style="font-weight:600;color:#f1a23c;">Sci-Fi Enthusiast</span>
            <span style="font-size:13px;color:#f1a23c;">Sci-Fi Enthusiast</span>
        </div>
        <div style="font-weight:600;font-size:16px;color:#314057;margin-bottom:3px;">Which of sci-fi's favourite technologies would you like to see become a reality?</div>
        <div style="color:#8b9aa6;font-size:13px;margin-bottom:6px;">09:00 pm</div>
        <p style="color:#314057;font-size:14px;margin-bottom:10px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Augue magna justo, volutpat, non amet massa viverra euismod id.</p>

        <div style="display:flex;align-items:center;gap:18px;margin-bottom:10px;">
            <span style="color:#60717d;font-size:13px;"><i class="fa fa-eye"></i> 60</span>
            <span style="color:#60717d;font-size:13px;"><i class="fa fa-comment"></i> 3</span>
            <span style="color:#60717d;font-size:13px;"><i class="fa fa-thumbs-up"></i> 3</span>
        </div>

        <div style="display:flex;gap:8px;">
            <button style="background:#e7f6fa;color:#40a09c;border:none;padding:5px 14px;border-radius:14px;font-size:13px;">Sci-fi</button>
            <button style="background:#e7f6fa;color:#40a09c;border:none;padding:5px 14px;border-radius:14px;font-size:13px;">Sci-fi</button>
        </div>
    </div>

    {{-- Postingan 2 --}}
    <div class="post-card" style="background:white;border-radius:10px;box-shadow:0 1px 6px rgba(0,0,0,0.05);padding:20px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:5px;">
            <span style="font-weight:600;color:#e38b2b;">User Lain</span>
            <span style="font-size:13px;color:#e38b2b;">User Lain</span>
        </div>
        <div style="font-weight:600;font-size:16px;color:#314057;margin-bottom:3px;">Something just happened</div>
        <div style="color:#8b9aa6;font-size:13px;margin-bottom:6px;">09:00 pm</div>
        <p style="color:#314057;font-size:14px;margin-bottom:10px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Augue magna justo, volutpat, non amet massa viverra euismod id.</p>

        <div style="display:flex;align-items:center;gap:18px;margin-bottom:10px;">
            <span style="color:#60717d;font-size:13px;"><i class="fa fa-eye"></i> 60</span>
            <span style="color:#60717d;font-size:13px;"><i class="fa fa-comment"></i> 3</span>
            <span style="color:#60717d;font-size:13px;"><i class="fa fa-thumbs-up"></i> 3</span>
        </div>

        <div style="display:flex;gap:8px;">
            <button style="background:#e7f6fa;color:#40a09c;border:none;padding:5px 14px;border-radius:14px;font-size:13px;">Sci-fi</button>
            <button style="background:#e7f6fa;color:#40a09c;border:none;padding:5px 14px;border-radius:14px;font-size:13px;">Sci-fi</button>
        </div>
    </div>

</div>
@endsection
