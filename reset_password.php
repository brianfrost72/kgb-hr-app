<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password | Konig Guard Bureau</title>
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

    <div class="resetPassword">
        <div class="container neon-pulse">

            <!-- BAGIAN FORM -->


            <div class="reset-content">
                <div class="logo-wrapper">
                    <img src="assets/images/logo-light.png" alt="logo">
                </div>

                <h2>Reset Password</h2>
                <h5>Silahkan masukkan email Anda untuk mengatur ulang kata sandi</h5>

                <?php if (isset($_SESSION['reset_error'])): ?>
                    <div class="error-msg">
                        <?= $_SESSION['reset_error'];
                        unset($_SESSION['reset_error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['reset_success'])): ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            document.querySelector(".reset-content").style.display = "none";
                            document.querySelector(".reset-success").style.display = "block";
                            setTimeout(() => {
                                window.location.href = "index.php";
                            }, 5000);
                        });
                    </script>
                <?php unset($_SESSION['reset_success']);
                endif; ?>
                <form method="POST" action="PHPMailer/resetpw_mail.php">
                    <div class="form-group mt-3">
                        <label>Email terdaftar</label>
                        <input type="email" id="reset-email" name="email" placeholder="Masukkan email Anda yang terdaftar" required>

                    </div>

                    <button id="resetBtn" type="submit">Kirim Tautan Reset</button>
                </form>


                <div class="switch">
                    Kembali ke <a href="index.php">Login</a> ?
                </div>
            </div>

            <!-- NOTIFIKASI SUKSES (AWALNYA HIDDEN) -->
            <div class="reset-success">
                <div class="checkmark">✓</div>
                <p>Email reset password berhasil dikirim</p>
                <small>Silahkan cek email Anda untuk tautan reset password</small>
                <br>
                <small>Mengarahkan ke halaman login...</small>
            </div>
        </div>
    </div>

</body>

</html>