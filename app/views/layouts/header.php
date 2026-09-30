<?php
$flash = consume_flash();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        <?= htmlspecialchars($title ?? 'BreadSaver') ?>
        - BreadSaver
    </title>

    <link
        rel="stylesheet"
        href="<?= base_url('/public/css/style.css') ?>"
    >
</head>

<body>

<header class="topbar">

    <a
        class="brand"
        href="<?= base_url('/') ?>"
    >
        🍞 BreadSaver
    </a>

    <nav>

        <a href="<?= base_url('/') ?>">
            Katalog
        </a>

        <?php if (current_user()): ?>

            <?php if (
                current_user()['type'] === 'customer'
            ): ?>

                <a href="<?= base_url('/pesanan') ?>">
                    Pesanan
                </a>

                <span class="user">
                    👤
                    <?= htmlspecialchars(
                        current_user()['nama']
                    ) ?>
                </span>

                <a
                    class="btn small"
                    href="<?= base_url('/logout') ?>"
                >
                    Keluar
                </a>

            <?php else: ?>

                <a href="<?= base_url('/produk') ?>">
                    Kelola Produk
                </a>

                <a href="<?= base_url('/pesanan') ?>">
                    Pesanan
                </a>

                <a href="<?= base_url('/laporan') ?>">
                    Laporan
                </a>

                <span class="user">
                    👤
                    <?= htmlspecialchars(
                        current_user()['nama']
                    ) ?>
                </span>

                <a
                    class="btn small"
                    href="<?= base_url('/logout') ?>"
                >
                    Keluar
                </a>

            <?php endif; ?>

        <?php else: ?>

            <a href="<?= base_url('/login') ?>">
                Login
            </a>

            <a
                class="btn small"
                href="<?= base_url('/register') ?>"
            >
                Daftar
            </a>

        <?php endif; ?>

    </nav>

</header>

<?php if (!empty($flash)): ?>

    <main class="container flash-container">

        <?php foreach ($flash as $type => $messages): ?>

            <?php foreach ((array)$messages as $message): ?>

                <div class="alert <?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>

            <?php endforeach; ?>

        <?php endforeach; ?>

    </main>

<?php endif; ?>
