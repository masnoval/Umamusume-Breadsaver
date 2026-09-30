<div class="container">

    <?php if (($title ?? '') === 'Halaman Tidak Ditemukan'): ?>

        <section class="empty">
            <h1>404</h1>
            <p>Halaman yang kamu cari tidak ditemukan.</p>

            <a
                class="btn"
                href="<?= base_url('/') ?>"
            >
                Kembali ke Katalog
            </a>
        </section>

    <?php else: ?>

        <section class="hero">

            <div>

                <p class="eyebrow">
                    BREADSAVER
                </p>

                <h1>
                    Selamatkan roti,
                    hemat biaya.
                </h1>

                <p>
                    Temukan produk roti dan pastry
                    dengan harga diskon sebelum
                    masa kedaluwarsa.
                </p>

            </div>

        </section>

        <form
            class="search"
            method="get"
            action="<?= base_url('/katalog') ?>"
        >

            <input
                type="search"
                name="q"
                value="<?= htmlspecialchars($search ?? '') ?>"
                placeholder="Cari produk atau kategori..."
            >

            <button class="btn" type="submit">
                Cari
            </button>

        </form>

        <?php if (empty($produk)): ?>

            <div class="empty">

                <div style="font-size:50px">
                    🍞
                </div>

                <h2>
                    Produk tidak ditemukan
                </h2>

                <p>
                    Coba gunakan kata pencarian
                    yang berbeda.
                </p>

            </div>

        <?php else: ?>

            <div class="grid">

                <?php foreach ($produk as $item): ?>

                    <article class="card">

                        <?php if (!empty($item['foto'])): ?>

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

                            <div class="placeholder">
                                🍞
                            </div>

                        <?php endif; ?>

                        <div class="card-body">

                            <span class="badge">
                                <?= htmlspecialchars(
                                    $item['kategori']
                                ) ?>
                            </span>

                            <h3>
                                <?= htmlspecialchars(
                                    $item['nama_produk']
                                ) ?>
                            </h3>

                            <div class="price">

                                <s>
                                    Rp <?= number_format(
                                        (float)$item['harga_asli'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </s>

                                <strong>
                                    Rp <?= number_format(
                                        (float)$item['harga_diskon'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>

                            </div>

                            <p>
                                Stok:
                                <strong>
                                    <?= (int)$item['stok'] ?>
                                </strong>

                                · ⭐
                                <?= number_format(
                                    (float)$item['rating'],
                                    1
                                ) ?>
                            </p>

                            <p>
                                Kedaluwarsa:
                                <?= date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $item['kadaluarsa']
                                    )
                                ) ?>
                            </p>

                            <div class="actions">

                                <?php if (
                                    current_user() &&
                                    current_user()['type'] === 'customer'
                                ): ?>

                                    <a
                                        class="btn small"
                                        href="<?= base_url(
                                            '/pesanan/tambah?id=' .
                                            (int)$item['id_produk']
                                        ) ?>"
                                    >
                                        Pesan
                                    </a>

                                <?php else: ?>

                                    <a
                                        class="btn small"
                                        href="<?= base_url(
                                            '/login'
                                        ) ?>"
                                    >
                                        Login untuk Pesan
                                    </a>

                                <?php endif; ?>

                                <a
                                    class="btn small ghost"
                                    href="<?= base_url(
                                        '/rating?id_produk=' .
                                        (int)$item['id_produk']
                                    ) ?>"
                                >
                                    Ulasan
                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>
