@extends('layouts.app')

@section('content')
    <h1>Edit Postingan</h1>
    <form method="POST" action="{{ route('posts.update', $post->id) }}">
        @csrf
        @method('PUT')
        <label>Judul:</label>
        <input type="text" name="title" value="{{ $post->title }}" required>
        <br>
        <label>Isi:</label>
        <textarea name="content" required>{{ $post->content }}</textarea>
        <br>
        <button type="submit">Update</button>
    </form>
    <a href="{{ route('posts.index') }}">Kembali ke daftar postingan</a>
@endsection
