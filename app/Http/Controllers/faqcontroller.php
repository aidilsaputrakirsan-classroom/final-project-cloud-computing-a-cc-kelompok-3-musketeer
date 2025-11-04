<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Data FAQ — disusun per kategori.
     * Kamu bisa ubah, tambah, atau pindahkan ke model/database nanti.
     */
    private array $faqData = [
        'akun' => [
            'ubah-nama' => [
                'title' => 'Bagaimana cara mengubah nama profil?',
                'icon' => 'bi-person-fill',
                'steps' => [
                    'Masuk ke halaman profil Anda.',
                    'Klik tombol "Edit Profil".',
                    'Ganti nama pada kolom "Nama Pengguna".',
                    'Klik "Simpan Perubahan".'
                ],
            ],
            'ganti-email' => [
                'title' => 'Bagaimana cara mengganti alamat email?',
                'icon' => 'bi-envelope-fill',
                'steps' => [
                    'Masuk ke Pengaturan Akun.',
                    'Pilih tab "Keamanan Akun".',
                    'Klik "Ubah Email".',
                    'Masukkan email baru dan lakukan verifikasi.'
                ],
            ],
        ],

        'postingan' => [
            'hapus-postingan' => [
                'title' => 'Bagaimana cara menghapus postingan?',
                'icon' => 'bi-trash3-fill',
                'steps' => [
                    'Buka halaman profil Anda.',
                    'Klik pada postingan yang ingin dihapus.',
                    'Pilih ikon titik tiga di pojok kanan atas.',
                    'Klik "Hapus Postingan" dan konfirmasi tindakan Anda.'
                ],
            ],
            'edit-postingan' => [
                'title' => 'Bagaimana cara mengedit postingan?',
                'icon' => 'bi-pencil-square',
                'steps' => [
                    'Masuk ke profil Anda.',
                    'Klik postingan yang ingin diedit.',
                    'Pilih "Edit Postingan".',
                    'Lakukan perubahan yang diinginkan lalu klik "Simpan".'
                ],
            ],
        ],

        'keamanan' => [
            'atur-ulang-kata-sandi' => [
                'title' => 'Bagaimana cara mengatur ulang kata sandi?',
                'icon' => 'bi-lock-fill',
                'steps' => [
                    'Buka halaman login.',
                    'Klik tautan "Lupa Kata Sandi?".',
                    'Masukkan alamat email Anda.',
                    'Buka email dan klik tautan untuk mengatur ulang kata sandi.'
                ],
            ],
            'verifikasi-dua-langkah' => [
                'title' => 'Apa itu verifikasi dua langkah?',
                'icon' => 'bi-shield-lock-fill',
                'steps' => [
                    'Fitur ini menambah keamanan akun Anda.',
                    'Setelah login, Anda akan diminta kode verifikasi tambahan.',
                    'Kode dikirim melalui SMS atau email.',
                    'Aktifkan fitur ini di menu "Keamanan Akun".'
                ],
            ],
        ],

        'kendala' => [
            'lapor-bug' => [
                'title' => 'Bagaimana melaporkan bug?',
                'icon' => 'bi-bug-fill',
                'steps' => [
                    'Masuk ke menu "Bantuan".',
                    'Klik "Laporkan Masalah".',
                    'Tuliskan deskripsi bug dan langkah yang menyebabkan error.',
                    'Klik "Kirim Laporan".'
                ],
            ],
            'tidak-bisa-login' => [
                'title' => 'Mengapa saya tidak bisa login?',
                'icon' => 'bi-exclamation-triangle-fill',
                'steps' => [
                    'Periksa kembali username dan password Anda.',
                    'Pastikan koneksi internet stabil.',
                    'Jika lupa kata sandi, gunakan fitur "Lupa Kata Sandi".',
                    'Jika masih gagal, hubungi tim dukungan kami.'
                ],
            ],
        ],

        'kontak' => [
            'hubungi-admin' => [
                'title' => 'Bagaimana cara menghubungi admin?',
                'icon' => 'bi-telephone-fill',
                'steps' => [
                    'Buka halaman "Bantuan & FAQ".',
                    'Klik tombol "Hubungi Kami".',
                    'Tuliskan pesan Anda secara jelas dan sopan.'
                ],
            ],
            'pusat-bantuan' => [
                'title' => 'Apakah ada pusat bantuan resmi?',
                'icon' => 'bi-info-circle-fill',
                'steps' => [
                    'Ya, Anda bisa mengunjungi halaman resmi Bantuan kami.',
                    'Di sana terdapat panduan lengkap penggunaan aplikasi.',
                    'Tersedia juga daftar pertanyaan umum (FAQ).'
                ],
            ],
        ],
    ];

    /**
     * Menampilkan halaman daftar FAQ berdasarkan kategori.
     */
    public function index()
    {
        return view('faq.index', [
            'faqData' => $this->faqData
        ]);
    }

    /**
     * Menampilkan halaman detail dari satu pertanyaan.
     */
    public function show($slug)
    {
        foreach ($this->faqData as $kategori => $items) {
            if (isset($items[$slug])) {
                return view('faq.show', $items[$slug]);
            }
        }

        abort(404, 'Halaman FAQ tidak ditemukan');
    }
}
