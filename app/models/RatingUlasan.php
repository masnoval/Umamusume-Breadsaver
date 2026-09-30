<?php

declare(strict_types=1);

class RatingUlasan
{
    public function __construct(private PDO $db)
    {
    }

    public function sudahAda(
        int $customerId,
        int $pesananId
    ): bool {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*)
             FROM memberi_nilai
             WHERE id_customer = ?
               AND id_pesanan = ?'
        );

        $stmt->execute([
            $customerId,
            $pesananId
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(
        int $customerId,
        int $pesananId,
        int $bintang,
        string $ulasan
    ): bool {
        if ($bintang < 1 || $bintang > 5) {
            throw new Exception('Rating harus antara 1 sampai 5.');
        }

        $stmt = $this->db->prepare(
            'SELECT *
             FROM jual
             WHERE id_pesanan = ?
               AND id_customer = ?
               AND status = "Selesai"'
        );

        $stmt->execute([
            $pesananId,
            $customerId
        ]);

        $pesanan = $stmt->fetch();

        if (!$pesanan) {
            throw new Exception(
                'Rating hanya dapat diberikan setelah transaksi selesai.'
            );
        }

        if ($this->sudahAda($customerId, $pesananId)) {
            throw new Exception(
                'Pesanan ini sudah diberi rating.'
            );
        }

        $stmt = $this->db->prepare(
            'INSERT INTO memberi_nilai
            (
                id_customer,
                id_produk,
                id_pesanan,
                bintang,
                ulasan
            )
            VALUES (?, ?, ?, ?, ?)'
        );

        return $stmt->execute([
            $customerId,
            $pesanan['id_produk'],
            $pesananId,
            $bintang,
            trim($ulasan) ?: null
        ]);
    }

    public function getProduk(int $produkId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                r.*,
                c.nama AS nama_customer
             FROM memberi_nilai r
             JOIN customer c ON c.id_customer = r.id_customer
             WHERE r.id_produk = ?
             ORDER BY r.tanggal DESC'
        );

        $stmt->execute([$produkId]);

        return $stmt->fetchAll();
    }

    public function rataRata(int $produkId): float
    {
        $stmt = $this->db->prepare(
            'SELECT COALESCE(AVG(bintang), 0)
             FROM memberi_nilai
             WHERE id_produk = ?'
        );

        $stmt->execute([$produkId]);

        return round((float)$stmt->fetchColumn(), 1);
    }
}
