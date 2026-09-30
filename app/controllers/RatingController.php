<?php

declare(strict_types=1);

class RatingController
{
    private RatingUlasan $rating;
    private Pesanan $pesanan;

    public function __construct(PDO $db)
    {
        $this->rating = new RatingUlasan($db);
        $this->pesanan = new Pesanan($db);
    }

    public function index(int $produkId): void
    {
        require_login();

        $produk = (new Produk(
            (new Database())->connect()
        ))->getById($produkId);

        if (!$produk) {
            flash('error', 'Produk tidak ditemukan.');
            redirect('/');
        }

        view('rating/index', [
            'title' => 'Ulasan Produk',
            'produk' => $produk,
            'rating' => $this->rating->getProduk($produkId),
            'rataRata' => $this->rating->rataRata($produkId)
        ]);
    }

    public function create(): void
    {
        require_customer();

        $pesananId = (int)($_POST['id_pesanan'] ?? 0);
        $bintang = (int)($_POST['bintang'] ?? 0);
        $ulasan = trim($_POST['ulasan'] ?? '');

        try {
            $this->rating->create(
                (int)current_user()['id'],
                $pesananId,
                $bintang,
                $ulasan
            );

            flash(
                'success',
                'Rating dan ulasan berhasil disimpan.'
            );
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
        }

        redirect('/pesanan');
    }
}
