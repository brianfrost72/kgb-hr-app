<?php
require_once "../dashboard/koneksi.php";
require 'autoload.php';

if (!isset($conn) || !$conn) {
    die('Database connection tidak tersedia.');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../reset_password.php");
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    $_SESSION['reset_error'] = "Email wajib diisi.";
    header("Location: ../reset_password.php");
    exit;
}

/* =========================
   CEK EMAIL USER
========================= */

$q = $conn->prepare("
    SELECT 
        id,
        email,
        user_type
    FROM users
    WHERE email = ?
    LIMIT 1
");

if (!$q) {
    die("Prepare failed: " . $conn->error);
}

$q->bind_param("s", $email);
$q->execute();

$result = $q->get_result();

if ($result->num_rows === 0) {

    $_SESSION['reset_error'] = "Email Anda tidak terdaftar di sistem kami.";
    header("Location: ../reset_password.php");
    exit;
}

$user = $result->fetch_assoc();
$q->close();

$user_id      = $user['id'];
$account_type = $user['user_type'];

/* =========================
   GENERATE TEMP PASSWORD
========================= */

$tempPass = substr(str_shuffle(
    "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789"
), 0, 10);

$hashPass = password_hash($tempPass, PASSWORD_DEFAULT);

/* =========================
   UPDATE PASSWORD USER
========================= */

$update = $conn->prepare("
    UPDATE users
    SET 
        password = ?,
        update_at = NOW()
    WHERE id = ?
");

if (!$update) {
    die("Prepare update failed: " . $conn->error);
}

$update->bind_param("si", $hashPass, $user_id);

if (!$update->execute()) {

    $_SESSION['reset_error'] = "Gagal memperbarui password.";
    header("Location: ../reset_password.php");
    exit;
}

$update->close();


/* =========================
   SMTP SEND
========================= */

$mail = new PHPMailer(true);

$status = "terkirim";

try {

    $mail->isSMTP();
    $mail->Host = 'mail.konig.co.id';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@konig.co.id';
    $mail->Password = 'Konig*2025@';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->CharSet = 'UTF-8';

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom('no-reply@konig.co.id', 'Konig Guard Bureau');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Sementara Akun Anda';

    $mail->Body = '
    <div style="max-width:600px;margin:0 auto;font-family:Arial,Helvetica,sans-serif;background:#ffffff;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden">

    <!-- HEADER -->
    <div style="display:flex;align-items:center;padding:16px 20px;border-bottom:1px solid #e5e5e5;background:#f9fafb">
        <img src="https://konig.co.id/assets/images/logo/logo.png" alt="Logo-email" style="height:50px;margin-right:15px">
        <div style="font-size:13px;color:#555">
            <strong style="font-size:15px;color:#111">Konig Guard Bureau</strong><br>
            Puri Botanical Blok H9 No.11, Jakarta - Indonesia.<br>
            Telp:
            <a href="tel:08111902759" style="color:#2563eb;text-decoration:none">
                0811 1902 759
            </a>
        </div>
    </div>

    <!-- CONTENT -->
    <div style="padding:30px 20px;text-align:center">
        <h2 style="margin:0 0 10px;color:#111">Reset Password Sementara</h2>
        <p style="margin:0 0 15px;color:#111;font-size:14px">
            Yth. Pengguna,
        </p>

        <p style="margin:0 0 15px;color:#555;font-size:14px">
            Permintaan reset password Anda telah diproses.
        </p>

        <p style="margin:20px 0 8px;color:#111;font-size:14px">
            <strong>Password sementara:</strong>
        </p>

        <div style="display:inline-block;padding:14px 25px;font-size:22px;font-weight:bold;letter-spacing:2px;background:#f1f5f9;color:#111;border-radius:6px">
            ' . $tempPass . '
        </div>

        <p style="margin-top:20px;color:#555;font-size:14px">
            Silakan <strong>copy-paste</strong> password sementara tersebut untuk login.
            Demi keamanan akun Anda, mohon segera mengganti password setelah berhasil masuk.
        </p>

        <p style="margin-top:25px;color:#555;font-size:14px">
            Hormat kami,<br>
            <strong>Konig Guard Bureau</strong>
        </p>
    </div>

    <!-- FOOTER -->
    <div style="padding:15px 20px;background:#f9fafb;border-top:1px solid #e5e5e5;font-size:12px;color:#777;text-align:center">
        Email ini dikirim secara otomatis. Mohon <strong>tidak membalas</strong> email ini.
    </div>

</div>
';

    $mail->send();
} catch (Exception $e) {

    $status = "gagal";
}

/* =========================
   INSERT LOG RESET PASSWORD
========================= */

$insertLog = $conn->prepare("
    INSERT INTO reset_password
    (
        user_id,
        account_type,
        email,
        reset_token,
        created_at,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        NOW(),
        ?
    )
");

if ($insertLog) {

    $insertLog->bind_param(
        "issss",
        $user_id,
        $account_type,
        $email,
        $tempPass,
        $status
    );

    $insertLog->execute();
}

$insertLog->close();

/* =========================
   RESULT
========================= */

if ($status === "terkirim") {

    $_SESSION['reset_success'] = true;
} else {

    $_SESSION['reset_error'] = "Reset berhasil, namun email gagal dikirim.";
}

header("Location: ../reset_password.php");
exit;
