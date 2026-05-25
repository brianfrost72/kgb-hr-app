<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

$userId = $_SESSION['user_id'] ?? 0;

if (!$userId) {
    header('Location: ../../../index.php');
    exit;
}

// ================================
// AMBIL DATA FORM
// ================================
$full_name       = mysqli_real_escape_string($conn, $_POST['full_name']);
$ktp_number      = mysqli_real_escape_string($conn, $_POST['ktp_number']);
$tempat_lahir    = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
$tanggal_lahir   = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
$jenis_kelamin   = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
$status_kawin    = mysqli_real_escape_string($conn, $_POST['status_kawin']);
$address         = mysqli_real_escape_string($conn, $_POST['address']);
$phone_number    = mysqli_real_escape_string($conn, $_POST['phone_number']);
$npwp            = mysqli_real_escape_string($conn, $_POST['npwp']);
$bpjs_kesehatan  = mysqli_real_escape_string($conn, $_POST['bpjs_kesehatan']);
$bpjstk          = mysqli_real_escape_string($conn, $_POST['bpjstk']);
$no_rekening     = mysqli_real_escape_string($conn, $_POST['no_rekening']);
$id_department   = mysqli_real_escape_string($conn, $_POST['id_department']);
$id_position     = mysqli_real_escape_string($conn, $_POST['id_position']);
$id_region       = mysqli_real_escape_string($conn, $_POST['id_region']);
$id_roles        = mysqli_real_escape_string($conn, $_POST['id_roles']);
$linkedin        = mysqli_real_escape_string($conn, $_POST['linkedin']);
$instagram       = mysqli_real_escape_string($conn, $_POST['instagram']);
$status          = mysqli_real_escape_string($conn, $_POST['status']);
$email           = mysqli_real_escape_string($conn, $_POST['email']);
$password        = $_POST['password'];
// ================================
// AUTO GENERATE ID NUMBER
// ================================

$tahun = date('y');

$id_number = $tahun .
    str_pad($userId, 3, '0', STR_PAD_LEFT);

// ================================
// FOTO PROFILE
// ================================
$photo_profile = '';

$queryOld = mysqli_query($conn, "
    SELECT photo_profile
    FROM user_profile
    WHERE user_id = '$userId'
    LIMIT 1
");

$dataOld = mysqli_fetch_assoc($queryOld);

$photo_profile = $dataOld['photo_profile'] ?? '';

if (!empty($_FILES['photo_profile']['name'])) {

    $uploadDir = __DIR__ . '/../../assets/images/uploads/user_photos/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp  = $_FILES['photo_profile']['tmp_name'];
    $fileName = $_FILES['photo_profile']['name'];
    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($fileExt, $allowed)) {

        $safeName = preg_replace('/[^A-Za-z0-9\\-]/', '_', $full_name);

        $newFileName = $safeName . '_' . time() . '.' . $fileExt;

        move_uploaded_file(
            $fileTmp,
            $uploadDir . $newFileName
        );

        // Hapus file lama
        if (!empty($photo_profile)) {

            $oldPath = $uploadDir . $photo_profile;

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $photo_profile = $newFileName;
    }
}

// ================================
// UPDATE USER PROFILE
// ================================
mysqli_query($conn, "
    UPDATE user_profile SET
        full_name = '$full_name',
        id_number = '$id_number',
        ktp_number = '$ktp_number',
        tempat_lahir = '$tempat_lahir',
        tanggal_lahir = '$tanggal_lahir',
        jenis_kelamin = '$jenis_kelamin',
        status_kawin = '$status_kawin',
        address = '$address',
        phone_number = '$phone_number',
        npwp = '$npwp',
        bpjs_kesehatan = '$bpjs_kesehatan',
        bpjstk = '$bpjstk',
        no_rekening = '$no_rekening',
        id_department = '$id_department',
        id_position = '$id_position',
        id_region = '$id_region',
        id_roles = '$id_roles',
        linkedin = '$linkedin',
        instagram = '$instagram',
        photo_profile = '$photo_profile',
        status = '$status'
    WHERE user_id = '$userId'
");

// ================================
// UPDATE USERS
// ================================
if (!empty($password)) {

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "
        UPDATE users SET
            email = '$email',
            password = '$hashPassword',
            role_id = '$id_roles',
            region_id = '$id_region',
            department_id = '$id_department',
            position_id = '$id_position',
            update_at = NOW()
        WHERE id = '$userId'
    ");
} else {

    mysqli_query($conn, "
        UPDATE users SET
            email = '$email',
            role_id = '$id_roles',
            region_id = '$id_region',
            department_id = '$id_department',
            position_id = '$id_position',
            update_at = NOW()
        WHERE id = '$userId'
    ");
}

header('Location: ../edit_profile.php?success=1');
exit;
