@extends('layouts.app')

@section('content')
    <h1>Daftar Postingan</h1>
    <a href="{{ route('posts.create') }}">Tambah Postingan Baru</a>
    <table>
        <tr>
            <th>Judul</th>
            <th>Aksi</th>
        </tr>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>
                    <a href="{{ route('posts.show', $post->id) }}">Lihat</a>
                    <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus postingan ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
