<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

$userId = $_SESSION['user_id'] ?? 0;

if (!$userId) {
    header('Location: ../../../index.php');
    exit;
}

// ========================================
// FUNCTION ESCAPE
// ========================================

function esc($conn, $value)
{
    return mysqli_real_escape_string(
        $conn,
        trim($value ?? '')
    );
}

// ========================================
// AMBIL DATA FORM
// ========================================

// USER PROFILE
$full_name       = esc($conn, $_POST['full_name']);
$ktp_number      = esc($conn, $_POST['ktp_number']);
$tempat_lahir    = esc($conn, $_POST['tempat_lahir']);
$tanggal_lahir   = esc($conn, $_POST['tanggal_lahir']);
$jenis_kelamin   = esc($conn, $_POST['jenis_kelamin']);
$status_kawin    = esc($conn, $_POST['status_kawin']);
$address         = esc($conn, $_POST['address']);
$phone_number    = esc($conn, $_POST['phone_number']);
$npwp            = esc($conn, $_POST['npwp']);
$bpjs_kesehatan  = esc($conn, $_POST['bpjs_kesehatan']);
$bpjstk          = esc($conn, $_POST['bpjstk']);
$no_rekening     = esc($conn, $_POST['no_rekening']);
$linkedin        = esc($conn, $_POST['linkedin']);
$instagram       = esc($conn, $_POST['instagram']);

// USERS
$email           = esc($conn, $_POST['email']);
$password        = $_POST['password'] ?? '';

$role_id         = esc($conn, $_POST['role_id']);
$region_id       = esc($conn, $_POST['region_id']);
$department_id   = esc($conn, $_POST['department_id']);
$position_id     = esc($conn, $_POST['position_id']);

// ========================================
// VALIDASI EMAIL
// ========================================

if (!empty($email)) {

    $checkEmail = mysqli_query($conn, "
        SELECT id
        FROM users
        WHERE email = '$email'
        AND id != '$userId'
        LIMIT 1
    ");

    if (mysqli_num_rows($checkEmail) > 0) {

        header('Location: ../edit_profile.php?error=email_exists');
        exit;
    }
}

// ========================================
// AUTO GENERATE ID NUMBER
// ========================================

$tahun = date('y');

$id_number =
    $tahun .
    str_pad($userId, 4, '0', STR_PAD_LEFT);

// ========================================
// CEK PROFILE
// ========================================

$checkProfile = mysqli_query($conn, "
    SELECT *
    FROM user_profile
    WHERE user_id = '$userId'
    LIMIT 1
");

if (mysqli_num_rows($checkProfile) == 0) {

    mysqli_query($conn, "
        INSERT INTO user_profile (
            user_id,
            status
        ) VALUES (
            '$userId',
            'active'
        )
    ");
}

$dataProfile = mysqli_fetch_assoc($checkProfile);

// ========================================
// FOTO PROFILE
// ========================================

$photo_profile = $dataProfile['photo_profile'] ?? '';

// PATH FIXED
$uploadDir = 'C:/xampp/htdocs/my-dashboard/fixed-v2/dashboard/assets/images/uploads/user_photos/';

// BUAT FOLDER
if (!is_dir($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

// ========================================
// UPLOAD FOTO
// ========================================

if (!empty($_FILES['photo_profile']['name'])) {

    $fileTmp  = $_FILES['photo_profile']['tmp_name'];
    $fileName = $_FILES['photo_profile']['name'];
    $fileSize = $_FILES['photo_profile']['size'];

    $fileExt = strtolower(
        pathinfo($fileName, PATHINFO_EXTENSION)
    );

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    // VALIDASI FORMAT
    if (!in_array($fileExt, $allowed)) {

        header('Location: ../edit_profile.php?error=format_foto');
        exit;
    }

    // VALIDASI SIZE 2MB
    if ($fileSize > 2 * 1024 * 1024) {

        header('Location: ../edit_profile.php?error=size_foto');
        exit;
    }

    // SAFE FILE NAME
    $safeName = preg_replace(
        '/[^A-Za-z0-9\-]/',
        '_',
        $full_name
    );

    $newFileName =
        $safeName .
        '_' .
        time() .
        '.' .
        $fileExt;

    // MOVE FILE
    if (
        move_uploaded_file(
            $fileTmp,
            $uploadDir . $newFileName
        )
    ) {

        // HAPUS FOTO LAMA
        if (!empty($photo_profile)) {

            $oldPath = $uploadDir . $photo_profile;

            if (file_exists($oldPath)) {

                unlink($oldPath);
            }
        }

        $photo_profile = $newFileName;
    }
}

// ========================================
// UPDATE USER_PROFILE
// ========================================

$updateProfile = mysqli_query($conn, "
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
        linkedin = '$linkedin',
        instagram = '$instagram',
        photo_profile = '$photo_profile'
    WHERE user_id = '$userId'
");

// ========================================
// UPDATE USERS
// ========================================

if (!empty($password)) {

    $hashPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $updateUsers = mysqli_query($conn, "
        UPDATE users SET
            email = '$email',
            password = '$hashPassword',
            role_id = '$role_id',
            region_id = '$region_id',
            department_id = '$department_id',
            position_id = '$position_id',
            update_at = NOW()
        WHERE id = '$userId'
    ");

} else {

    $updateUsers = mysqli_query($conn, "
        UPDATE users SET
            email = '$email',
            role_id = '$role_id',
            region_id = '$region_id',
            department_id = '$department_id',
            position_id = '$position_id',
            update_at = NOW()
        WHERE id = '$userId'
    ");
}

// ========================================
// RESULT
// ========================================

if ($updateProfile && $updateUsers) {

    header('Location: ../edit_profile.php?success=1');

} else {

    header('Location: ../edit_profile.php?error=update_failed');
}

exit;