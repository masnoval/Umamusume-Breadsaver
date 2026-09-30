<?php

declare(strict_types=1);

class LaporanController
{
    private Pesanan $pesanan;

    public function __construct(PDO $db)
    {
        $this->pesanan = new Pesanan($db);
    }

    public function index(): void
    {
        require_staff();

        $staffId = (int)current_user()['id'];

        view('laporan/index', [
            'title' => 'Laporan Penjualan',
            'statistik' => $this->pesanan->statistik($staffId),
            'laporan' => $this->pesanan->laporan($staffId)
        ]);
    }
}
