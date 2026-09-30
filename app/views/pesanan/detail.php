<div class="container">

    <section class="detail">

        <p class="eyebrow">
            DETAIL PESANAN
        </p>

        <h1>
            <?= htmlspecialchars(
                $pesanan['kode_pesanan']
            ) ?>
        </h1>

        <div class="order-product">

            <span class="badge">
                <?= htmlspecialchars(
                    $pesanan['kategori']
                ) ?>
            </span>

            <h2>
                <?= htmlspecialchars(
                    $pesanan['nama_produk']
                ) ?>
            </h2>

            <p>
                Jumlah:
                <strong>
                    <?= (int)$pesanan['jumlah'] ?>
                </strong>
            </p>

            <p>
                Harga satuan:
                <strong>
                    Rp <?= number_format(
                        (float)$pesanan['harga_satuan'],
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>
            </p>

            <p>
                Total:
                <strong>
                    Rp <?= number_format(
                        (float)$pesanan['total_harga'],
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>
            </p>

        </div>

        <dl>

            <dt>Customer</dt>
            <dd>
                <?= htmlspecialchars(
                    $pesanan['nama_customer']
                ) ?>
            </dd>

            <dt>Email</dt>
            <dd>
                <?= htmlspecialchars(
                    $pesanan['email_customer']
                ) ?>
            </dd>

            <dt>No. HP</dt>
            <dd>
                <?= htmlspecialchars(
                    $pesanan['no_hp'] ?? '-'
                ) ?>
            </dd>

            <dt>Alamat</dt>
            <dd>
                <?= nl2br(
                    htmlspecialchars(
                        $pesanan['alamat'] ?? '-'
                    )
                ) ?>
            </dd>

            <dt>Status</dt>
            <dd>
                <span class="badge">
                    <?= htmlspecialchars(
                        $pesanan['status']
                    ) ?>
                </span>
            </dd>

            <dt>Tanggal</dt>
            <dd>
                <?= date(
                    'd/m/Y H:i',
                    strtotime($pesanan['tanggal'])
                ) ?>
            </dd>

        </dl>

        <?php if (
            current_user()['type'] === 'customer' &&
            $pesanan['status'] === 'Selesai'
        ): ?>

            <div class="review">

                <h2>
                    ⭐ Berikan Rating
                </h2>

                <form
                    method="post"
                    action="<?= base_url('/rating') ?>"
                >

                    <input
                        type="hidden"
                        name="id_pesanan"
                        value="<?= (int)$pesanan['id_pesanan'] ?>"
                    >

                    <label>
                        Rating

                        <select
                            name="bintang"
                            required
                        >
                            <option value="">
                                Pilih rating
                            </option>

                            <option value="5">
                                ⭐⭐⭐⭐⭐
                            </option>

                            <option value="4">
                                ⭐⭐⭐⭐
                            </option>

                            <option value="3">
                                ⭐⭐⭐
                            </option>

                            <option value="2">
                                ⭐⭐
                            </option>

                            <option value="1">
                                ⭐
                            </option>

                        </select>

                    </label>

                    <label>
                        Ulasan

                        <textarea
                            name="ulasan"
                            placeholder="Bagaimana pengalamanmu?"
                        ></textarea>

                    </label>

                    <button
                        class="btn"
                        type="submit"
                    >
                        Kirim Rating
                    </button>

                </form>

            </div>

        <?php endif; ?>

        <div class="actions">

            <a
                class="btn ghost"
                href="<?= base_url('/pesanan') ?>"
            >
                ← Kembali
            </a>

        </div>

    </section>

</div>
