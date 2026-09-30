<div class="container">

    <section class="auth-card">

        <p class="eyebrow">
            BREADSAVER
        </p>

        <h1>
            Buat akun
        </h1>

        <p>
            Daftar sebagai customer BreadSaver.
        </p>

        <form
            method="post"
            action="<?= base_url('/register') ?>"
        >

            <label>
                Nama

                <input
                    type="text"
                    name="nama"
                    required
                >
            </label>

            <label>
                Email

                <input
                    type="email"
                    name="email"
                    required
                >
            </label>

            <label>
                Nomor HP

                <input
                    type="text"
                    name="no_hp"
                >
            </label>

            <label>
                Alamat

                <textarea
                    name="alamat"
                    placeholder="Alamat lengkap"
                ></textarea>
            </label>

            <label>
                Password

                <input
                    type="password"
                    name="password"
                    minlength="6"
                    required
                >
            </label>

            <button
                class="btn"
                type="submit"
            >
                Daftar
            </button>

        </form>

        <p>
            Sudah punya akun?

            <a href="<?= base_url('/login') ?>">
                Login
            </a>
        </p>

    </section>

</div>
