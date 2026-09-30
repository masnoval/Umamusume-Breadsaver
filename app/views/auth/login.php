<div class="container">

    <section class="auth-card">

        <p class="eyebrow">
            BREADSAVER
        </p>

        <h1>
            Selamat datang kembali 👋
        </h1>

        <p>
            Login untuk melanjutkan.
        </p>

        <form
            method="post"
            action="<?= base_url('/login') ?>"
        >

            <label>
                Email

                <input
                    type="email"
                    name="email"
                    required
                    autocomplete="email"
                >
            </label>

            <label>
                Password

                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                >
            </label>

            <button
                class="btn"
                type="submit"
            >
                Login
            </button>

        </form>

        <p>
            Belum punya akun?

            <a href="<?= base_url('/register') ?>">
                Daftar sekarang
            </a>
        </p>

    </section>

</div>
