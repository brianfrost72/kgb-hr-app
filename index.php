<?php
session_start();

/* kalau sudah login, lempar ke dashboard */
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Konig Guard Bureau</title>
    <link rel="stylesheet" href="assets/css/login.css" />
</head>

<body>
    <div class="wrap">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
    </div>



    <div class="container login neon-pulse">
        <div class="logo-wrapper">
            <img src="assets/images/logo-light.png" alt="logo">
        </div>
        <h2>Masuk ke Akun</h2>
        <h5>Silahkan Masuk ke Konig Guard Bureau</h5>

        <!-- ERROR LOGIN -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                Email Atau Kata Sandi Salah.
            </div>
        <?php endif; ?>

        <!-- AKUN USER DIBLOKIR/NONAKTIF -->
        <?php if (isset($_GET['blocked'])): ?>
            <div class="error-msg">
                Akun Anda sedang <b>dinonaktifkan</b> oleh Super Admin.<br>
                Silakan hubungi Manager Cabang Anda.
            </div>
        <?php endif; ?>

        <!-- AUTO NONAKTIF CLIENT -->
        <?php if (isset($_GET['client_auto_block'])): ?>
            <div class="error-msg">
                Akun Anda Di Nonaktifkan Karena 2 Bulan tidak aktif.<br>
                Silahkan Hubungi kami <b>0811 1902 759</b>
            </div>
        <?php endif; ?>

        <!-- NONAKTIF DARI SUPERADMIN -->
        <?php if (isset($_GET['client_admin_block'])): ?>
            <div class="error-msg">
                Mohon Maaf Akun Anda Di Nonaktifkan.<br>
                Untuk Pertanyaan Silahkan Hubungi Admin.
            </div>
        <?php endif; ?>



        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    id="login_email"
                    name="email"
                    placeholder="Masukkan email" required />
            </div>

            <div class="form-group password-group">
                <label>Kata Sandi</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="login_password"
                        name="password"
                        placeholder="Masukkan kata sandi" required />

                    </span>
                </div>
            </div>


            <div class="form-check">
                <input
                    type="checkbox"
                    id="show_login_pass"
                    onclick="togglePassword('login_password')" />
                <label for="show_login_pass">Tampilkan Password</label>
            </div>

            <div class="forgot">
                <a href="reset_password.php">Lupa kata sandi?</a>
            </div>

            <button type="submit">Masuk</button>

            <!-- <div class="switch">
                Belum punya akun? <a href="registrasi.php">Daftar</a>
            </div> -->
        </form>
    </div>

    <script src="assets/js/auth.js"></script>
</body>

</html>