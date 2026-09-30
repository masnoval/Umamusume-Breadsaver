<?php

declare(strict_types=1);

class Produk
{
    public function __construct(private PDO $db)
    {
    }

    public function getAll(?int $staffId = null): array
    {
        if ($staffId !== null) {
            $stmt = $this->db->prepare(
                'SELECT
                    p.*,
                    s.nama AS nama_staff,
                    COALESCE(AVG(r.bintang), 0) AS rating
                 FROM produk p
                 JOIN staff s ON s.id_user = p.id_user
                 LEFT JOIN memberi_nilai r ON r.id_produk = p.id_produk
                 WHERE p.id_user = ?
                 GROUP BY p.id_produk
                 ORDER BY p.id_produk DESC'
            );

            $stmt->execute([$staffId]);

            return $stmt->fetchAll();
        }

        $stmt = $this->db->query(
            'SELECT
                p.*,
                s.nama AS nama_staff,
                COALESCE(AVG(r.bintang), 0) AS rating
             FROM produk p
             JOIN staff s ON s.id_user = p.id_user
             LEFT JOIN memberi_nilai r ON r.id_produk = p.id_produk
             GROUP BY p.id_produk
             ORDER BY p.id_produk DESC'
        );

        return $stmt->fetchAll();
    }

    public function getAktif(?string $search = null): array
    {
        $sql = '
            SELECT
                p.*,
                s.nama AS nama_staff,
                COALESCE(AVG(r.bintang), 0) AS rating
            FROM produk p
            JOIN staff s ON s.id_user = p.id_user
            LEFT JOIN memberi_nilai r ON r.id_produk = p.id_produk
            WHERE p.status = "Aktif"
              AND p.stok > 0
              AND p.kadaluarsa > NOW()
        ';

        $params = [];

        if ($search !== null && trim($search) !== '') {
            $sql .= '
                AND (
                    p.nama_produk LIKE ?
                    OR p.kategori LIKE ?
                )
            ';

            $term = '%' . trim($search) . '%';

            $params[] = $term;
            $params[] = $term;
        }

        $sql .= '
            GROUP BY p.id_produk
            ORDER BY p.kadaluarsa ASC, p.id_produk DESC
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT
                p.*,
                s.nama AS nama_staff,
                COALESCE(AVG(r.bintang), 0) AS rating
             FROM produk p
             JOIN staff s ON s.id_user = p.id_user
             LEFT JOIN memberi_nilai r ON r.id_produk = p.id_produk
             WHERE p.id_produk = ?
             GROUP BY p.id_produk'
        );

        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function create(
        int $staffId,
        string $nama,
        string $kategori,
        float $hargaAsli,
        float $hargaDiskon,
        int $stok,
        string $status,
        string $kadaluarsa,
        ?string $foto
    ): int {
        if ($hargaDiskon > $hargaAsli) {
            throw new Exception(
                'Harga diskon tidak boleh lebih besar dari harga asli.'
            );
        }

        if ($stok < 1) {
            throw new Exception('Stok minimal 1.');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO produk
            (
                id_user,
                nama_produk,
                kategori,
                harga_asli,
                harga_diskon,
                stok,
                status,
                kadaluarsa,
                foto
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $staffId,
            $nama,
            $kategori,
            $hargaAsli,
            $hargaDiskon,
            $stok,
            $status,
            $kadaluarsa,
            $foto
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(
        int $id,
        int $staffId,
        string $nama,
        string $kategori,
        float $hargaAsli,
        float $hargaDiskon,
        int $stok,
        string $status,
        string $kadaluarsa,
        ?string $foto = null
    ): bool {
        if ($hargaDiskon > $hargaAsli) {
            throw new Exception(
                'Harga diskon tidak boleh lebih besar dari harga asli.'
            );
        }

        $old = $this->getById($id);

        if (!$old || (int)$old['id_user'] !== $staffId) {
            throw new Exception('Produk tidak ditemukan.');
        }

        if ($foto !== null) {
            $stmt = $this->db->prepare(
                'UPDATE produk
                 SET nama_produk = ?,
                     kategori = ?,
                     harga_asli = ?,
                     harga_diskon = ?,
                     stok = ?,
                     status = ?,
                     kadaluarsa = ?,
                     foto = ?
                 WHERE id_produk = ?
                   AND id_user = ?'
            );

            return $stmt->execute([
                $nama,
                $kategori,
                $hargaAsli,
                $hargaDiskon,
                $stok,
                $status,
                $kadaluarsa,
                $foto,
                $id,
                $staffId
            ]);
        }

        $stmt = $this->db->prepare(
            'UPDATE produk
             SET nama_produk = ?,
                 kategori = ?,
                 harga_asli = ?,
                 harga_diskon = ?,
                 stok = ?,
                 status = ?,
                 kadaluarsa = ?
             WHERE id_produk = ?
               AND id_user = ?'
        );

        return $stmt->execute([
            $nama,
            $kategori,
            $hargaAsli,
            $hargaDiskon,
            $stok,
            $status,
            $kadaluarsa,
            $id,
            $staffId
        ]);
    }

    public function delete(int $id, int $staffId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*)
             FROM jual
             WHERE id_produk = ?
               AND status IN ("Menunggu Konfirmasi", "Diproses")'
        );

        $stmt->execute([$id]);

        if ((int)$stmt->fetchColumn() > 0) {
            throw new Exception(
                'Produk tidak dapat dihapus karena masih memiliki pesanan aktif.'
            );
        }

        $stmt = $this->db->prepare(
            'DELETE FROM produk
             WHERE id_produk = ?
               AND id_user = ?'
        );

        return $stmt->execute([$id, $staffId]);
    }
}
