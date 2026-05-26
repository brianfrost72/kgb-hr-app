<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

// ================================
// AMBIL USER LOGIN
// ================================
$userId = $_SESSION['user_id'] ?? 0;

if (!$userId) {
    header('Location: ../login.php');
    exit;
}

// ================================
// AMBIL DATA PROFILE + USERS
// ================================
$queryUser = mysqli_query($conn, "
    SELECT
        up.*,
        u.email,
        u.password,
        u.role_id,
        u.region_id,
        u.department_id,
        u.position_id,
        r.role_name,
        rg.region_name,
        d.department_name,
        p.position_name
    FROM user_profile up
    LEFT JOIN users u ON u.id = up.user_id
    LEFT JOIN roles r ON r.id = u.role_id
    LEFT JOIN regions rg ON rg.id = u.region_id
    LEFT JOIN departments d ON d.id = u.department_id
    LEFT JOIN positions p ON p.id = u.position_id
    WHERE up.user_id = '$userId'
    LIMIT 1
");

if (!$queryUser) {
    die(mysqli_error($conn));
}

$dataUser = mysqli_fetch_assoc($queryUser);

// ================================
// JIKA PROFILE BELUM ADA
// ================================
if (!$dataUser) {

    mysqli_query($conn, "
        INSERT INTO user_profile (
            user_id,
            status
        ) VALUES (
            '$userId',
            'active'
        )
    ");

    $queryUser = mysqli_query($conn, "
    SELECT
        up.*,
        u.email,
        u.password,
        u.role_id,
        u.region_id,
        u.department_id,
        u.position_id,
        r.role_name,
        rg.region_name,
        d.department_name,
        p.position_name
    FROM user_profile up
    LEFT JOIN users u ON u.id = up.user_id
    LEFT JOIN roles r ON r.id = u.role_id
    LEFT JOIN regions rg ON rg.id = u.region_id
    LEFT JOIN departments d ON d.id = u.department_id
    LEFT JOIN positions p ON p.id = u.position_id
    WHERE up.user_id = '$userId'
    LIMIT 1
");

    $dataUser = mysqli_fetch_assoc($queryUser);
}

// ================================
// DROPDOWN ROLES
// ================================
$queryRoles = mysqli_query($conn, "
    SELECT * FROM roles
    ORDER BY role_name ASC
");

// ================================
// DROPDOWN REGIONS / CABANG
// ================================
$queryRegions = mysqli_query($conn, "
    SELECT * FROM regions
    ORDER BY region_name ASC
");

// ================================
// DROPDOWN DEPARTMENT
// ================================
$queryDepartment = mysqli_query($conn, "
    SELECT * FROM departments
    ORDER BY department_name ASC
");

// ================================
// DROPDOWN POSITIONS
// ================================
$queryPositions = mysqli_query($conn, "
    SELECT * FROM positions
    ORDER BY position_name ASC
");

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Profil - Dashboard | Konig Guard Bureau</title>


    <!-- Perfect Scrollbar -->
    <link
        type="text/css"
        href="../assets/vendor/perfect-scrollbar.css"
        rel="stylesheet" />

    <!-- App CSS -->
    <link type="text/css" href="../assets/css/app.css" rel="stylesheet" />

    <!-- Material Design Icons -->
    <link
        type="text/css"
        href="../assets/css/vendor-material-icons.css"
        rel="stylesheet" />

    <!-- Font Awesome FREE Icons -->
    <link
        type="text/css"
        href="../assets/css/vendor-fontawesome-free.css"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Flatpickr -->
    <link
        type="text/css"
        href="../assets/css/vendor-flatpickr.css"
        rel="stylesheet" />
    <link
        type="text/css"
        href="../assets/css/vendor-flatpickr-airbnb.css"
        rel="stylesheet" />
</head>

<body class="layout-fluid layout-sticky-subnav">
    <div class="preloader"></div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">
        <!-- **********************************Top Header********************************** -->
        <?php include 'includes/topheader.php'; ?>
        <!-- **********************************// END Top Header //********************************** -->

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content page">
            <div class="page__header">
                <div class="container-fluid page__heading-container">
                    <div class="page__heading d-flex align-items-end">
                        <div class="flex">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit Profil
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Edit Profil</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="row">

                    <!-- LEFT CONTENT -->
                    <div class="col-lg-8 my-4">

                        <div class="card border-0 shadow-sm" style="border-radius:20px;">
                            <div class="card-body p-4">

                                <!-- TITLE -->
                                <div class="d-flex align-items-start mb-4">

                                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                        style="width:60px; height:60px;
                                                background:#edf3ff; color:#4a6cf7;">

                                        <span class="material-icons">
                                            person_outline
                                        </span>

                                    </div>

                                    <div>

                                        <h4 class="mb-1 font-weight-bold">
                                            Informasi Pribadi & Data Karyawan
                                        </h4>

                                        <p class="text-muted mb-0">
                                            Lengkapi dan perbarui informasi data diri Anda.
                                        </p>

                                    </div>

                                </div>

                                <form method="POST"
                                    action="logic/process_edit_profile.php"
                                    enctype="multipart/form-data">
                                    <div class="row">

                                        <!-- FULL NAME -->
                                        <div class="col-12 mb-4">

                                            <label class="font-weight-medium">
                                                Nama Lengkap
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%; transform:translateY(-50%);
                                                        color:#8b95a7; font-size:21px;">
                                                    person
                                                </span>

                                                <input type="text"
                                                    class="form-control" name="full_name"
                                                    value="<?= htmlspecialchars($dataUser['full_name'] ?? ''); ?>"
                                                    style="height:55px; padding-left:52px;
                                                        border-radius:12px; background:#fff;
                                                        border:1px solid #dfe5ef; box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- ID NUMBER -->
                                        <div class="col-12 mb-4">

                                            <label class="font-weight-medium">
                                                Nomor ID
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                        transform:translateY(-50%); color:#8b95a7;
                                                        font-size:21px;">
                                                    person
                                                </span>

                                                <input type="text"
                                                    name="id_number"
                                                    class="form-control"
                                                    value="<?= !empty($dataUser['id_number'])
                                                                ? htmlspecialchars($dataUser['id_number'])
                                                                : date('y') . str_pad($userId, 3, '0', STR_PAD_LEFT); ?>"
                                                    readonly
                                                    style="height:55px; padding-left:52px;
    border-radius:12px; background:#fff;
    border:1px solid #dfe5ef; box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- TEMPAT LAHIR -->
                                        <div class="col-md-6 mb-4">

                                            <label class="font-weight-medium">
                                                Tempat Lahir
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                        transform:translateY(-50%); color:#8b95a7;
                                                        font-size:21px;">
                                                    location_on
                                                </span>

                                                <input type="text"
                                                    name="tempat_lahir"
                                                    class="form-control" value="<?= htmlspecialchars($dataUser['tempat_lahir'] ?? ''); ?>"
                                                    placeholder="Masukkan tempat lahir"
                                                    style="height:55px; padding-left:52px;
                                                        border-radius:12px; background:#fff;
                                                        border:1px solid #dfe5ef;box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- TANGGAL LAHIR -->
                                        <div class="col-md-6 mb-4">

                                            <label class="font-weight-medium">
                                                Tanggal Lahir
                                            </label>

                                            <div class="position-relative tanggal-lahir-wrapper">



                                                <!-- INPUT -->
                                                <input type="text"
                                                    id="tanggalLahir"
                                                    name="tanggal_lahir"
                                                    class="form-control" value="<?= htmlspecialchars($dataUser['tanggal_lahir'] ?? ''); ?>"
                                                    placeholder="Pilih tanggal lahir"
                                                    style="height:55px; border-radius:12px;
                                                        background:#fff; border:1px solid #dfe5ef;
                                                        box-shadow:none;">

                                            </div>

                                            <!-- UMUR -->
                                            <div class="mt-3"
                                                style="background:#f5f8ff; border:1px solid #dfe7ff;
                                                    border-radius:12px; padding:14px 18px;">

                                                <div class="text-muted mb-1"
                                                    style="font-size:13px;">
                                                    Umur
                                                </div>

                                                <div id="hasilUmur"
                                                    style="font-size:22px; font-weight:700;
                                                color:#2962ff; line-height:1;">
                                                    -
                                                </div>

                                            </div>

                                        </div>

                                        <!-- JENIS KELAMIN -->
                                        <div class="col-md-6 mb-4">

                                            <label class="font-weight-medium">
                                                Jenis Kelamin
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                        transform:translateY(-50%);
                                                        color:#8b95a7; font-size:21px;
                                                        z-index:2;">
                                                    groups
                                                </span>

                                                <select name="jenis_kelamin" class="form-control"
                                                    style="
                                        height:55px;
                                        padding-left:52px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;">

                                                    <option value="">Pilih Jenis Kelamin</option>

                                                    <option value="Laki-laki"
                                                        <?= ($dataUser['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : ''; ?>>
                                                        Laki-laki
                                                    </option>

                                                    <option value="Perempuan"
                                                        <?= ($dataUser['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : ''; ?>>
                                                        Perempuan
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                        <!-- STATUS -->
                                        <div class="col-md-6 mb-4">

                                            <label class="font-weight-medium">
                                                Status Pernikahan
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%); color:#8b95a7;
                                                    font-size:21px; z-index:2;">
                                                    favorite_border
                                                </span>

                                                <select name="status_kawin" class="form-control"
                                                    style="
                                        height:55px;
                                        padding-left:52px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;">

                                                    <option value="">Pilih Status Kawin</option>

                                                    <option value="Menikah"
                                                        <?= ($dataUser['status_kawin'] ?? '') == 'Menikah' ? 'selected' : ''; ?>>
                                                        Menikah
                                                    </option>

                                                    <option value="Belum Menikah"
                                                        <?= ($dataUser['status_kawin'] ?? '') == 'Belum Menikah' ? 'selected' : ''; ?>>
                                                        Belum Menikah
                                                    </option>

                                                </select>

                                            </div>

                                        </div>

                                        <!-- ROLE -->
                                        <div class="col-md-6 mb-4">

                                            <label class="font-weight-medium">
                                                ROLE
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
            transform:translateY(-50%);
            color:#8b95a7; font-size:21px; z-index:2;">
                                                    accessibility
                                                </span>

                                                <!-- TAMPILAN ROLE -->
                                                <input type="text"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['role_name'] ?? 'Belum Ada Role'); ?>"
                                                    readonly
                                                    style="
                height:55px;
                padding-left:52px;
                border-radius:12px;
                background:#f5f7fb;
                border:1px solid #dfe5ef;
                box-shadow:none;
                cursor:not-allowed;
            ">

                                                <!-- HIDDEN INPUT -->
                                                <input type="hidden"
                                                    name="role_id"
                                                    value="<?= htmlspecialchars($dataUser['role_id'] ?? ''); ?>">

                                            </div>

                                        </div>

                                        <!-- NO HP -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                No. Hp
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%); color:#8b95a7;
                                                    font-size:21px;">
                                                    call
                                                </span>

                                                <input type="text" name="phone_number"
                                                    class="form-control"
                                                    placeholder="08xxxxxxxxxx"
                                                    value="<?= htmlspecialchars($dataUser['phone_number'] ?? ''); ?>"
                                                    style=" height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">
                                            </div>

                                        </div>

                                        <!-- ALAMAT -->
                                        <div class="col-12 mb-4">

                                            <label class="font-weight-medium">
                                                Alamat
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:18px;
                                                    color:#8b95a7; font-size:21px;">
                                                    home
                                                </span>

                                                <textarea class="form-control" name="address"
                                                    rows="4"
                                                    placeholder="Masukkan alamat lengkap"
                                                    style="padding-left:52px; border-radius:12px;
                                                    background:#fff; border:1px solid #dfe5ef;
                                                    box-shadow:none; resize:none;"><?= htmlspecialchars($dataUser['address'] ?? ''); ?></textarea>

                                            </div>

                                        </div>

                                        <!-- DEPARTEMEN -->
                                        <div class="col-md-4 mb-4">

                                            <label class="font-weight-medium">
                                                Departemen
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;
                                                    z-index:2;">
                                                    business_center
                                                </span>

                                                <select name="department_id" class="form-control"
                                                    style="
                                        height:55px;
                                        padding-left:52px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;">

                                                    <option value="">--Pilih Department--</option>

                                                    <?php while ($department = mysqli_fetch_assoc($queryDepartment)) : ?>

                                                        <option value="<?= $department['id']; ?>"
                                                            <?= ($dataUser['department_id'] ?? '') == $department['id'] ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($department['department_name']); ?>
                                                        </option>

                                                    <?php endwhile; ?>

                                                </select>

                                            </div>

                                        </div>

                                        <!-- JABATAN -->
                                        <div class="col-md-4 mb-4">

                                            <label class="font-weight-medium">
                                                Jabatan
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;
                                                    z-index:2;">
                                                    work
                                                </span>

                                                <select name="position_id" class="form-control"
                                                    style="
                                        height:55px;
                                        padding-left:52px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;">

                                                    <option value="">--Pilih Jabatan--</option>

                                                    <?php while ($position = mysqli_fetch_assoc($queryPositions)) : ?>

                                                        <option value="<?= $position['id']; ?>"
                                                            <?= ($dataUser['position_id'] ?? '') == $position['id'] ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($position['position_name']); ?>
                                                        </option>

                                                    <?php endwhile; ?>

                                                </select>

                                            </div>

                                        </div>

                                        <!-- CABANG -->
                                        <div class="col-md-4 mb-4">

                                            <label class="font-weight-medium">
                                                Cabang
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%); color:#8b95a7;
                                                    font-size:21px; z-index:2;">
                                                    domain
                                                </span>

                                                <select name="region_id" class="form-control"
                                                    style="
                                        height:55px;
                                        padding-left:52px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;">

                                                    <option value="">--Pilih Cabang--</option>

                                                    <?php while ($region = mysqli_fetch_assoc($queryRegions)) : ?>

                                                        <option value="<?= $region['id']; ?>"
                                                            <?= ($dataUser['region_id'] ?? '') == $region['id'] ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($region['region_name']); ?>
                                                        </option>

                                                    <?php endwhile; ?>

                                                </select>

                                            </div>

                                        </div>

                                        <!-- KTP -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                Nomor KTP
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                    credit_card
                                                </span>

                                                <input type="text" name="ktp_number"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['ktp_number'] ?? ''); ?>"
                                                    placeholder="Masukkan nomor KTP"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- NPWP -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                Nomor NPWP
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                    description
                                                </span>

                                                <input type="text" name="npwp"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['npwp'] ?? ''); ?>"
                                                    placeholder="Masukkan nomor NPWP"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- BPJS KESEHATAN -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                No. BPJS Kesehatan
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                    credit_card
                                                </span>

                                                <input type="text" name="bpjs_kesehatan"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['bpjs_kesehatan'] ?? ''); ?>"
                                                    placeholder="Masukkan nomor BPJS Kesehatan"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- BPJS KETENAGAKERJAAN -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                No. BPJS Ketenagakerjaan
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                    credit_card
                                                </span>

                                                <input type="text" name="bpjstk"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['bpjstk'] ?? ''); ?>"
                                                    placeholder="Masukkan nomor BPJS Ketenagakerjaan"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- REKENING -->
                                        <div class="col-12 mb-4">

                                            <label class="font-weight-medium">
                                                No. Rekening
                                            </label>

                                            <div class="position-relative">

                                                <span class="material-icons position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                    account_balance
                                                </span>

                                                <input type="text" name="no_rekening"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['no_rekening'] ?? ''); ?>"
                                                    placeholder="Masukkan nomor rekening"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- LINKEDIN -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                LinkedIn
                                            </label>

                                            <div class="position-relative">

                                                <span class="fab fa-linkedin position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                </span>

                                                <input type="text" name="linkedin"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['linkedin'] ?? ''); ?>"
                                                    placeholder="Masukkan link linkedin"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">

                                            </div>

                                        </div>

                                        <!-- IG -->
                                        <div class="col-6 mb-4">

                                            <label class="font-weight-medium">
                                                Instagram
                                            </label>

                                            <div class="position-relative">

                                                <span class="fab fa-instagram position-absolute"
                                                    style="left:15px; top:50%;
                                                    transform:translateY(-50%);
                                                    color:#8b95a7; font-size:21px;">
                                                </span>

                                                <input type="text" name="instagram"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($dataUser['instagram'] ?? ''); ?>"
                                                    placeholder="Masukkan link Instagram"
                                                    style="height:55px; padding-left:52px;
                                                    border-radius:12px; background:#fff;
                                                    border:1px solid #dfe5ef;
                                                    box-shadow:none;">
                                            </div>

                                        </div>

                                        <!-- BUTTON -->
                                        <div class="col-12 text-right">

                                            <button type="submit"
                                                id="btnSubmitInformasi"
                                                class="btn text-white px-5 btn-submit-profile"
                                                style="height:55px; border-radius:12px; background:linear-gradient(90deg,#3f7cff,#2962ff); font-weight:500; min-width:220px; border:0;">

                                                <span class="material-icons align-middle mr-2"
                                                    style="font-size:19px;">
                                                    send
                                                </span>

                                                SUBMIT

                                            </button>

                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>

                    <!-- RIGHT CONTENT -->
                    <div class="col-lg-4">

                        <!-- EMAIL PASSWORD -->
                        <div class="card border-0 shadow-sm my-4"
                            style="border-radius:20px;">

                            <div class="card-body p-4">

                                <div class="d-flex align-items-start mb-4">

                                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                        style="
                                width:55px;
                                height:55px;
                                background:#edf3ff;
                                color:#4a6cf7;
                            ">

                                        <span class="material-icons">
                                            mail
                                        </span>

                                    </div>

                                    <div>

                                        <h4 class="mb-1 font-weight-bold">
                                            Email & Password
                                        </h4>

                                        <p class="text-muted mb-0">
                                            Perbarui email dan password akun Anda.
                                        </p>

                                    </div>

                                </div>

                                <!-- EMAIL -->
                                <div class="mb-4">

                                    <label class="font-weight-medium">
                                        Email
                                    </label>

                                    <div class="position-relative">

                                        <span class="material-icons position-absolute"
                                            style="
                                    left:15px;
                                    top:50%;
                                    transform:translateY(-50%);
                                    color:#8b95a7;
                                    font-size:21px;
                                ">
                                            mail
                                        </span>

                                        <input type="email"
                                            class="form-control"
                                            name="email"
                                            value="<?= htmlspecialchars($dataUser['email'] ?? ''); ?>"
                                            placeholder="Masukkan email"
                                            style="
                                        height:55px;
                                        padding-left:52px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;
                                        ">

                                    </div>

                                </div>

                                <!-- PASSWORD -->
                                <div class="mb-4">

                                    <label class="font-weight-medium">
                                        Password
                                    </label>

                                    <div class="position-relative">

                                        <span class="material-icons position-absolute"
                                            style="
                left:15px;
                top:50%;
                transform:translateY(-50%);
                color:#8b95a7;
                font-size:21px;
                z-index:2;
            ">
                                            lock
                                        </span>

                                        <input type="password"
                                            class="form-control"
                                            id="passwordInput"
                                            name="password"
                                            placeholder="Kosongkan jika tidak ingin mengganti password"
                                            style="
                                        height:55px;
                                        padding-left:52px;
                                        padding-right:55px;
                                        border-radius:12px;
                                        background:#fff;
                                        border:1px solid #dfe5ef;
                                        box-shadow:none;
                                        ">

                                        <!-- TOGGLE -->
                                        <span class="material-icons"
                                            onclick="togglePassword('passwordInput', this)"
                                            style="
                position:absolute;
                right:16px;
                top:50%;
                transform:translateY(-50%);
                color:#8b95a7;
                font-size:22px;
                cursor:pointer;
                z-index:2;
            ">
                                            visibility_off
                                        </span>

                                    </div>

                                </div>

                                <!-- RE PASSWORD -->
                                <div class="mb-5">

                                    <label class="font-weight-medium">
                                        Re-enter Password
                                    </label>

                                    <div class="position-relative">

                                        <span class="material-icons position-absolute"
                                            style="
                left:15px;
                top:50%;
                transform:translateY(-50%);
                color:#8b95a7;
                font-size:21px;
                z-index:2;
            ">
                                            lock
                                        </span>

                                        <input type="password"
                                            id="rePasswordInput"
                                            class="form-control"
                                            placeholder="Masukkan ulang password"
                                            style="
                height:55px;
                padding-left:52px;
                padding-right:55px;
                border-radius:12px;
                background:#fff;
                border:1px solid #dfe5ef;
                box-shadow:none;
            ">

                                        <!-- TOGGLE -->
                                        <span class="material-icons"
                                            onclick="togglePassword('rePasswordInput', this)"
                                            style="
                position:absolute;
                right:16px;
                top:50%;
                transform:translateY(-50%);
                color:#8b95a7;
                font-size:22px;
                cursor:pointer;
                z-index:2;
            ">
                                            visibility_off
                                        </span>

                                    </div>

                                </div>

                                <!-- BUTTON -->
                                <button
                                    type="submit"
                                    id="btnSubmitEmail"
                                    class="btn text-white px-5 btn-submit-profile"
                                    style="height:55px; border-radius:12px; background:linear-gradient(90deg,#3f7cff,#2962ff); font-weight:500; min-width:220px; transition:.25s ease; border:0;">

                                    <span class="material-icons align-middle mr-2"
                                        style="font-size:19px;">
                                        send
                                    </span>

                                    SUBMIT

                                </button>

                            </div>

                        </div>

                        <!-- PHOTO PROFILE -->
                        <div class="upload-photo-wrapper">
                            <form method="POST"
                                action="logic/process_edit_profile.php"
                                enctype="multipart/form-data">
                                <!-- INPUT -->
                                <input type="file"
                                    name="photo_profile"
                                    id="uploadFoto"
                                    accept="image/*"
                                    hidden>

                                <!-- AREA -->
                                <div id="uploadArea"
                                    class="d-flex flex-column align-items-center justify-content-center mb-4"
                                    style="
            border:2px dashed #d9deea;
            border-radius:30px;
            min-height:320px;
            cursor:pointer;
            transition:.25s ease;
            overflow:hidden;
            position:relative;
        ">

                                    <!-- PREVIEW -->
                                    <img src="<?= !empty($dataUser['photo_profile'])
                                                    ? '../assets/images/uploads/user_photos/' . $dataUser['photo_profile']
                                                    : ''; ?>"
                                        id="previewFoto"
                                        onerror="this.style.display='none'; document.getElementById('uploadPlaceholder').style.display='block';"
                                        style="
    width:100%;
    height:320px;
    object-fit:cover;
    <?= empty($dataUser['photo_profile']) ? 'display:none;' : ''; ?>">

                                    <!-- PLACEHOLDER -->
                                    <div id="uploadPlaceholder"
                                        class="text-center" style="<?= !empty($dataUser['photo_profile']) ? 'display:none;' : ''; ?>">

                                        <span class="material-icons mb-3"
                                            style="
                    font-size:70px;
                    color:#6f7687;
                ">
                                            cloud_upload
                                        </span>

                                        <h6 class="font-weight-bold mb-2">
                                            Klik untuk upload foto
                                        </h6>

                                        <p class="text-muted text-center mb-1">
                                            atau seret & lepas file di sini
                                        </p>

                                        <small class="text-muted">
                                            JPG, PNG maks. 2MB
                                        </small>

                                    </div>

                                </div>

                                <!-- BUTTON -->
                                <button
                                    type="submit"
                                    id="btnSubmitFoto"
                                    class="btn btn-block text-white btn-submit-profile"
                                    style=" height:55px; border-radius:12px; background:linear-gradient(90deg,#3f7cff,#2962ff); font-weight:500; transition:.25s ease; border:0;">

                                    <span class="material-icons align-middle mr-2"
                                        style="font-size:19px;">
                                        check
                                    </span>

                                    SUBMIT

                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                <!-- ********************************** //END page-content ********************************** -->
            </div>
            <!-- ********************************** //END page-content ********************************** -->
        </div>
    </div>
    <!-- // END header-layout -->

    <!-- App Settings FAB -->
    <div id="app-settings" style="display: none">
        <app-settings layout-active="fluid"></app-settings>
    </div>

    <!-- ********************************** // MENU-Drawer ********************************** -->
    <?php include 'includes/drawer_menu.php'; ?>
    <!-- ********************************** //END MENU-drawer ********************************** -->

    <!-- =========================
    MODAL LOADING
========================= -->

    <div class="modal fade"
        id="modalLoading"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0"
                style="
                border-radius:20px;
                overflow:hidden;
            ">

                <div class="modal-body text-center p-5">

                    <!-- SPINNER -->
                    <div class="spinner-border text-primary mb-4"
                        style="
                        width:4rem;
                        height:4rem;
                    ">
                    </div>

                    <h5 class="font-weight-bold mb-2">
                        Sedang Mengirim...
                    </h5>

                    <p class="text-muted mb-0">
                        Mohon tunggu sebentar
                    </p>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
    MODAL SUCCESS
========================= -->

    <div class="modal fade"
        id="modalSuccess"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0"
                style="
                border-radius:20px;
                overflow:hidden;
            ">

                <div class="modal-body text-center p-5">

                    <!-- ICON -->
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                        style="
                        width:90px;
                        height:90px;
                        border-radius:50%;
                        background:#ecfdf3;
                    ">

                        <span class="material-icons"
                            style="
                            font-size:50px;
                            color:#16a34a;
                        ">
                            check_circle
                        </span>

                    </div>

                    <!-- TITLE -->
                    <h4 class="font-weight-bold mb-2">
                        Berhasil
                    </h4>

                    <!-- TEXT -->
                    <p class="text-muted mb-4"
                        id="successMessage">

                        Data berhasil diperbarui

                    </p>

                    <!-- BUTTON -->
                    <button type="button"
                        class="btn btn-success px-4"
                        data-dismiss="modal"
                        style="
                        min-width:120px;
                        height:48px;
                        border-radius:12px;
                    ">

                        Okay

                    </button>

                </div>

            </div>

        </div>

    </div>

    <footer class="dashboard-footer mt-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- LEFT -->
                <div class="col-md-6 text-md-left text-center mb-2 mb-md-0">
                    <span class="footer-text">
                        © 2026 Konig Guard Bureau. All rights reserved.
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="../assets/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/vendor/popper.min.js"></script>
    <script src="../assets/vendor/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="../assets/vendor/perfect-scrollbar.min.js"></script>

    <!-- DOM Factory -->
    <script src="../assets/vendor/dom-factory.js"></script>

    <!-- MDK -->
    <script src="../assets/vendor/material-design-kit.js"></script>

    <!-- App -->
    <script src="../assets/js/toggle-check-all.js"></script>
    <script src="../assets/js/check-selected-row.js"></script>
    <script src="../assets/js/dropdown.js"></script>
    <script src="../assets/js/sidebar-mini.js"></script>
    <script src="../assets/js/app.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="../assets/js/app-settings.js"></script>

    <!-- Flatpickr -->
    <script src="../assets/vendor/flatpickr/flatpickr.min.js"></script>
    <script src="../assets/js/flatpickr.js"></script>

    <script>
        // =========================
        // FLATPICKR DATEPICKER
        // =========================

        flatpickr("#tanggalLahir", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            defaultDate: document.getElementById('tanggalLahir').value,
            maxDate: "today",
            onChange: function(selectedDates) {

                if (selectedDates.length > 0) {
                    hitungUmur(selectedDates[0]);
                }
            }
        });

        window.addEventListener('load', function() {

            const tanggal =
                document.getElementById('tanggalLahir').value;

            hitungUmur(tanggal);

        });

        function hitungUmur(tanggal) {

            if (!tanggal) return;

            const birthDate = new Date(tanggal);
            const today = new Date();

            let umur = today.getFullYear() - birthDate.getFullYear();

            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                umur--;
            }

            document.getElementById('hasilUmur').innerHTML =
                umur + ' Tahun';
        }

        // =========================
        // TOGGLE PASSWORD
        // =========================

        function togglePassword(id, icon) {

            const input = document.getElementById(id);

            if (input.type === "password") {

                input.type = "text";
                icon.innerHTML = "visibility";

            } else {

                input.type = "password";
                icon.innerHTML = "visibility_off";

            }

        }

        // =========================
        // DRAG & DROP FOTO
        // =========================

        const uploadArea = document.getElementById('uploadArea');
        const uploadFoto = document.getElementById('uploadFoto');
        const previewFoto = document.getElementById('previewFoto');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');

        uploadArea.addEventListener('click', () => {
            uploadFoto.click();
        });

        uploadFoto.addEventListener('change', function(e) {

            const file = e.target.files[0];

            if (file) {
                tampilkanPreview(file);
            }

        });

        uploadArea.addEventListener('dragover', function(e) {

            e.preventDefault();

            uploadArea.classList.add('dragover');

        });

        uploadArea.addEventListener('dragleave', function() {

            uploadArea.classList.remove('dragover');

        });

        uploadArea.addEventListener('drop', function(e) {

            e.preventDefault();

            uploadArea.classList.remove('dragover');

            const file = e.dataTransfer.files[0];

            if (file) {

                uploadFoto.files = e.dataTransfer.files;

                tampilkanPreview(file);

            }

        });

        function tampilkanPreview(file) {

            const reader = new FileReader();

            reader.onload = function(e) {

                previewFoto.src = e.target.result;

                previewFoto.style.display = 'block';

                uploadPlaceholder.style.display = 'none';

            }

            reader.readAsDataURL(file);

        }
    </script>

    <script>
        // FUNCTION LOADING
        function showLoading(callback) {

            $('#modalLoading').modal('show');

            setTimeout(() => {

                $('#modalLoading').modal('hide');

                setTimeout(() => {

                    callback();

                }, 300);

            }, 1500);

        }

        // =========================
        // VALIDASI INPUT KOSONG
        // =========================

        function isEmpty(value) {

            return value.trim() === "";

        }


        // =========================
        // INFORMASI PRIBADI
        // =========================

        document.getElementById('btnSubmitInformasi')
            .addEventListener('click', function() {

                // INPUT
                const inputs = document.querySelectorAll(
                    '.col-lg-8 input[type="text"], .col-lg-8 textarea'
                );

                // SELECT
                const selects = document.querySelectorAll(
                    '.col-lg-8 select'
                );

                // VALIDASI INPUT
                for (let input of inputs) {

                    if (isEmpty(input.value)) {

                        alert(input.placeholder + ' wajib diisi!');

                        input.focus();

                        return;
                    }

                }

                // VALIDASI SELECT
                for (let select of selects) {

                    if (select.value === "") {
                        alert('Semua pilihan wajib dipilih!');
                        select.focus();
                        return;
                    }

                }

                // VALIDASI TANGGAL
                if (
                    isEmpty(document.getElementById('tanggalLahir').value)
                ) {

                    alert('Tanggal lahir wajib dipilih!');

                    return;

                }

                // LOADING
                showLoading(() => {

                    document.getElementById('successMessage').innerHTML =
                        'Informasi Pribadi berhasil diperbarui';

                    $('#modalSuccess').modal('show');

                });

            });

        // =========================
        // EMAIL PASSWORD
        // =========================

        document.getElementById('btnSubmitEmail')
            .addEventListener('click', function() {

                const emailInput =
                    document.querySelector('input[type="email"]');

                const passwordInput =
                    document.getElementById('passwordInput');

                const rePasswordInput =
                    document.getElementById('rePasswordInput');

                // VALIDASI EMAIL
                if (isEmpty(emailInput.value)) {

                    alert('Email wajib diisi!');

                    emailInput.focus();

                    return;

                }

                // VALIDASI PASSWORD
                if (isEmpty(passwordInput.value)) {

                    alert('Password wajib diisi!');

                    passwordInput.focus();

                    return;

                }

                // VALIDASI RE PASSWORD
                if (isEmpty(rePasswordInput.value)) {

                    alert('Re-enter password wajib diisi!');

                    rePasswordInput.focus();

                    return;

                }

                // VALIDASI PASSWORD SAMA
                if (
                    passwordInput.value !==
                    rePasswordInput.value
                ) {

                    alert('Password tidak sama!');

                    rePasswordInput.focus();

                    return;

                }

                // LOADING
                showLoading(() => {

                    document.getElementById('successMessage').innerHTML =
                        'Email & Password berhasil diperbarui';

                    $('#modalSuccess').modal('show');

                });

            });

        // =========================
        // FOTO PROFILE
        // =========================

        document.getElementById('btnSubmitFoto')
            .addEventListener('click', function() {

                // LOADING
                showLoading(() => {

                    document.getElementById('successMessage').innerHTML =
                        'Foto profile berhasil diperbarui';

                    $('#modalSuccess').modal('show');

                });

            });
    </script>

    <script>
        const onlyNumberInputs = [
            'phone_number',
            'npwp',
            'bpjs_kesehatan',
            'bpjstk',
            'no_rekening',
            'ktp_number'
        ];

        onlyNumberInputs.forEach(name => {

            const input = document.querySelector(`[name="${name}"]`);

            if (input) {

                input.addEventListener('input', function() {

                    this.value = this.value.replace(/[^0-9]/g, '');

                });

            }

        });
    </script>

</body>

</html>