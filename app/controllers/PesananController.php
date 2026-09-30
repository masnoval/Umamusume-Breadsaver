<?php

declare(strict_types=1);

class PesananController
{
    private Pesanan $pesanan;
    private Produk $produk;

    public function __construct(PDO $db)
    {
        $this->pesanan = new Pesanan($db);
        $this->produk = new Produk($db);
    }

    public function index(): void
    {
        require_login();

        if (current_user()['type'] === 'customer') {
            $data = $this->pesanan->getByCustomer(
                (int)current_user()['id']
            );
        } else {
            $data = $this->pesanan->getAll(
                (int)current_user()['id']
            );
        }

        view('pesanan/index', [
            'title' => 'Pesanan',
            'pesanan' => $data
        ]);
    }

    public function createForm(?int $produkId = null): void
    {
        require_customer();

        $produk = null;

        if ($produkId !== null) {
            $produk = $this->produk->getById($produkId);
        }

        if (!$produk) {
            flash('error', 'Produk tidak ditemukan.');
            redirect('/katalog');
        }

        view('pesanan/create', [
            'title' => 'Pesan Produk',
            'produk' => $produk
        ]);
    }

    public function create(): void
    {
        require_customer();

        $produkId = (int)($_POST['id_produk'] ?? 0);
        $jumlah = (int)($_POST['jumlah'] ?? 0);

        try {
            $kode = $this->pesanan->buat(
                (int)current_user()['id'],
                $produkId,
                $jumlah
            );

            flash(
                'success',
                'Pesanan berhasil dibuat. Kode: ' . $kode
            );

            redirect('/pesanan');
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
            redirect('/pesanan/tambah?id=' . $produkId);
        }
    }

    public function detail(int $id): void
    {
        require_login();

        $pesanan = $this->pesanan->getById($id);

        if (!$pesanan) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/pesanan');
        }

        if (
            current_user()['type'] === 'customer' &&
            (int)$pesanan['id_customer'] !==
            (int)current_user()['id']
        ) {
            flash('error', 'Anda tidak memiliki akses.');
            redirect('/pesanan');
        }

        if (
            current_user()['type'] === 'staff' &&
            $this->staffOwnsOrder($pesanan) === false
        ) {
            flash('error', 'Anda tidak memiliki akses.');
            redirect('/pesanan');
        }

        view('pesanan/detail', [
            'title' => 'Detail Pesanan',
            'pesanan' => $pesanan
        ]);
    }

    public function updateStatus(int $id): void
    {
        require_staff();

        $status = $_POST['status'] ?? '';

        try {
            $this->pesanan->updateStatus(
                $id,
                $status,
                (int)current_user()['id']
            );

            flash(
                'success',
                'Status pesanan berhasil diperbarui.'
            );
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
        }

        redirect('/pesanan');
    }

    public function cancel(int $id): void
    {
        require_customer();

        try {
            $this->pesanan->batalkan(
                $id,
                (int)current_user()['id']
            );

            flash(
                'success',
                'Pesanan berhasil dibatalkan.'
            );
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
        }

        redirect('/pesanan');
    }

    private function staffOwnsOrder(array $pesanan): bool
    {
        $produk = $this->produk->getById(
            (int)$pesanan['id_produk']
        );

        return $produk &&
            (int)$produk['id_user'] ===
            (int)current_user()['id'];
    }
}
