<?php

$is404 = (($title ?? '') === 'Halaman Tidak Ditemukan');

?>

<?php if ($is404): ?>

    <main>

        <section class="simple-page">

            <div class="container">

                <div class="simple-page-inner">

                    <div class="script-title">
                        Oops!
                    </div>

                    <h1>
                        Halaman tidak ditemukan
                    </h1>

                    <p>
                        Halaman yang kamu cari tidak tersedia.
                    </p>

                    <a
                        href="<?= base_url('/') ?>"
                        class="outline-button dark"
                    >
                        KEMBALI KE HOME
                    </a>

                </div>

            </div>

        </section>

    </main>

<?php else: ?>

<main>

    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="hero-banner">

        <div class="hero-scallop-top"></div>

        <div class="container">

            <div class="hero-grid">

                <div class="hero-art">

                    <div class="cake-shadow"></div>

                    <div class="cake-plate"></div>

                    <div class="cake">

                        <div class="cake-top">

                            <span class="berry berry-one"></span>
                            <span class="berry berry-two"></span>
                            <span class="berry berry-three"></span>

                        </div>

                        <div class="cake-cream"></div>

                        <div class="cake-body">

                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>

                        </div>

                    </div>

                </div>

                <div class="hero-copy">

                    <div class="script-title white">
                        Selamatkan Roti
                    </div>

                    <h1>
                        ROTI BAIK
                        <br>
                        TIDAK HARUS
                        <br>
                        TERBUANG
                    </h1>

                    <p>
                        Temukan roti dan pastry dengan
                        harga lebih hemat sebelum masa
                        kedaluwarsa.
                    </p>

                    <a
                        href="<?= base_url('/katalog') ?>"
                        class="outline-button"
                    >
                        LIHAT KATALOG
                    </a>

                </div>

            </div>

        </div>

        <div class="hero-dots">
            <span></span>
            <span class="active"></span>
        </div>

        <div class="hero-scallop-bottom"></div>

    </section>


    <!-- =====================================================
         TENTANG / DISCOVER
    ====================================================== -->

    <section
        class="discover-section"
        id="tentang"
    >

        <div class="container">

            <div class="discover-grid">

                <div class="discover-copy">

                    <div class="script-title pink">
                        Discover
                    </div>

                    <h2>
                        BreadSaver
                    </h2>

                    <p>
                        BreadSaver membantu menghubungkan
                        customer dengan produk roti dan pastry
                        yang masih layak konsumsi tetapi
                        mendekati masa kedaluwarsa.
                    </p>

                    <p>
                        Dengan harga yang lebih terjangkau,
                        produk tetap bernilai dan makanan
                        tidak berakhir sia-sia.
                    </p>

                    <a
                        href="<?= base_url('/katalog') ?>"
                        class="outline-button dark"
                    >
                        LIHAT PRODUK
                    </a>

                </div>


                <div class="discover-gallery">

                    <div class="gallery-item gallery-large">
                        <div class="food-illustration chocolate">
                            🍰
                        </div>
                    </div>

                    <div class="gallery-item">
                        <div class="food-illustration roll">
                            🥐
                        </div>
                    </div>

                    <div class="gallery-item">
                        <div class="food-illustration bread">
                            🍞
                        </div>
                    </div>

                    <div class="gallery-item">
                        <div class="food-illustration cookie">
                            🍪
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PRODUK PILIHAN
    ====================================================== -->

    <section
        class="featured-section"
        id="produk"
    >

        <div class="container">

            <header class="section-header white-header">

                <div class="script-title white">
                    Produk Pilihan
                </div>

                <p class="section-subtitle">
                    PRODUK ROTI &amp; PASTRY
                    DENGAN HARGA LEBIH HEMAT
                </p>

            </header>


            <?php if (empty($produk)): ?>

                <div class="featured-empty">
                    Belum ada produk tersedia.
                </div>

            <?php else: ?>

                <div class="featured-products">

                    <?php foreach (
                        array_slice($produk, 0, 6)
                        as $item
                    ): ?>

                        <article class="featured-product">

                            <div class="featured-image">

                                <?php if (
                                    !empty($item['foto'])
                                ): ?>

                                    <img
                                        src="<?= base_url(
                                            '/public/uploads/' .
                                            rawurlencode(
                                                $item['foto']
                                            )
                                        ) ?>"
                                        alt="<?= htmlspecialchars(
                                            $item['nama_produk']
                                        ) ?>"
                                    >

                                <?php else: ?>

                                    <div class="featured-placeholder">
                                        🍞
                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="featured-info">

                                <span class="product-category">
                                    <?= htmlspecialchars(
                                        $item['kategori']
                                    ) ?>
                                </span>

                                <h3>
                                    <?= htmlspecialchars(
                                        $item['nama_produk']
                                    ) ?>
                                </h3>

                                <div class="featured-price">

                                    <s>
                                        Rp <?= number_format(
                                            (float)$item[
                                                'harga_asli'
                                            ],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                    </s>

                                    <strong>
                                        Rp <?= number_format(
                                            (float)$item[
                                                'harga_diskon'
                                            ],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                    </strong>

                                </div>

                                <div class="featured-rating">
                                    ⭐
                                    <?= number_format(
                                        (float)$item['rating'],
                                        1
                                    ) ?>
                                </div>

                                <?php if (
                                    current_user() &&
                                    current_user()['type'] ===
                                    'customer'
                                ): ?>

                                    <a
                                        href="<?= base_url(
                                            '/pesanan/tambah?id=' .
                                            (int)$item[
                                                'id_produk'
                                            ]
                                        ) ?>"
                                        class="featured-order"
                                    >
                                        PESAN
                                    </a>

                                <?php else: ?>

                                    <a
                                        href="<?= base_url(
                                            '/login'
                                        ) ?>"
                                        class="featured-order"
                                    >
                                        LOGIN UNTUK PESAN
                                    </a>

                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

        <div class="pink-scallop"></div>

    </section>


    <!-- =====================================================
         CALL TO ACTION
    ====================================================== -->

    <section
        class="cta-section"
        id="kontak"
    >

        <div class="container">

            <div class="cta-inner">

                <div>

                    <div class="script-title white">
                        Jangan biarkan roti terbuang
                    </div>

                    <h2>
                        Hemat hari ini.
                        Selamatkan roti hari ini.
                    </h2>

                    <p>
                        Temukan produk yang masih layak
                        dinikmati sebelum waktunya habis.
                    </p>

                </div>

                <a
                    href="<?= base_url('/katalog') ?>"
                    class="outline-button"
                >
                    JELAJAHI KATALOG
                </a>

            </div>

        </div>

    </section>

</main>

<?php endif; ?>
