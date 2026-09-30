<div class="container">

    <div class="page-head">

        <div>

            <p class="eyebrow">
                STAFF
            </p>

            <h1>
                Kelola Produk
            </h1>

        </div>

        <a
            class="btn"
            href="<?= base_url('/produk/tambah') ?>"
        >
            + Tambah Produk
        </a>

    </div>

    <?php if (empty($produk)): ?>

        <div class="empty">

            <div style="font-size:50px">
                🍞
            </div>

            <h2>
                Belum ada produk
            </h2>

            <a
                class="btn"
                href="<?= base_url('/produk/tambah') ?>"
            >
                Tambah Produk
            </a>

        </div>

    <?php else: ?>

        <div class="table-wrap">

            <table>

                <thead>

                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Kadaluarsa</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($produk as $item): ?>

                    <tr>

                        <td>
                            <strong>
                                <?= htmlspecialchars(
                                    $item['nama_produk']
                                ) ?>
                            </strong>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $item['kategori']
                            ) ?>
                        </td>

                        <td>
                            Rp <?= number_format(
                                (float)$item['harga_diskon'],
                                0,
                                ',',
                                '.'
                            ) ?>
                        </td>

                        <td>
                            <?= (int)$item['stok'] ?>
                        </td>

                        <td>
                            <span class="badge">
                                <?= htmlspecialchars(
                                    $item['status']
                                ) ?>
                            </span>
                        </td>

                        <td>
                            <?= date(
                                'd/m/Y H:i',
                                strtotime(
                                    $item['kadaluarsa']
                                )
                            ) ?>
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    class="btn small"
                                    href="<?= base_url(
                                        '/produk/edit?id=' .
                                        (int)$item['id_produk']
                                    ) ?>"
                                >
                                    Edit
                                </a>

                                <form
                                    method="post"
                                    action="<?= base_url(
                                        '/produk/hapus'
                                    ) ?>"
                                    class="inline"
                                    data-confirm="Yakin ingin menghapus produk ini?"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int)$item['id_produk'] ?>"
                                    >

                                    <button
                                        class="btn small danger"
                                        type="submit"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>
