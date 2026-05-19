<?php
require "dashboard/koneksi.php";
session_start();

header('Content-Type: application/json');

// ===== AMBIL OTP =====
$data = json_decode(file_get_contents("php://input"), true);
$otp_input = $data['otp'] ?? '';

if (!$otp_input) {
    echo json_encode(["success" => false, "message" => "OTP kosong"]);
    exit;
}

// ===== CEK SESSION DATA =====
if (!isset($_SESSION['reg_data'])) {
    echo json_encode(["success" => false, "message" => "Session reg_data hilang"]);
    exit;
}

$email = $_SESSION['reg_data']['email'];

// ===== CEK OTP =====
$q = mysqli_query($conn, "
    SELECT * FROM otp_verifikasi
    WHERE email='$email'
    ORDER BY id DESC
    LIMIT 1
");

$row = mysqli_fetch_assoc($q);

if (!$row) {
    echo json_encode(["success" => false, "message" => "OTP tidak ditemukan"]);
    exit;
}

if (trim($row['otp_code']) !== trim($otp_input)) {
    echo json_encode(["success" => false, "message" => "OTP salah"]);
    exit;
}

if (strtotime($row['expired_at']) < time()) {
    echo json_encode(["success" => false, "message" => "OTP expired"]);
    exit;
}

# =====================================================
# ===== INSERT USER KE DATABASE =======================
# =====================================================

# =====================================================
# INSERT USER + PROFILE (SAFE VERSION)
# =====================================================

$d = $_SESSION['reg_data'];

$conn->begin_transaction();

try {

    $now = date("Y-m-d H:i:s");

    /* ===== USERS ===== */

    $stmt = $conn->prepare("
        INSERT INTO users
        (email, password, user_type, id_client_type, created_at, update_at, is_online)
        VALUES (?,?,?,?,?,?,0)
    ");

    if (!$stmt) throw new Exception($conn->error);

    $user_type = 'client';
    $client_type = $d['client_type'];

    $stmt->bind_param(
        "ssssss",
        $d['email'],
        $d['password_hash'],
        $user_type,
        $client_type,
        $now,
        $now
    );

    if (!$stmt->execute()) throw new Exception($stmt->error);

    $user_id = $stmt->insert_id;
    $stmt->close();


    /* ===== PERSONAL PROFILE ===== */

    if ($client_type === 'personal') {

        $stmt = $conn->prepare("
            INSERT INTO client_personal_profile
            (user_id, full_name, address, phone_number)
            VALUES (?,?,?,?)
        ");

        if (!$stmt) throw new Exception($conn->error);

        $stmt->bind_param(
            "isss",
            $user_id,
            $d['full_name'],
            $d['address'],
            $d['phone_number']
        );

        if (!$stmt->execute()) throw new Exception($stmt->error);
        $stmt->close();
    }


    /* ===== KORPORAT PROFILE ===== */

    if ($client_type === 'korporat') {

        $stmt = $conn->prepare("
            INSERT INTO client_korporat_profile
            (user_id, company_name, company_address,
             pic_name, pic_position, pic_phone, company_phone)
            VALUES (?,?,?,?,?,?,?)
        ");

        if (!$stmt) throw new Exception($conn->error);

        $stmt->bind_param(
            "issssss",
            $user_id,
            $d['company_name'],
            $d['company_address'],
            $d['pic_name'],
            $d['pic_position'],
            $d['pic_phone'],
            $d['company_phone']
        );

        if (!$stmt->execute()) throw new Exception($stmt->error);
        $stmt->close();
    }

    $conn->commit();
} catch (Throwable $e) {

    $conn->rollback();

    echo json_encode([
        "success" => false,
        "message" => "DB ERROR: " . $e->getMessage()
    ]);
    exit;
}

# ===== TANDAI OTP TERPAKAI =====
mysqli_query($conn, "
    UPDATE otp_verifikasi SET is_used=1 WHERE id={$row['id']}
");

unset($_SESSION['reg_data']);

echo json_encode(["success" => true]);
