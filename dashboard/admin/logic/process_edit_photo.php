<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

$userId = $_SESSION['user_id'] ?? 0;

if (!$userId) {
    header('Location: ../../../index.php');
    exit;
}

// ================================
// CEK FILE
// ================================

if (
    empty($_FILES['photo_profile']['name'])
) {

    header('Location: ../edit_profile.php?error=foto_kosong');
    exit;
}

// ================================
// AMBIL FOTO LAMA
// ================================

$queryOld = mysqli_query($conn, "
    SELECT
        full_name,
        photo_profile
    FROM user_profile
    WHERE user_id = '$userId'
    LIMIT 1
");

$dataOld = mysqli_fetch_assoc($queryOld);

$full_name    = $dataOld['full_name'] ?? 'user';
$oldPhoto     = $dataOld['photo_profile'] ?? '';

// ================================
// LOKASI UPLOAD
// ================================

$uploadDir = __DIR__ . '/../../assets/images/uploads/user_photos/';

// ================================
// CEK FOLDER
// ================================

if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

// ================================
// FILE INFO
// ================================

$fileTmp  = $_FILES['photo_profile']['tmp_name'];
$fileName = $_FILES['photo_profile']['name'];
$fileSize = $_FILES['photo_profile']['size'];

$fileExt = strtolower(
    pathinfo($fileName, PATHINFO_EXTENSION)
);

// ================================
// VALIDASI EXTENSION
// ================================

$allowed = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($fileExt, $allowed)) {

    header('Location: ../edit_profile.php?error=format_foto');
    exit;
}

// ================================
// VALIDASI SIZE (2MB)
// ================================

if ($fileSize > 2 * 1024 * 1024) {

    header('Location: ../edit_profile.php?error=size_foto');
    exit;
}

// ================================
// GENERATE FILE NAME
// ================================

$safeName = preg_replace(
    '/[^A-Za-z0-9\\-]/',
    '_',
    $full_name
);

$newFileName =
    $safeName .
    '_' .
    time() .
    '.' .
    $fileExt;

// ================================
// UPLOAD FILE
// ================================

if (
    move_uploaded_file(
        $fileTmp,
        $uploadDir . $newFileName
    )
) {

    // ================================
    // HAPUS FOTO LAMA
    // ================================

    if (!empty($oldPhoto)) {

        $oldPath = $uploadDir . $oldPhoto;

        if (file_exists($oldPath)) {

            unlink($oldPath);
        }
    }

    // ================================
    // UPDATE DATABASE
    // ================================

    $update = mysqli_query($conn, "
        UPDATE user_profile SET
            photo_profile = '$newFileName'
        WHERE user_id = '$userId'
    ");

    if ($update) {

        header('Location: ../edit_profile.php?success=foto');

    } else {

        header('Location: ../edit_profile.php?error=gagal_update_foto');
    }

} else {

    header('Location: ../edit_profile.php?error=upload_gagal');
}

exit;