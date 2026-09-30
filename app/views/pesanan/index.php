<div class="container">

    <div class="page-head">

        <div>
            <p class="eyebrow">
                TRANSAKSI
            </p>

            <h1>
                Pesanan
            </h1>
        </div>

    </div>

    <?php if (empty($pesanan)): ?>

        <div class="empty">

            <div style="font-size:50px">
                🛒
            </div>

            <h2>
                Belum ada pesanan
            </h2>

            <p>
                Pesanan yang dibuat akan muncul di sini.
            </p>

            <a
                class="btn"
                href="<?= base_url('/') ?>"
            >
                Lihat Katalog
            </a>

        </div>

    <?php else: ?>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($pesanan as $item): ?>

                    <tr>

                        <td>
                            <strong>
                                <?= htmlspecialchars(
                                    $item['kode_pesanan']
                                ) ?>
                            </strong>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $item['nama_produk']
                            ) ?>
                        </td>

                        <td>
                            <?= (int)$item['jumlah'] ?>
                        </td>

                        <td>
                            Rp <?= number_format(
                                (float)$item['total_harga'],
                                0,
                                ',',
                                '.'
                            ) ?>
                        </td>

                        <td>
                            <?= date(
                                'd/m/Y H:i',
                                strtotime($item['tanggal'])
                            ) ?>
                        </td>

                        <td>
                            <span class="badge">
                                <?= htmlspecialchars(
                                    $item['status']
                                ) ?>
                            </span>
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    class="btn small ghost"
                                    href="<?= base_url(
                                        '/pesanan/detail?id=' .
                                        (int)$item['id_pesanan']
                                    ) ?>"
                                >
                                    Detail
                                </a>

                                <?php if (
                                    current_user()['type'] ===
                                    'customer'
                                ): ?>

                                    <?php if (
                                        $item['status'] ===
                                        'Menunggu Konfirmasi'
                                    ): ?>

                                        <form
                                            method="post"
                                            action="<?= base_url(
                                                '/pesanan/batal'
                                            ) ?>"
                                            class="inline"
                                            data-confirm="Batalkan pesanan ini?"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$item['id_pesanan'] ?>"
                                            >

                                            <button
                                                class="btn small danger"
                                                type="submit"
                                            >
                                                Batal
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                    <?php if (
                                        $item['status'] === 'Selesai' &&
                                        !(bool)$item['sudah_rating']
                                    ): ?>

                                        <a
                                            class="btn small"
                                            href="<?= base_url(
                                                '/pesanan/detail?id=' .
                                                (int)$item['id_pesanan']
                                            ) ?>"
                                        >
                                            ⭐ Rating
                                        </a>

                                    <?php endif; ?>

                                <?php else: ?>

                                    <?php if (
                                        !in_array(
                                            $item['status'],
                                            ['Selesai', 'Dibatalkan'],
                                            true
                                        )
                                    ): ?>

                                        <form
                                            method="post"
                                            action="<?= base_url(
                                                '/pesanan/status'
                                            ) ?>"
                                            class="inline"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int)$item['id_pesanan'] ?>"
                                            >

                                            <select
                                                name="status"
                                                onchange="this.form.submit()"
                                            >
                                                <option
                                                    value=""
                                                >
                                                    Ubah
                                                </option>

                                                <option
                                                    value="Menunggu Konfirmasi"
                                                >
                                                    Menunggu Konfirmasi
                                                </option>

                                                <option
                                                    value="Diproses"
                                                >
                                                    Diproses
                                                </option>

                                                <option
                                                    value="Selesai"
                                                >
                                                    Selesai
                                                </option>

                                                <option
                                                    value="Dibatalkan"
                                                >
                                                    Dibatalkan
                                                </option>

                                            </select>

                                        </form>

                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>
