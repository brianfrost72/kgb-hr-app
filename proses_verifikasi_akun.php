<?php
require_once "dashboard/koneksi.php";
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['reg_data'])) {
    echo json_encode([
        "success" => false,
        "message" => "Session registrasi tidak ditemukan"
    ]);
    exit;
}

/* ========================
   AMBIL INPUT JSON
======================== */

$input = json_decode(file_get_contents("php://input"), true);
$otp = $input['otp'] ?? '';

if (!$otp) {
    echo json_encode([
        "success" => false,
        "message" => "OTP kosong"
    ]);
    exit;
}

$email = $_SESSION['reg_data']['email'];

/* ========================
   VALIDASI OTP
======================== */

$stmt = $conn->prepare("
SELECT id FROM otp_verifikasi
WHERE email=?
AND otp_code=?
AND is_used=0
AND expired_at > NOW()
ORDER BY id DESC
LIMIT 1
");

$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode([
        "success" => false,
        "message" => "OTP salah atau expired"
    ]);
    exit;
}

$row = $result->fetch_assoc();
$otp_id = $row['id'];
$stmt->close();

/* ========================
   START TRANSACTION
======================== */

$conn->begin_transaction();

try {

    /* tandai OTP terpakai */
    $u = $conn->prepare("UPDATE otp_verifikasi SET is_used=1 WHERE id=?");
    $u->bind_param("i", $otp_id);
    $u->execute();
    $u->close();

    $data = $_SESSION['reg_data'];

    /* ========================
       INSERT USERS
    ======================== */

    $user_type = 'client';
    $client_type = $data['client_type']; // personal | korporat
    $now = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("
        INSERT INTO users
        (email, password, user_type, id_client_type, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssss",
        $data['email'],
        $data['password_hash'],
        $user_type,
        $client_type,
        $now,
        $now
    );

    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    /* ========================
       INSERT PROFILE
    ======================== */

    if ($client_type === 'personal') {

        $stmt = $conn->prepare("
            INSERT INTO client_personal_profile
            (user_id, full_name, address, phone_number, npwp_number)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "issss",
            $user_id,
            $data['full_name'],
            $data['address'],
            $data['phone_number'],
            $data['npwp_number']
        );

        $stmt->execute();
        $stmt->close();
    }

    if ($client_type === 'korporat') {

        $stmt = $conn->prepare("
            INSERT INTO client_korporat_profile
            (user_id, company_name, company_address, npwp_number, pic_name, pic_position, pic_phone, company_phone)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssssss",
            $user_id,
            $data['company_name'],
            $data['company_address'],
            $data['npwp_number'],
            $data['pic_name'],
            $data['pic_position'],
            $data['pic_phone'],
            $data['company_phone']
        );

        $stmt->execute();
        $stmt->close();
    }

    /* ========================
       COMMIT
    ======================== */

    $conn->commit();

    unset($_SESSION['reg_data']);

    echo json_encode([
        "success" => true
    ]);
} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([
        "success" => false,
        "message" => "Gagal membuat akun"
    ]);
}
