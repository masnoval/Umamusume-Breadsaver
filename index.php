<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/Database.php';

require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Produk.php';
require_once __DIR__ . '/app/models/Pesanan.php';
require_once __DIR__ . '/app/models/RatingUlasan.php';
require_once __DIR__ . '/app/models/TokoRoti.php';

require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/ProdukController.php';
require_once __DIR__ . '/app/controllers/PesananController.php';
require_once __DIR__ . '/app/controllers/RatingController.php';
require_once __DIR__ . '/app/controllers/LaporanController.php';

function base_url(string $path = ''): string
{
    return '/Umamusume-Breadsaver/' . ltrim($path, '/');
}

function current_user(): ?array
{
    return $_SESSION['auth'] ?? null;
}

function is_logged_in(): bool
{
    return isset($_SESSION['auth']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('/login');
    }
}

function require_customer(): void
{
    require_login();

    if (current_user()['type'] !== 'customer') {
        flash('error', 'Halaman ini hanya untuk customer.');
        redirect('/');
    }
}

function require_staff(): void
{
    require_login();

    if (current_user()['type'] !== 'staff') {
        flash('error', 'Halaman ini hanya untuk staff.');
        redirect('/');
    }
}

function redirect(string $path): never
{
    header('Location: ' . base_url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type][] = $message;
}

function consume_flash(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $flash;
}

function view(string $file, array $data = []): void
{
    extract($data);

    $viewFile = __DIR__ . '/app/views/' . $file . '.php';

    if (!file_exists($viewFile)) {
        http_response_code(500);
        echo 'View tidak ditemukan: ' . htmlspecialchars($file);
        exit;
    }

    require __DIR__ . '/app/views/layouts/header.php';
    require $viewFile;
    require __DIR__ . '/app/views/layouts/footer.php';
}

try {
    require __DIR__ . '/routes/web.php';
} catch (Throwable $e) {
    http_response_code(500);

    echo '<!doctype html>';
    echo '<html lang="id">';
    echo '<head><meta charset="utf-8"><title>BreadSaver Error</title></head>';
    echo '<body style="font-family:Arial;padding:40px">';
    echo '<h1>Terjadi kesalahan aplikasi</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</body>';
    echo '</html>';
}
