<aside class="sidebar">

    <a href="<?= base_url('/') ?>">
        🏠 Beranda
    </a>

    <a href="<?= base_url('/katalog') ?>">
        🍞 Katalog Produk
    </a>

    <?php if (
        current_user() &&
        current_user()['type'] === 'staff'
    ): ?>

        <a href="<?= base_url('/produk') ?>">
            📦 Kelola Produk
        </a>

        <a href="<?= base_url('/pesanan') ?>">
            🧾 Pesanan
        </a>

        <a href="<?= base_url('/laporan') ?>">
            📊 Laporan Penjualan
        </a>

    <?php elseif (
        current_user() &&
        current_user()['type'] === 'customer'
    ): ?>

        <a href="<?= base_url('/pesanan') ?>">
            🧾 Pesanan Saya
        </a>

    <?php endif; ?>

</aside>
