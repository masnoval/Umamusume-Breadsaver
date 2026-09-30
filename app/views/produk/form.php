<?php
$isEdit = isset($produk);
?>

<form
    method="post"
    enctype="multipart/form-data"
    action="<?= $isEdit
        ? base_url(
            '/produk/edit?id=' .
            (int)$produk['id_produk']
        )
        : base_url('/produk/tambah')
    ?>"
>

    <div class="two">

        <label>
            Nama Produk

            <input
                type="text"
                name="nama_produk"
                value="<?= htmlspecialchars(
                    $produk['nama_produk'] ?? ''
                ) ?>"
                required
            >
        </label>

        <label>
            Kategori

            <select
                name="kategori"
                required
            >

                <?php
                $kategori = $produk['kategori'] ?? '';
                ?>

                <option value="">
                    Pilih kategori
                </option>

                <option
                    value="Roti"
                    <?= $kategori === 'Roti'
                        ? 'selected'
                        : '' ?>
                >
                    Roti
                </option>

                <option
                    value="Pastry"
                    <?= $kategori === 'Pastry'
                        ? 'selected'
                        : '' ?>
                >
                    Pastry
                </option>

                <option
                    value="Kue"
                    <?= $kategori === 'Kue'
                        ? 'selected'
                        : '' ?>
                >
                    Kue
                </option>

                <option
                    value="Lainnya"
                    <?= $kategori === 'Lainnya'
                        ? 'selected'
                        : '' ?>
                >
                    Lainnya
                </option>

            </select>

        </label>

    </div>

    <div class="two">

        <label>
            Harga Asli

            <input
                type="number"
                name="harga_asli"
                min="0"
                step="100"
                value="<?= htmlspecialchars(
                    $produk['harga_asli'] ?? ''
                ) ?>"
                required
            >
        </label>

        <label>
            Harga Diskon

            <input
                type="number"
                name="harga_diskon"
                min="0"
                step="100"
                value="<?= htmlspecialchars(
                    $produk['harga_diskon'] ?? ''
                ) ?>"
                required
            >
        </label>

    </div>

    <div class="two">

        <label>
            Stok

            <input
                type="number"
                name="stok"
                min="0"
                value="<?= htmlspecialchars(
                    $produk['stok'] ?? ''
                ) ?>"
                required
            >
        </label>

        <label>
            Status

            <?php
            $status = $produk['status'] ?? 'Aktif';
            ?>

            <select name="status">

                <option
                    value="Aktif"
                    <?= $status === 'Aktif'
                        ? 'selected'
                        : '' ?>
                >
                    Aktif
                </option>

                <option
                    value="Habis"
                    <?= $status === 'Habis'
                        ? 'selected'
                        : '' ?>
                >
                    Habis
                </option>

                <option
                    value="Nonaktif"
                    <?= $status === 'Nonaktif'
                        ? 'selected'
                        : '' ?>
                >
                    Nonaktif
                </option>

            </select>

        </label>

    </div>

    <label>
        Kadaluarsa

        <input
            type="datetime-local"
            name="kadaluarsa"
            value="<?=
                isset($produk['kadaluarsa'])
                    ? date(
                        'Y-m-d\TH:i',
                        strtotime(
                            $produk['kadaluarsa']
                        )
                    )
                    : ''
            ?>"
            required
        >
    </label>

    <label>
        Foto Produk

        <input
            type="file"
            name="foto"
            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
        >

        <small>
            JPG/PNG, maksimal 5 MB.
        </small>
    </label>

    <?php if (
        !empty($produk['foto'])
    ): ?>

        <p>
            Foto saat ini:
            <?= htmlspecialchars(
                $produk['foto']
            ) ?>
        </p>

    <?php endif; ?>

    <div class="actions">

        <button
            class="btn"
            type="submit"
        >
            <?= $isEdit
                ? 'Simpan Perubahan'
                : 'Tambah Produk'
            ?>
        </button>

        <a
            class="btn ghost"
            href="<?= base_url('/produk') ?>"
        >
            Batal
        </a>

    </div>

</form>