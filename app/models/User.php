<?php

declare(strict_types=1);

class User
{
    public function __construct(private PDO $db)
    {
    }

    public function customerByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM customer WHERE email = ? LIMIT 1'
        );

        $stmt->execute([$email]);

        return $stmt->fetch() ?: null;
    }

    public function staffByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM staff WHERE email = ? LIMIT 1'
        );

        $stmt->execute([$email]);

        return $stmt->fetch() ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            'SELECT
                (SELECT COUNT(*) FROM customer WHERE email = ?) +
                (SELECT COUNT(*) FROM staff WHERE email = ?) AS total'
        );

        $stmt->execute([$email, $email]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function registerCustomer(
        string $nama,
        string $email,
        string $password,
        string $noHp,
        string $alamat
    ): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            'INSERT INTO customer
            (nama, password, no_hp, alamat, email)
            VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $nama,
            $hash,
            $noHp ?: null,
            $alamat ?: null,
            $email
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function getCustomer(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM customer WHERE id_customer = ?'
        );

        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function getStaff(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM staff WHERE id_user = ?'
        );

        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }
}
