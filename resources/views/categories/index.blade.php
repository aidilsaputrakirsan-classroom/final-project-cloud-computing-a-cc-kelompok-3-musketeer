@extends('layouts.main')

@section('title', 'Jelajahi Topik | Chatter Box')

@section('content')

@php
    $colors = [
        '#4AA0C0', '#5AA76C', '#C8A53A', '#E05D4A', '#8BB9E0',
        '#9CC77B', '#F29263', '#7B6EE6', '#D39FB6', '#6DD7AA',
        '#e67e22', '#3498db', '#16a085', '#9b59b6', '#f39c12',
        '#e74c3c', '#1abc9c', '#8e44ad', '#27ae60', '#2980b9'
    ];
@endphp

<div style="padding:24px;">
    <h1 style="font-size:2rem;margin-bottom:18px;">Jelajahi Topik</h1>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;">
        @foreach($categories as $index => $category)
            @php
                // Pilih warna berdasarkan index, ulangi jika lebih banyak kategori daripada warna
                $color = $colors[$index % count($colors)];
            @endphp

            <a href="{{ route('categories.show', $category->slug) }}" style="text-decoration:none;">
                <div class="category-card" data-slug="{{ $category->slug }}" style="background: {{ $color }};">
                    <div style="font-weight:700;font-size:1.05rem;">{{ $category->name }}</div>
                    <div style="font-weight:600;opacity:0.95;text-align:right;">{{ $category->posts_count ?? 0 }} Postingan</div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<style>
.category-card {
    padding:22px;
    border-radius:12px;
    min-height:75px; 
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    color:#fff;
    box-shadow:0 4px 10px rgba(0,0,0,0.06);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    text-decoration: none;
}

/* efek hover halus */
.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.12);
}

/* pastikan link <a> tidak merusak tampilan */
a { color: inherit; }
a:hover { text-decoration: none; }
</style>
@endsection
