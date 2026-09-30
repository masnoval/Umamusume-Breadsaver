<?php

declare(strict_types=1);

class TokoRoti
{
    public function __construct(private PDO $db)
    {
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT
                id_user,
                nama,
                email,
                no_hp,
                alamat
             FROM staff
             ORDER BY nama ASC'
        );

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT
                id_user,
                nama,
                email,
                no_hp,
                alamat
             FROM staff
             WHERE id_user = ?'
        );

        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }
}
