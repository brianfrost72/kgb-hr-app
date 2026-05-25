<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

$userId = $_SESSION['user_id'] ?? 0;

if (!$userId) {
    header('Location: ../../../index.php');
    exit;
}

// ================================
// AMBIL DATA
// ================================

$email    = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// ================================
// VALIDASI EMAIL
// ================================

if (empty($email)) {

    header('Location: ../edit_profile.php?error=email_kosong');
    exit;
}

// ================================
// CEK EMAIL DUPLIKAT
// ================================

$queryCheck = mysqli_query($conn, "
    SELECT id
    FROM users
    WHERE email = '$email'
    AND id != '$userId'
    LIMIT 1
");

if (mysqli_num_rows($queryCheck) > 0) {

    header('Location: ../edit_profile.php?error=email_sudah_ada');
    exit;
}

// ================================
// UPDATE EMAIL & PASSWORD
// ================================

if (!empty($password)) {

    $hashPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $update = mysqli_query($conn, "
        UPDATE users SET
            email = '$email',
            password = '$hashPassword',
            update_at = NOW()
        WHERE id = '$userId'
    ");
} else {

    $update = mysqli_query($conn, "
        UPDATE users SET
            email = '$email',
            update_at = NOW()
        WHERE id = '$userId'
    ");
}

// ================================
// RESULT
// ================================

if ($update) {

    header('Location: ../edit_profile.php?success=email');
} else {

    header('Location: ../edit_profile.php?error=gagal_update_email');
}

exit;
