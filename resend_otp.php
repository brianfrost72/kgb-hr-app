<?php
require_once "dashboard/koneksi.php";
require_once "PHPMailer/otp_mail.php";
session_start();

header('Content-Type: application/json');

// ===== CEK SESSION =====
if (!isset($_SESSION['reg_data']['email'])) {
    echo json_encode([
        "success" => false,
        "message" => "Session email tidak ditemukan"
    ]);
    exit;
}

$email = $_SESSION['reg_data']['email'];

// ===== GENERATE OTP BARU =====
$otp = random_int(100000, 999999);
$expired = date("Y-m-d H:i:s", time() + 300); // 5 menit

// ===== SIMPAN OTP KE DATABASE =====
$insert = mysqli_query($conn, "
    INSERT INTO otp_verifikasi (email, otp_code, expired_at)
    VALUES ('$email', '$otp', '$expired')
");

if (!$insert) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menyimpan OTP"
    ]);
    exit;
}

// ===== KIRIM EMAIL OTP =====
$kirim = kirimOTP($email, $otp);

if (!$kirim) {
    echo json_encode([
        "success" => false,
        "message" => "Gagal mengirim email OTP"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "OTP baru berhasil dikirim"
]);
