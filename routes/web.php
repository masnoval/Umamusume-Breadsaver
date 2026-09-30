<?php

declare(strict_types=1);

$db = (new Database())->connect();

$authController = new AuthController($db);
$produkController = new ProdukController($db);
$pesananController = new PesananController($db);
$ratingController = new RatingController($db);
$laporanController = new LaporanController($db);

$method = strtoupper($_SERVER['REQUEST_METHOD']);

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$base = '/Umamusume-Breadsaver';

if (str_starts_with($uri, $base)) {
    $path = substr($uri, strlen($base));
} else {
    $path = $uri;
}

$path = '/' . trim($path, '/');

if ($path === '//') {
    $path = '/';
}


/*
|--------------------------------------------------------------------------
| HOME / KATALOG
|--------------------------------------------------------------------------
*/

if (
    $method === 'GET' &&
    ($path === '/' || $path === '/katalog')
) {
    $produkModel = new Produk($db);

    $search = trim($_GET['q'] ?? '');

    view('home/index', [
        'title' => 'Katalog Produk',
        'produk' => $produkModel->getAktif($search),
        'search' => $search
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

if ($method === 'GET' && $path === '/login') {
    $authController->loginForm();
    exit;
}

if ($method === 'POST' && $path === '/login') {
    $authController->login();
    exit;
}

if ($method === 'GET' && $path === '/register') {
    $authController->registerForm();
    exit;
}

if ($method === 'POST' && $path === '/register') {
    $authController->register();
    exit;
}

if ($method === 'GET' && $path === '/logout') {
    $authController->logout();
    exit;
}


/*
|--------------------------------------------------------------------------
| PRODUK STAFF
|--------------------------------------------------------------------------
*/

if ($method === 'GET' && $path === '/produk') {
    $produkController->index();
    exit;
}

if ($method === 'GET' && $path === '/produk/tambah') {
    $produkController->createForm();
    exit;
}

if ($method === 'POST' && $path === '/produk/tambah') {
    $produkController->create();
    exit;
}

if ($method === 'GET' && $path === '/produk/edit') {
    $produkController->editForm(
        (int)($_GET['id'] ?? 0)
    );
    exit;
}

if ($method === 'POST' && $path === '/produk/edit') {
    $produkController->edit(
        (int)($_GET['id'] ?? 0)
    );
    exit;
}

if ($method === 'POST' && $path === '/produk/hapus') {
    $produkController->delete(
        (int)($_POST['id'] ?? 0)
    );
    exit;
}


/*
|--------------------------------------------------------------------------
| PESANAN
|--------------------------------------------------------------------------
*/

if ($method === 'GET' && $path === '/pesanan') {
    $pesananController->index();
    exit;
}

if (
    $method === 'GET' &&
    $path === '/pesanan/tambah'
) {
    $pesananController->createForm(
        isset($_GET['id'])
            ? (int)$_GET['id']
            : null
    );

    exit;
}

if (
    $method === 'POST' &&
    $path === '/pesanan/tambah'
) {
    $pesananController->create();
    exit;
}

if (
    $method === 'GET' &&
    $path === '/pesanan/detail'
) {
    $pesananController->detail(
        (int)($_GET['id'] ?? 0)
    );

    exit;
}

if (
    $method === 'POST' &&
    $path === '/pesanan/status'
) {
    $pesananController->updateStatus(
        (int)($_POST['id'] ?? 0)
    );

    exit;
}

if (
    $method === 'POST' &&
    $path === '/pesanan/batal'
) {
    $pesananController->cancel(
        (int)($_POST['id'] ?? 0)
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| RATING
|--------------------------------------------------------------------------
*/

if (
    $method === 'GET' &&
    $path === '/rating'
) {
    $ratingController->index(
        (int)($_GET['id_produk'] ?? 0)
    );

    exit;
}

if (
    $method === 'POST' &&
    $path === '/rating'
) {
    $ratingController->create();
    exit;
}


/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/

if (
    $method === 'GET' &&
    $path === '/laporan'
) {
    $laporanController->index();
    exit;
}


/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/

http_response_code(404);

view('home/index', [
    'title' => 'Halaman Tidak Ditemukan',
    'produk' => [],
    'search' => ''
]);
