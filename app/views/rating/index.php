<div class="container">

    <section class="detail">

        <p class="eyebrow">
            ULASAN PRODUK
        </p>

        <h1>
            <?= htmlspecialchars(
                $produk['nama_produk']
            ) ?>
        </h1>

        <div class="order-product">

            <span class="badge">
                <?= htmlspecialchars(
                    $produk['kategori']
                ) ?>
            </span>

            <p class="price">

                <strong>
                    Rp <?= number_format(
                        (float)$produk['harga_diskon'],
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

            </p>

            <h2>
                ⭐ <?= number_format(
                    (float)$rataRata,
                    1
                ) ?>/5
            </h2>

        </div>

        <h2>
            Ulasan Customer
        </h2>

        <?php if (empty($rating)): ?>

            <div class="empty">

                <p>
                    Belum ada ulasan untuk produk ini.
                </p>

            </div>

        <?php else: ?>

            <?php foreach ($rating as $item): ?>

                <article class="review">

                    <strong>
                        <?= htmlspecialchars(
                            $item['nama_customer']
                        ) ?>
                    </strong>

                    <span>
                        <?= str_repeat(
                            '⭐',
                            (int)$item['bintang']
                        ) ?>
                    </span>

                    <?php if (
                        !empty($item['ulasan'])
                    ): ?>

                        <p>
                            <?= nl2br(
                                htmlspecialchars(
                                    $item['ulasan']
                                )
                            ) ?>
                        </p>

                    <?php endif; ?>

                    <small>
                        <?= date(
                            'd/m/Y H:i',
                            strtotime($item['tanggal'])
                        ) ?>
                    </small>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

        <div class="actions">

            <a
                class="btn ghost"
                href="<?= base_url('/') ?>"
            >
                ← Kembali ke Katalog
            </a>

        </div>

    </section>

</div>
