@extends('layouts.app')

@section('content')
    <h1>Buat Postingan Baru</h1>
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <label>Judul:</label>
        <input type="text" name="title" required>
        <br>
        <label>Isi:</label>
        <textarea name="content" required></textarea>
        <br>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('posts.index') }}">Kembali ke daftar postingan</a>
@endsection
