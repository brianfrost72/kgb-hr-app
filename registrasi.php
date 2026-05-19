<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrasi</title>
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
    </div>

    <div class="container neon-pulse">
        <div class="logo-wrapper">
            <img src="assets/images/logo-light.png" alt="logo">
        </div>
        <h2>Pendaftaran Akun</h2>
        <h5>Yuk, daftarkan akunmu sekarang juga!</h5>

        <form id="registerForm" action="proses_registrasi.php" method="POST">
            <!-- Pilihan Jenis Akun -->
            <div class="form-group">
                <label>Jenis Akun</label>
                <select id="jenisAkun" name="jenis_akun" required>
                    <option value="" disabled selected>-- Pilih Jenis Akun --</option>
                    <option value="personal">Personal</option>
                    <option value="korporat">Perusahaan</option>
                </select>
            </div>

            <!-- FORM PERSONAL -->
            <div id="formPersonal" class="hidden">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama_lengkap"
                        placeholder="Nama lengkap" />
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input name="alamat" placeholder="Alamat lengkap" />
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input
                        type="tel"
                        name="telp_personal"
                        placeholder="08123456789"
                        inputmode="numeric"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                </div>
                <div class="form-group">
                    <label>Nomor NPWP</label>
                    <input type="text"
                        name="npwp_personal"
                        class="form-control"
                        placeholder="00.000.000.0-000.000"
                        pattern="\d{2}\.\d{3}\.\d{3}\.\d-\d{3}\.\d{3}"
                        maxlength="20">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email_personal"
                        placeholder="email@example.com" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        id="passwordPersonal"
                        name="password_personal"
                        placeholder="Password" />
                </div>
            </div>

            <!-- FORM KORPORAT -->
            <div id="formKorporat" class="hidden">
                <div class="form-group">
                    <label>Nama Perusahaan</label>
                    <input
                        type="text"
                        name="nama_perusahaan"
                        placeholder="Nama perusahaan" />
                </div>
                <div class="form-group">
                    <label>Alamat Perusahaan</label>
                    <input
                        name="alamat_perusahaan"
                        placeholder="Alamat perusahaan" />
                </div>
                <div class="form-group">
                    <label>Nomor NPWP Perusahaan</label>
                    <input type="text"
                        name="npwp_corporate"
                        class="form-control"
                        placeholder="00.000.000.0-000.000"
                        pattern="\d{2}\.\d{3}\.\d{3}\.\d-\d{3}\.\d{3}"
                        maxlength="20">
                </div>
                <div class="form-group">
                    <label>Nama PIC</label>
                    <input
                        type="text"
                        name="nama_pic"
                        placeholder="Nama PIC" />
                </div>
                <div class="form-group">
                    <label>Jabatan PIC</label>
                    <input
                        type="text"
                        name="jabatan_pic"
                        placeholder="Jabatan PIC" />
                </div>
                <div class="form-group">
                    <label>Nomor Telepon PIC</label>
                    <input
                        type="tel"
                        name="telp_pic"
                        placeholder="08xxxxxxxxxx"
                        inputmode="numeric"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                </div>
                <div class="form-group">
                    <label>Telepon Perusahaan</label>
                    <input
                        type="tel"
                        name="telp_perusahaan"
                        placeholder="021xxxxxxx"
                        inputmode="numeric"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
                </div>
                <div class="form-group">
                    <label>Email Perusahaan</label>
                    <input
                        type="email"
                        name="email_perusahaan"
                        placeholder="email@perusahaan.com" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        id="passwordKorporat"
                        name="password_korporat"
                        placeholder="Password" />
                </div>
            </div>

            <!-- Checkbox tampilkan password -->
            <div class="form-check">
                <input type="checkbox" id="togglePass" />
                <label for="togglePass">Tampilkan Password</label>
            </div>

            <button type="submit">Daftar</button>
            <div class="switch">
                Sudah punya akun? <a href="index.php">Login</a>
            </div>
        </form>

    </div>

    <script src="assets/js/auth.js"></script>
</body>

</html>