<div class="container">

    <div class="page-head">

        <div>

            <p class="eyebrow">
                STAFF
            </p>

            <h1>
                Laporan Penjualan
            </h1>

        </div>

    </div>

    <div class="stats">

        <div>

            <b>
                <?= number_format(
                    (int)$statistik['transaksi'],
                    0,
                    ',',
                    '.'
                ) ?>
            </b>

            <span>
                Transaksi Selesai
            </span>

        </div>

        <div>

            <b>
                <?= number_format(
                    (int)$statistik['produk_terjual'],
                    0,
                    ',',
                    '.'
                ) ?>
            </b>

            <span>
                Produk Terjual
            </span>

        </div>

        <div>

            <b>
                Rp <?= number_format(
                    (float)$statistik['pendapatan'],
                    0,
                    ',',
                    '.'
                ) ?>
            </b>

            <span>
                Total Pendapatan
            </span>

        </div>

    </div>

    <div class="table-wrap">

        <table>

            <thead>

                <tr>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Produk Terjual</th>
                    <th>Total Penjualan</th>
                </tr>

            </thead>

            <tbody>

            <?php if (empty($laporan)): ?>

                <tr>
                    <td colspan="4">
                        Belum ada transaksi selesai.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($laporan as $item): ?>

                    <tr>

                        <td>
                            <?= date(
                                'd/m/Y',
                                strtotime(
                                    $item['tanggal']
                                )
                            ) ?>
                        </td>

                        <td>
                            <?= (int)$item[
                                'jumlah_transaksi'
                            ] ?>
                        </td>

                        <td>
                            <?= (int)$item[
                                'jumlah_produk'
                            ] ?>
                        </td>

                        <td>
                            Rp <?= number_format(
                                (float)$item[
                                    'total_penjualan'
                                ],
                                0,
                                ',',
                                '.'
                            ) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
