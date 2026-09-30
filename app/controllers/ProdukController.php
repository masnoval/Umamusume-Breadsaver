<?php

declare(strict_types=1);

class ProdukController
{
    private Produk $produk;

    public function __construct(PDO $db)
    {
        $this->produk = new Produk($db);
    }

    public function index(): void
    {
        require_staff();

        $staffId = (int)current_user()['id'];

        view('produk/index', [
            'title' => 'Kelola Produk',
            'produk' => $this->produk->getAll($staffId)
        ]);
    }

    public function createForm(): void
    {
        require_staff();

        view('produk/create', [
            'title' => 'Tambah Produk'
        ]);
    }

    public function create(): void
    {
        require_staff();

        try {
            $foto = $this->uploadFoto();

            $this->produk->create(
                (int)current_user()['id'],
                trim($_POST['nama_produk'] ?? ''),
                trim($_POST['kategori'] ?? ''),
                (float)($_POST['harga_asli'] ?? 0),
                (float)($_POST['harga_diskon'] ?? 0),
                (int)($_POST['stok'] ?? 0),
                $_POST['status'] ?? 'Aktif',
                $_POST['kadaluarsa'] ?? '',
                $foto
            );

            flash('success', 'Produk berhasil ditambahkan.');
            redirect('/produk');
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
            redirect('/produk/tambah');
        }
    }

    public function editForm(int $id): void
    {
        require_staff();

        $produk = $this->produk->getById($id);

        if (
            !$produk ||
            (int)$produk['id_user'] !== (int)current_user()['id']
        ) {
            flash('error', 'Produk tidak ditemukan.');
            redirect('/produk');
        }

        view('produk/edit', [
            'title' => 'Edit Produk',
            'produk' => $produk
        ]);
    }

    public function edit(int $id): void
    {
        require_staff();

        try {
            $foto = $this->uploadFoto();

            $this->produk->update(
                $id,
                (int)current_user()['id'],
                trim($_POST['nama_produk'] ?? ''),
                trim($_POST['kategori'] ?? ''),
                (float)($_POST['harga_asli'] ?? 0),
                (float)($_POST['harga_diskon'] ?? 0),
                (int)($_POST['stok'] ?? 0),
                $_POST['status'] ?? 'Aktif',
                $_POST['kadaluarsa'] ?? '',
                $foto
            );

            flash('success', 'Produk berhasil diperbarui.');
            redirect('/produk');
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
            redirect('/produk/edit?id=' . $id);
        }
    }

    public function delete(int $id): void
    {
        require_staff();

        try {
            $this->produk->delete(
                $id,
                (int)current_user()['id']
            );

            flash('success', 'Produk berhasil dihapus.');
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
        }

        redirect('/produk');
    }

    private function uploadFoto(): ?string
    {
        if (
            !isset($_FILES['foto']) ||
            $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file = $_FILES['foto'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload foto gagal.');
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception(
                'Ukuran foto maksimal 5 MB.'
            );
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png'
        ];

        $mime = mime_content_type($file['tmp_name']);

        if (!isset($allowed[$mime])) {
            throw new Exception(
                'Foto hanya boleh JPG atau PNG.'
            );
        }

        $uploadDir = __DIR__ . '/../../public/uploads';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $filename =
            bin2hex(random_bytes(10)) .
            '.' .
            $allowed[$mime];

        $destination = $uploadDir . '/' . $filename;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            throw new Exception(
                'Foto tidak dapat disimpan.'
            );
        }

        return $filename;
    }
}
