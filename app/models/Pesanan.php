<?php

declare(strict_types=1);

class Pesanan
{
    public function __construct(private PDO $db)
    {
    }

    public function getByCustomer(int $customerId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                j.*,
                p.nama_produk,
                p.kategori,
                p.foto,
                s.nama AS nama_staff,
                EXISTS(
                    SELECT 1
                    FROM memberi_nilai r
                    WHERE r.id_pesanan = j.id_pesanan
                ) AS sudah_rating
             FROM jual j
             JOIN produk p ON p.id_produk = j.id_produk
             JOIN staff s ON s.id_user = p.id_user
             WHERE j.id_customer = ?
             ORDER BY j.tanggal DESC'
        );

        $stmt->execute([$customerId]);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT
                j.*,
                c.nama AS nama_customer,
                c.email AS email_customer,
                c.no_hp,
                c.alamat,
                p.nama_produk,
                p.kategori,
                p.foto,
                s.nama AS nama_staff
             FROM jual j
             JOIN customer c ON c.id_customer = j.id_customer
             JOIN produk p ON p.id_produk = j.id_produk
             JOIN staff s ON s.id_user = p.id_user
             WHERE j.id_pesanan = ?'
        );

        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function getAll(?int $staffId = null): array
    {
        $sql = '
            SELECT
                j.*,
                c.nama AS nama_customer,
                p.nama_produk,
                s.nama AS nama_staff
            FROM jual j
            JOIN customer c ON c.id_customer = j.id_customer
            JOIN produk p ON p.id_produk = j.id_produk
            JOIN staff s ON s.id_user = p.id_user
        ';

        $params = [];

        if ($staffId !== null) {
            $sql .= ' WHERE p.id_user = ? ';
            $params[] = $staffId;
        }

        $sql .= ' ORDER BY j.tanggal DESC ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function buat(
        int $customerId,
        int $produkId,
        int $jumlah
    ): string {
        if ($jumlah < 1) {
            throw new Exception('Jumlah minimal 1.');
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'SELECT *
                 FROM produk
                 WHERE id_produk = ?
                 FOR UPDATE'
            );

            $stmt->execute([$produkId]);

            $produk = $stmt->fetch();

            if (!$produk) {
                throw new Exception('Produk tidak ditemukan.');
            }

            if ($produk['status'] !== 'Aktif') {
                throw new Exception('Produk sedang tidak tersedia.');
            }

            if ((int)$produk['stok'] < $jumlah) {
                throw new Exception('Stok produk tidak mencukupi.');
            }

            if (strtotime($produk['kadaluarsa']) <= time()) {
                throw new Exception('Produk sudah kadaluarsa.');
            }

            $harga = (float)$produk['harga_diskon'];
            $total = $harga * $jumlah;

            $kode = 'BS-' .
                date('YmdHis') .
                '-' .
                strtoupper(bin2hex(random_bytes(2)));

            $stmt = $this->db->prepare(
                'INSERT INTO jual
                (
                    id_customer,
                    id_produk,
                    kode_pesanan,
                    total_harga,
                    tanggal,
                    status,
                    harga_satuan,
                    jumlah
                )
                VALUES (?, ?, ?, ?, NOW(), "Menunggu Konfirmasi", ?, ?)'
            );

            $stmt->execute([
                $customerId,
                $produkId,
                $kode,
                $total,
                $harga,
                $jumlah
            ]);

            $stokBaru = (int)$produk['stok'] - $jumlah;

            $statusProduk = $stokBaru > 0 ? 'Aktif' : 'Habis';

            $stmt = $this->db->prepare(
                'UPDATE produk
                 SET stok = ?,
                     status = ?
                 WHERE id_produk = ?'
            );

            $stmt->execute([
                $stokBaru,
                $statusProduk,
                $produkId
            ]);

            $this->db->commit();

            return $kode;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function updateStatus(
        int $id,
        string $status,
        int $staffId
    ): bool {
        $allowed = [
            'Menunggu Konfirmasi',
            'Diproses',
            'Selesai',
            'Dibatalkan'
        ];

        if (!in_array($status, $allowed, true)) {
            throw new Exception('Status tidak valid.');
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'SELECT
                    j.*,
                    p.id_user
                 FROM jual j
                 JOIN produk p ON p.id_produk = j.id_produk
                 WHERE j.id_pesanan = ?
                   AND p.id_user = ?
                 FOR UPDATE'
            );

            $stmt->execute([$id, $staffId]);

            $pesanan = $stmt->fetch();

            if (!$pesanan) {
                throw new Exception('Pesanan tidak ditemukan.');
            }

            if (in_array(
                $pesanan['status'],
                ['Selesai', 'Dibatalkan'],
                true
            )) {
                throw new Exception(
                    'Pesanan sudah berstatus final.'
                );
            }

            if ($status === 'Dibatalkan') {
                $stmt = $this->db->prepare(
                    'UPDATE jual
                     SET status = "Dibatalkan"
                     WHERE id_pesanan = ?'
                );

                $stmt->execute([$id]);

                $stmt = $this->db->prepare(
                    'UPDATE produk
                     SET stok = stok + ?,
                         status = "Aktif"
                     WHERE id_produk = ?'
                );

                $stmt->execute([
                    (int)$pesanan['jumlah'],
                    (int)$pesanan['id_produk']
                ]);
            } else {
                $stmt = $this->db->prepare(
                    'UPDATE jual
                     SET status = ?
                     WHERE id_pesanan = ?'
                );

                $stmt->execute([$status, $id]);
            }

            $this->db->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function batalkan(
        int $id,
        int $customerId
    ): bool {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'SELECT *
                 FROM jual
                 WHERE id_pesanan = ?
                   AND id_customer = ?
                 FOR UPDATE'
            );

            $stmt->execute([
                $id,
                $customerId
            ]);

            $pesanan = $stmt->fetch();

            if (!$pesanan) {
                throw new Exception('Pesanan tidak ditemukan.');
            }

            if ($pesanan['status'] !== 'Menunggu Konfirmasi') {
                throw new Exception(
                    'Pesanan hanya dapat dibatalkan saat menunggu konfirmasi.'
                );
            }

            $stmt = $this->db->prepare(
                'UPDATE jual
                 SET status = "Dibatalkan"
                 WHERE id_pesanan = ?'
            );

            $stmt->execute([$id]);

            $stmt = $this->db->prepare(
                'UPDATE produk
                 SET stok = stok + ?,
                     status = "Aktif"
                 WHERE id_produk = ?'
            );

            $stmt->execute([
                (int)$pesanan['jumlah'],
                (int)$pesanan['id_produk']
            ]);

            $this->db->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function laporan(?int $staffId = null): array
    {
        $sql = '
            SELECT
                DATE(j.tanggal) AS tanggal,
                COUNT(*) AS jumlah_transaksi,
                SUM(j.jumlah) AS jumlah_produk,
                SUM(j.total_harga) AS total_penjualan
            FROM jual j
            JOIN produk p ON p.id_produk = j.id_produk
            WHERE j.status = "Selesai"
        ';

        $params = [];

        if ($staffId !== null) {
            $sql .= ' AND p.id_user = ? ';
            $params[] = $staffId;
        }

        $sql .= '
            GROUP BY DATE(j.tanggal)
            ORDER BY DATE(j.tanggal) DESC
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function statistik(?int $staffId = null): array
    {
        $where = '';
        $params = [];

        if ($staffId !== null) {
            $where = ' AND p.id_user = ? ';
            $params[] = $staffId;
        }

        $stmt = $this->db->prepare(
            'SELECT
                COUNT(*) AS transaksi,
                COALESCE(SUM(j.total_harga), 0) AS pendapatan,
                COALESCE(SUM(j.jumlah), 0) AS produk_terjual
             FROM jual j
             JOIN produk p ON p.id_produk = j.id_produk
             WHERE j.status = "Selesai"
             ' . $where
        );

        $stmt->execute($params);

        return $stmt->fetch() ?: [
            'transaksi' => 0,
            'pendapatan' => 0,
            'produk_terjual' => 0
        ];
    }
}
