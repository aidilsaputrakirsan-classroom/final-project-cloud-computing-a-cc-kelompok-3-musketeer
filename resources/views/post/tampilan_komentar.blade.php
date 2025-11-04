@extends('layout.komentar')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-2xl p-6">
    <div class="flex items-center mb-4">
        <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full mr-3">
        <div>
            <h3 class="font-semibold">Liliani Natsir</h3>
            <p class="text-sm text-gray-500">09:00 pm</p>
        </div>
    </div>
    <h2 class="text-lg font-semibold mb-2">Dari Mana Asalnya Kebiasaan Makan 3 kali sehari?</h2>
    <p class="text-gray-700 mb-4">
        Itu bukan hukum alam, tapi hasil konstruksi budaya dan sejarah...
    </p>
    <div class="flex space-x-2 mb-6">
        <button class="bg-teal-500 text-white px-3 py-1 rounded-full text-sm">Slice Of Life</button>
        <button class="bg-teal-500 text-white px-3 py-1 rounded-full text-sm">Random</button>
    </div>

    {{-- Tambahkan komentar --}}
    <textarea class="w-full p-3 border rounded-2xl mb-4" placeholder="Tambahkan komentar..."></textarea>
    <button class="bg-teal-600 text-white px-4 py-2 rounded-2xl">Kirim</button>

    {{-- Daftar komentar --}}
    <div class="mt-6">
        <h4 class="font-semibold mb-3">Komentar</h4>
        <div class="space-y-4">
            <div class="border-t pt-3">
                <p class="text-sm text-gray-700"><span class="font-semibold">Dita Karang</span> : Kalau kita makan pagi dan sore...</p>
                <button class="text-sm text-teal-600 font-medium">Balas</button>
            </div>
            <div class="border-t pt-3">
                <p class="text-sm text-gray-700"><span class="font-semibold">Blonde Jeli</span> : Aah.. Lagi2 berawal di Inggris 🇬🇧</p>
                <button class="text-sm text-teal-600 font-medium">Balas</button>
            </div>
        </div>
    </div>
</div>
@endsection