<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Komentar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-3xl mx-auto">

        <!-- POSTINGAN -->
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-gray-300"></div>
                <div>
                    <h2 class="font-semibold text-gray-800">Liliani Natsir</h2>
                    <p class="text-sm text-gray-500">09:00 pm</p>
                </div>
            </div>

            <p class="text-gray-700 leading-relaxed mb-4">
                Itu bukan hukum alam, tapi hasil konstruksi budaya dan sejarah. Kalau kamu bayangin manusia prasejarah, mereka makan kapan aja begitu ada makanan.
                Nggak ada konsep sarapan, makan siang, apalagi "dinner date". Jadi pertanyaanmu valid, karena memang kebiasaan ini ada asal-usulnya, bukan bawaan lahir.
            </p>

            <div class="flex gap-4 text-gray-600 text-sm mt-4">
                <span>👁️ 60</span>
                <span>💬 3</span>
                <span>👍 3</span>
            </div>
        </div>

        <!-- FORM KOMENTAR -->
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <textarea class="w-full border rounded-lg p-3 mb-4 focus:outline-none focus:ring" placeholder="Tambahkan komentar..."></textarea>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kirim</button>
        </div>

        <!-- DAFTAR KOMENTAR -->
        <div class="bg-white shadow rounded-xl p-6">
            <h3 class="font-semibold text-gray-800 mb-6">Komentar</h3>

            <!-- KOMENTAR 1 -->
            <div class="flex gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-gray-300"></div>
                <div>
                    <p class="font-semibold text-gray-700">Dita Karang <span class="text-sm text-gray-500">09:50 pm</span></p>
                    <p class="text-gray-700 mb-2">Kalau kita makan pagi dan sore, tidak akan ada istirahat makan siang 😌</p>
                    <button class="text-blue-600 text-sm hover:underline">Balas</button>
                </div>
            </div>

            <!-- KOMENTAR 2 -->
            <div class="flex gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-300"></div>
                <div>
                    <p class="font-semibold text-gray-700">Blonde Jeli <span class="text-sm text-gray-500">10:00 pm</span></p>
                    <p class="text-gray-700 mb-2">Aah.. Lagi2 berawal di Inggris 🇬🇧, pantas lah full English breakfast disebut sebagai sarapannya working class 🐝</p>
                    <button class="text-blue-600 text-sm hover:underline">Balas</button>
                </div>
            </div>

        </div>

    </div>
</body>
</html>
