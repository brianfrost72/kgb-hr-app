<?php
require_once "dashboard/koneksi.php";
require_once "PHPMailer/otp_mail.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registrasi.php");
    exit;
}

function clean($v)
{
    return trim(htmlspecialchars($v));
}

$jenis_akun = $_POST['jenis_akun'] ?? '';

if (!in_array($jenis_akun, ['personal', 'korporat'])) {
    $_SESSION['reg_error'] = "Jenis akun tidak valid";
    header("Location: registrasi.php");
    exit;
}

/* =========================
   VALIDASI + SIMPAN SESSION
========================= */

if ($jenis_akun === 'personal') {

    $nama   = clean($_POST['nama_lengkap']);
    $alamat = clean($_POST['alamat']);
    $telp   = clean($_POST['telp_personal']);
    $npwp   = clean($_POST['npwp_personal']);
    $email  = clean($_POST['email_personal']);
    $pass   = $_POST['password_personal'];

    if (!$nama || !$alamat || !$telp || !$npwp || !$email || !$pass) {
        $_SESSION['reg_error'] = "Semua field personal wajib diisi";
        header("Location: registrasi.php");
        exit;
    }

    $_SESSION['reg_data'] = [
        'jenis' => 'personal',

        // USERS
        'email' => $email,
        'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
        'user_type' => 'client',
        'client_type' => 'personal',

        // PROFILE
        'full_name' => $nama,
        'address' => $alamat,
        'phone_number' => $telp,
        'npwp_number' => $npwp
    ];
}

/* ========================= */

if ($jenis_akun === 'korporat') {

    $company_name    = clean($_POST['nama_perusahaan']);
    $company_address = clean($_POST['alamat_perusahaan']);
    $npwp_corp       = clean($_POST['npwp_corporate']);
    $pic_name        = clean($_POST['nama_pic']);
    $pic_position    = clean($_POST['jabatan_pic']);
    $pic_phone       = clean($_POST['telp_pic']);
    $company_phone   = clean($_POST['telp_perusahaan']);
    $email           = clean($_POST['email_perusahaan']);
    $pass            = $_POST['password_korporat'];

    if (!$company_name || !$company_address || !$npwp_corp || !$pic_name || !$pic_position || !$pic_phone || !$email || !$pass) {
        $_SESSION['reg_error'] = "Semua field perusahaan wajib diisi";
        header("Location: registrasi.php");
        exit;
    }

    $_SESSION['reg_data'] = [
        'jenis' => 'korporat',

        // USERS
        'email' => $email,
        'password_hash' => password_hash($pass, PASSWORD_DEFAULT),
        'user_type' => 'client',
        'client_type' => 'korporat',

        // PROFILE
        'company_name' => $company_name,
        'company_address' => $company_address,
        'npwp_number' => $npwp_corp,
        'pic_name' => $pic_name,
        'pic_position' => $pic_position,
        'pic_phone' => $pic_phone,
        'company_phone' => $company_phone
    ];
}

/* =========================
   CEK EMAIL SUDAH ADA?
========================= */

$email = $_SESSION['reg_data']['email'];

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['reg_error'] = "Email sudah terdaftar";
    header("Location: registrasi.php");
    exit;
}
$stmt->close();

/* =========================
   GENERATE OTP
========================= */

$otp = random_int(100000, 999999);
$expired = date("Y-m-d H:i:s", time() + 300);

/* hapus OTP lama jika ada */
$del = $conn->prepare("DELETE FROM otp_verifikasi WHERE email = ?");
$del->bind_param("s", $email);
$del->execute();
$del->close();

/* insert OTP baru */
$stmt = $conn->prepare("
    INSERT INTO otp_verifikasi (email, otp_code, expired_at)
    VALUES (?, ?, ?)
");
$stmt->bind_param("sss", $email, $otp, $expired);
$stmt->execute();
$stmt->close();

/* =========================
   KIRIM EMAIL OTP
========================= */

kirimOTP($email, $otp);

/* ========================= */

header("Location: verifikasi_akun.php");
exit;
