<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FAQController extends Controller
{
     private array $faqData = [
        [
            'question' => 'Bagaimana cara mengubah nama profil?',
        ],
        [
            'question' => 'Bagaimana cara mengganti alamat email?',
        ],
        [
            'question' => 'Bagaimana cara menghapus postingan?',
        ],
        [
            'question' => 'Bagaimana cara mengedit postingan?',
        ],
        [
            'question' => 'Bagaimana cara mengatur ulang kata sandi?',
        ],
        [
            'question' => 'Mengapa saya tidak bisa login?',
        ],
        [
            'question' => 'Bagaimana cara menghubungi admin?',
        ],
        [
            'question' => 'Apakah ada pusat bantuan resmi?',
        ],
    ];

    public function index()
    {
        $faqData = $this->faqData;
        return view('faq.index', compact('faqData'));
    }

    public function show($id)
{
    // Pastikan ID valid agar tidak error
    if (!isset($this->faqData[$id])) {
        abort(404, 'FAQ tidak ditemukan');
    }

    $faq = $this->faqData[$id];

    // Kirim $faq dan $id ke view
    return view('faq.show', compact('faq', 'id'));
}
};