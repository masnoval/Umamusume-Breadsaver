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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Niconne&family=Open+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?= base_url('/public/css/style.css') ?>"
    >
</head>

<body>

<header class="site-header">

    <div class="header-top">

        <div class="container">

            <div class="site-branding">

                <a
                    href="<?= base_url('/') ?>"
                    class="site-title"
                >
                    BreadSaver
                </a>

                <p class="site-description">
                    Selamatkan roti, hemat biaya.
                </p>

            </div>

        </div>

    </div>

    <nav class="main-navigation">

        <div class="container">

            <div class="nav-inner">

                <a
                    href="<?= base_url('/') ?>"
                    class="nav-link"
                >
                    HOME
                </a>

                <a
                    href="<?= base_url('/katalog') ?>"
                    class="nav-link"
                >
                    KATALOG
                </a>

                <a
                    href="<?= base_url('/') ?>#tentang"
                    class="nav-link"
                >
                    TENTANG
                </a>

                <?php if (current_user()): ?>

                    <a
                        href="<?= base_url('/pesanan') ?>"
                        class="nav-link"
                    >
                        PESANAN
                    </a>

                    <?php if (
                        current_user()['type'] === 'staff'
                    ): ?>

                        <a
                            href="<?= base_url('/produk') ?>"
                            class="nav-link"
                        >
                            PRODUK
                        </a>

                        <a
                            href="<?= base_url('/laporan') ?>"
                            class="nav-link"
                        >
                            LAPORAN
                        </a>

                    <?php endif; ?>

                <?php endif; ?>

                <a
                    href="<?= base_url('/') ?>#produk"
                    class="nav-link"
                >
                    PRODUK PILIHAN
                </a>

                <a
                    href="<?= base_url('/') ?>#kontak"
                    class="nav-link"
                >
                    KONTAK
                </a>

                <div class="nav-account">

                    <?php if (current_user()): ?>

                        <span class="nav-user">
                            <?= htmlspecialchars(
                                current_user()['nama']
                            ) ?>
                        </span>

                        <a
                            href="<?= base_url('/logout') ?>"
                            class="nav-link nav-login"
                        >
                            KELUAR
                        </a>

                    <?php else: ?>

                        <a
                            href="<?= base_url('/login') ?>"
                            class="nav-link nav-login"
                        >
                            LOGIN
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </nav>

</header>

<?php if (!empty($flash)): ?>

    <div class="flash-area">

        <div class="container">

            <?php foreach ($flash as $type => $messages): ?>

                <?php foreach ((array)$messages as $message): ?>

                    <div class="alert <?= htmlspecialchars($type) ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>

                <?php endforeach; ?>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif; ?>
