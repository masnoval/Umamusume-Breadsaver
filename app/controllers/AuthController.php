<?php

declare(strict_types=1);

class AuthController
{
    private User $user;

    public function __construct(PDO $db)
    {
        $this->user = new User($db);
    }

    public function loginForm(): void
    {
        if (is_logged_in()) {
            redirect('/');
        }

        view('auth/login', [
            'title' => 'Login'
        ]);
    }

    public function login(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            flash('error', 'Email dan password wajib diisi.');
            redirect('/login');
        }

        $staff = $this->user->staffByEmail($email);

        if (
            $staff &&
            password_verify($password, $staff['password'])
        ) {
            session_regenerate_id(true);

            $_SESSION['auth'] = [
                'type' => 'staff',
                'id' => (int)$staff['id_user'],
                'nama' => $staff['nama'],
                'email' => $staff['email']
            ];

            flash('success', 'Login staff berhasil.');
            redirect('/');
        }

        $customer = $this->user->customerByEmail($email);

        if (
            $customer &&
            password_verify($password, $customer['password'])
        ) {
            session_regenerate_id(true);

            $_SESSION['auth'] = [
                'type' => 'customer',
                'id' => (int)$customer['id_customer'],
                'nama' => $customer['nama'],
                'email' => $customer['email']
            ];

            flash('success', 'Login berhasil.');
            redirect('/');
        }

        flash('error', 'Email atau password salah.');
        redirect('/login');
    }

    public function registerForm(): void
    {
        if (is_logged_in()) {
            redirect('/');
        }

        view('auth/register', [
            'title' => 'Daftar Akun'
        ]);
    }

    public function register(): void
    {
        $nama = trim($_POST['nama'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $noHp = trim($_POST['no_hp'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');

        if (
            $nama === '' ||
            $email === '' ||
            $password === ''
        ) {
            flash(
                'error',
                'Nama, email, dan password wajib diisi.'
            );

            redirect('/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email tidak valid.');
            redirect('/register');
        }

        if (strlen($password) < 6) {
            flash(
                'error',
                'Password minimal 6 karakter.'
            );

            redirect('/register');
        }

        if ($this->user->emailExists($email)) {
            flash('error', 'Email sudah digunakan.');
            redirect('/register');
        }

        try {
            $this->user->registerCustomer(
                $nama,
                $email,
                $password,
                $noHp,
                $alamat
            );

            flash(
                'success',
                'Registrasi berhasil. Silakan login.'
            );

            redirect('/login');
        } catch (Throwable $e) {
            flash(
                'error',
                'Registrasi gagal: ' . $e->getMessage()
            );

            redirect('/register');
        }
    }

    public function logout(): void
    {
        unset($_SESSION['auth']);

        flash('success', 'Anda telah logout.');

        redirect('/');
    }
}
