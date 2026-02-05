<?php
namespace App\Controllers;

use App\Core\Controller;

class BlogController extends Controller {
    public function index() {
        // Mock data for blog posts
        $posts = [
            [
                'title' => 'UV DTF Baskı Teknolojisi Nedir?',
                'slug' => 'uv-dtf-baski-teknolojisi-nedir',
                'image' => 'https://placehold.co/800x400',
                'excerpt' => 'Dijital baskı dünyasında devrim yaratan UV DTF teknolojisini detaylarıyla inceliyoruz.',
                'date' => '01 Şubat 2026'
            ],
            [
                'title' => 'Eco Solvent Makine Alırken Dikkat Edilmesi Gerekenler',
                'slug' => 'eco-solvent-makine-alirken-dikkat',
                'image' => 'https://placehold.co/800x400',
                'excerpt' => 'İşletmeniz için en doğru eco solvent yazıcıyı seçmeniz için 5 altın kural.',
                'date' => '25 Ocak 2026'
            ],
            [
                'title' => 'Baskı Maliyetlerini Düşürmenin Yolları',
                'slug' => 'baski-maliyetlerini-dusurmenin-yollari',
                'image' => 'https://placehold.co/800x400',
                'excerpt' => 'Mürekkep ve malzeme tasarrufu ile karlılığınızı nasıl artırabilirsiniz?',
                'date' => '10 Ocak 2026'
            ]
        ];
        
        $this->view('blog/index', ['posts' => $posts]);
    }

    public function detail($slug) {
        // Simple view for detail
        $this->view('blog/detail', ['slug' => $slug]);
    }
}
