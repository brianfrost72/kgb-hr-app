<?php
session_start();

require_once __DIR__ . "/../koneksi.php";

/*
|--------------------------------------------------------------------------
| TAMBAH USER
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah_user'])) {

    $email          = trim($_POST['email']);
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $role_id        = (int) $_POST['role_id'];
    $region_id      = (int) $_POST['region_id'];
    $department_id  = (int) $_POST['department_id'];
    $position_id    = (int) $_POST['position_id'];

    $full_name      = trim($_POST['full_name']);
    $phone_number   = trim($_POST['phone_number']);

    if (
        $email != '' &&
        $_POST['password'] != '' &&
        $role_id > 0 &&
        $full_name != ''
    ) {

        /*
        |--------------------------------------------------------------------------
        | CEK EMAIL DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $check = mysqli_query($conn, "
            SELECT id
            FROM users
            WHERE email = '$email'
        ");

        if (mysqli_num_rows($check) > 0) {

            $_SESSION['error'] = "Email sudah digunakan.";
            header("Location:manage_roles.php");
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO
        |--------------------------------------------------------------------------
        */

        $photo_profile = 'default.png';

        if (!empty($_FILES['photo_profile']['name'])) {

            $file      = $_FILES['photo_profile'];

            $tmp       = $file['tmp_name'];

            $ext       = strtolower(
                pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            $allowed   = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {

                $_SESSION['error'] =
                    "Format foto harus JPG JPEG PNG";

                header("Location:manage_roles.php");

                exit;
            }

            // AUTO NAMA FILE DARI FULL NAME
            $clean_name = strtolower($full_name);

            $clean_name = preg_replace(
                '/[^a-z0-9]/',
                '_',
                $clean_name
            );

            $clean_name = preg_replace(
                '/_+/',
                '_',
                $clean_name
            );

            $photo_name =
                $clean_name . '.' . $ext;

            $upload_path =
                "../assets/images/profile/users/" . $photo_name;

            move_uploaded_file($tmp, $upload_path);

            $photo_profile = $photo_name;
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT USERS
        |--------------------------------------------------------------------------
        */

        $insertUser = mysqli_query($conn, "
            INSERT INTO users (
                email,
                password,
                role_id,
                region_id,
                department_id,
                position_id,
                user_type,
                created_at,
                update_at
            ) VALUES (
                '$email',
                '$password',
                '$role_id',
                '$region_id',
                '$department_id',
                '$position_id',
                'internal',
                NOW(),
                NOW()
            )
        ");

        if ($insertUser) {

            $user_id = mysqli_insert_id($conn);

            /*
            |--------------------------------------------------------------------------
            | INSERT USER PROFILE
            |--------------------------------------------------------------------------
            */

            mysqli_query($conn, "
                INSERT INTO user_profile (
                    user_id,
                    full_name,
                    tempat_lahir,
                    tanggal_lahir,
                    jenis_kelamin,
                    status_kawin,
                    address,
                    phone_number,
                    ktp_number,
                    npwp,
                    bpjs_kesehatan,
                    bpjstk,
                    no_rekening,
                    id_department,
                    id_position,
                    id_region,
                    id_roles,
                    photo_profile,
                    status
                ) VALUES (
                    '$user_id',
                    '" . mysqli_real_escape_string($conn, $_POST['full_name']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['tempat_lahir']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['tanggal_lahir']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['jenis_kelamin']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['status_kawin']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['address']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['phone_number']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['ktp_number']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['npwp']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['bpjs_kesehatan']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['bpjstk']) . "',
                    '" . mysqli_real_escape_string($conn, $_POST['no_rekening']) . "',
                    '$department_id',
                    '$position_id',
                    '$region_id',
                    '$role_id',
                    '$photo_profile',
                    'active'
                )
            ");

            $_SESSION['success'] = "User berhasil ditambahkan.";
        } else {

            $_SESSION['error'] = "User gagal ditambahkan.";
        }
    } else {

        $_SESSION['error'] = "Field wajib belum lengkap.";
    }

    header("Location:manage_roles.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE USER
|--------------------------------------------------------------------------
*/
if (isset($_POST['update_user'])) {

    $id             = (int) $_POST['id'];
    $profile_id     = (int) $_POST['profile_id'];

    $email          = trim($_POST['email']);
    $role_id        = (int) $_POST['role_id'];
    $region_id      = (int) $_POST['region_id'];
    $department_id  = (int) $_POST['department_id'];
    $position_id    = (int) $_POST['position_id'];

    $full_name      = trim($_POST['full_name']);

    if ($id > 0 && $full_name != '') {

        $password_sql = "";

        if (!empty($_POST['password'])) {

            $new_password = password_hash(
                $_POST['password'],
                PASSWORD_DEFAULT
            );

            $password_sql = ", password = '$new_password'";
        }

        /*
|--------------------------------------------------------------------------
| UPDATE FOTO
|--------------------------------------------------------------------------
*/

        $photo_sql = '';
        if (!empty($_FILES['photo_profile']['name'])) {

            $file = $_FILES['photo_profile'];

            $tmp  = $file['tmp_name'];

            $ext  = strtolower(
                pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            $allowed = ['jpg', 'jpeg', 'png'];

            if (in_array($ext, $allowed)) {

                // HAPUS FOTO LAMA
                $old = mysqli_query($conn, "
            SELECT photo_profile
            FROM user_profile
            WHERE id = '$profile_id'
        ");

                $oldData = mysqli_fetch_assoc($old);

                if (
                    !empty($oldData['photo_profile']) &&
                    $oldData['photo_profile'] != 'default.png'
                ) {

                    $oldPath =
                        "../assets/images/profile/users/" .
                        $oldData['photo_profile'];

                    if (file_exists($oldPath)) {

                        unlink($oldPath);
                    }
                }

                // AUTO NAMA FILE
                $clean_name = strtolower($full_name);

                $clean_name = preg_replace(
                    '/[^a-z0-9]/',
                    '_',
                    $clean_name
                );

                $clean_name = preg_replace(
                    '/_+/',
                    '_',
                    $clean_name
                );

                $photo_name =
                    $clean_name . '.' . $ext;

                $upload_path =
                    "../assets/images/profile/users/" .
                    $photo_name;

                move_uploaded_file($tmp, $upload_path);

                $photo_sql =
                    ", photo_profile = '$photo_name'";
            }
        }

        mysqli_query($conn, "
    UPDATE users
    SET
        email = '$email',
        role_id = '$role_id',
        region_id = '$region_id',
        department_id = '$department_id',
        position_id = '$position_id'
        $password_sql,
        update_at = NOW()
    WHERE id = '$id'
");

        mysqli_query($conn, "
            UPDATE user_profile
            SET
                full_name = '" . mysqli_real_escape_string($conn, $_POST['full_name']) . "',
                tempat_lahir = '" . mysqli_real_escape_string($conn, $_POST['tempat_lahir']) . "',
                tanggal_lahir = '" . mysqli_real_escape_string($conn, $_POST['tanggal_lahir']) . "',
                jenis_kelamin = '" . mysqli_real_escape_string($conn, $_POST['jenis_kelamin']) . "',
                status_kawin = '" . mysqli_real_escape_string($conn, $_POST['status_kawin']) . "',
                address = '" . mysqli_real_escape_string($conn, $_POST['address']) . "',
                phone_number = '" . mysqli_real_escape_string($conn, $_POST['phone_number']) . "',
                ktp_number = '" . mysqli_real_escape_string($conn, $_POST['ktp_number']) . "',
                npwp = '" . mysqli_real_escape_string($conn, $_POST['npwp']) . "',
                bpjs_kesehatan = '" . mysqli_real_escape_string($conn, $_POST['bpjs_kesehatan']) . "',
                bpjstk = '" . mysqli_real_escape_string($conn, $_POST['bpjstk']) . "',
                no_rekening = '" . mysqli_real_escape_string($conn, $_POST['no_rekening']) . "',
                id_department = '$department_id',
                id_position = '$position_id',
                id_region = '$region_id',
                id_roles = '$role_id'
$photo_sql
WHERE id = '$profile_id'
        ");

        $_SESSION['success'] = "User berhasil diupdate.";
    } else {

        $_SESSION['error'] = "Data tidak valid.";
    }

    header("Location:manage_roles.php");
    exit;
}



/*
|--------------------------------------------------------------------------
| DELETE SINGLE
|--------------------------------------------------------------------------
*/
if (isset($_POST['delete_single'])) {

    $id = (int) $_POST['id'];

    $get = mysqli_query($conn, "
        SELECT *
        FROM user_profile
        WHERE user_id = '$id'
    ");

    $profile = mysqli_fetch_assoc($get);

    if (!empty($profile['photo_profile'])) {

        $path = "../assets/images/profile/users/" . $profile['photo_profile'];

        if (file_exists($path)) {

            unlink($path);
        }
    }

    mysqli_query($conn, "
        DELETE FROM user_profile
        WHERE user_id = '$id'
    ");

    mysqli_query($conn, "
        DELETE FROM users
        WHERE id = '$id'
    ");

    $_SESSION['success'] = "User berhasil dihapus.";

    header("Location:manage_roles.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE SELECTED
|--------------------------------------------------------------------------
*/
if (isset($_POST['delete_selected'])) {

    if (!empty($_POST['selected_id'])) {

        foreach ($_POST['selected_id'] as $id) {

            $id = (int) $id;

            mysqli_query($conn, "
                DELETE FROM user_profile
                WHERE user_id = '$id'
            ");

            mysqli_query($conn, "
                DELETE FROM users
                WHERE id = '$id'
            ");
        }

        $_SESSION['success'] = "Data terpilih berhasil dihapus.";
    } else {

        $_SESSION['error'] = "Pilih data terlebih dahulu.";
    }

    header("Location:manage_roles.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| DATA USERS
|--------------------------------------------------------------------------
*/
$users = mysqli_query($conn, "
    SELECT
        users.*,
        user_profile.*,
        user_profile.id AS profile_id,
        roles.role_name,
        department.department_name,
        positions.position_name,
        regions.region_name

    FROM users

    LEFT JOIN user_profile
        ON user_profile.user_id = users.id

    LEFT JOIN roles
        ON roles.id = users.role_id

    LEFT JOIN department
        ON department.id = users.department_id

    LEFT JOIN positions
        ON positions.id = users.position_id

    LEFT JOIN regions
        ON regions.id = users.region_id

    ORDER BY users.id ASC
");

/*
|--------------------------------------------------------------------------
| MASTER DATA SELECT
|--------------------------------------------------------------------------
*/
$roles = mysqli_query($conn, "
    SELECT *
    FROM roles
    ORDER BY role_name ASC
");

$departments = mysqli_query($conn, "
    SELECT *
    FROM department
    ORDER BY department_name ASC
");

$positions = mysqli_query($conn, "
    SELECT *
    FROM positions
    ORDER BY position_name ASC
");

$regions = mysqli_query($conn, "
    SELECT *
    FROM regions
    ORDER BY region_name ASC
");

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage User Role - Dashboard | Konig Guard Bureau</title>

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
                                        Manage User Role
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage User Role</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">
                    <?php if (isset($_SESSION['success'])) : ?>

                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">

                            <i class="fa fa-check-circle mr-2"></i>

                            <?= $_SESSION['success']; ?>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert">

                                <span>&times;</span>

                            </button>

                        </div>

                    <?php unset($_SESSION['success']);
                    endif; ?>


                    <?php if (isset($_SESSION['error'])) : ?>

                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">

                            <i class="fa fa-times-circle mr-2"></i>

                            <?= $_SESSION['error']; ?>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert">

                                <span>&times;</span>

                            </button>

                        </div>

                    <?php unset($_SESSION['error']);
                    endif; ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Manage Roles</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
                        </div>

                        <div class="card-body">
                            <!-- FILTER -->
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">

                                <div class="d-flex align-items-center flex-wrap">

                                    <!-- SHOW ENTRIES -->
                                    <div class="mr-3 mb-2">

                                        <label class="mr-2 mb-0">
                                            Show
                                        </label>

                                        <select
                                            id="showEntries"
                                            class="form-control d-inline-block"
                                            style="width:90px;">

                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>

                                        </select>

                                    </div>

                                    <!-- FILTER ROLE -->
                                    <div class="mr-3 mb-2">

                                        <select
                                            id="filterRole"
                                            class="form-control">

                                            <option value="">
                                                Semua Roles
                                            </option>

                                            <?php
                                            mysqli_data_seek($roles, 0);
                                            while ($role = mysqli_fetch_assoc($roles)):
                                            ?>

                                                <option value="<?= strtolower($role['role_name']); ?>">

                                                    <?= htmlspecialchars($role['role_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>

                                    </div>

                                    <!-- FILTER CABANG -->
                                    <div class="mr-3 mb-2">

                                        <select
                                            id="filterCabang"
                                            class="form-control">

                                            <option value="">
                                                Semua Cabang
                                            </option>

                                            <?php
                                            mysqli_data_seek($regions, 0);
                                            while ($region = mysqli_fetch_assoc($regions)):
                                            ?>

                                                <option value="<?= strtolower($region['region_name']); ?>">

                                                    <?= htmlspecialchars($region['region_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>

                                    </div>

                                </div>

                                <!-- SEARCH -->
                                <div class="mb-2">

                                    <input
                                        type="text"
                                        id="searchInput"
                                        class="form-control"
                                        placeholder="Search..."
                                        style="width:250px;">

                                </div>

                            </div>

                            <form method="POST">
                                <!-- TABLE -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>No</th>
                                                <th>Role ID</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Roles</th>
                                                <th>Cabang</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($users)): ?>

                                                <tr>

                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="selected_id[]"
                                                            value="<?= $row['id']; ?>"
                                                            class="rowCheck">
                                                    </td>

                                                    <td><?= $no++; ?></td>

                                                    <td>
                                                        <?= date('y', strtotime($row['created_at'])) . sprintf('%03d', $row['id']); ?>
                                                    </td>

                                                    <td class="d-flex align-items-center">

                                                        <img
                                                            src="../assets/images/profile/users/<?= !empty($row['photo_profile'])
                                                                                                    ? $row['photo_profile']
                                                                                                    : 'default.png'; ?>"
                                                            class="rounded-circle mr-2"
                                                            width="40"
                                                            height="40"
                                                            style="object-fit:cover;">

                                                        <?= htmlspecialchars($row['full_name']); ?>

                                                    </td>

                                                    <td><?= htmlspecialchars($row['email']); ?></td>

                                                    <td><?= htmlspecialchars($row['role_name']); ?></td>

                                                    <td><?= htmlspecialchars($row['region_name']); ?></td>

                                                    <td>
                                                        <button
                                                            type="button"
                                                            class="btn btn-info btn-sm mb-1"
                                                            title="Lihat Detail User Role"
                                                            onclick='viewData(<?= json_encode([
                                                                                    "photo" => !empty($row['photo_profile']) ? $row['photo_profile'] : 'default.png',
                                                                                    "nama" => $row['full_name'],
                                                                                    "role" => $row['role_name'],
                                                                                    "tempat_lahir" => $row['tempat_lahir'],
                                                                                    "tanggal_lahir" => $row['tanggal_lahir'],
                                                                                    "telepon" => $row['phone_number'],
                                                                                    "email" => $row['email'],
                                                                                    "gender" => $row['jenis_kelamin'],
                                                                                    "department" => $row['department_name'],
                                                                                    "jabatan" => $row['position_name'],
                                                                                    "npwp" => $row['npwp'],
                                                                                    "bpjs" => $row['bpjs_kesehatan'],
                                                                                    "bpjstk" => $row['bpjstk'],
                                                                                    "rekening" => $row['no_rekening'],
                                                                                    "alamat" => $row['address'],
                                                                                    "cabang" => $row['region_name'],
                                                                                    "ktp" => $row['ktp_number']
                                                                                ]); ?>)'>

                                                            <i class="material-icons">remove_red_eye</i>

                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-warning btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#modalEdit<?= $row['id']; ?>" title="Edit Role Profile">

                                                            <i class="material-icons">edit</i>

                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#modalDelete<?= $row['id']; ?>" title="Hapus User Role">

                                                            <i class="material-icons">delete</i>

                                                        </button>

                                                    </td>

                                                </tr>

                                            <?php endwhile; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <!-- PAGINATION -->
                                <div class="d-flex justify-content-between mt-3">
                                    <button
                                        type="submit"
                                        name="delete_selected"
                                        class="btn btn-danger">Hapus Terpilih</button>
                                    <ul class="pagination" id="pagination"></ul>
                                </div>
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

    <form method="POST" enctype="multipart/form-data">
        <div class="modal fade" id="modalTambah">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Tambah User</h5>
                    </div>

                    <div class="modal-body row">
                        <div class="col-md-6">

                            <!-- NAMA -->
                            <div class="form-group">
                                <label>Nama Lengkap</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">person</span>
                                        </span>
                                    </div>

                                    <input
                                        type="text"
                                        name="full_name"
                                        class="form-control"
                                        placeholder="Andy Lau">
                                </div>
                            </div>

                            <!-- EMAIL -->
                            <div class="form-group">
                                <label>Email</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">email</span>
                                        </span>
                                    </div>

                                    <input type="email" name="email"
                                        class="form-control"
                                        placeholder="contoh@gmail.com">
                                </div>
                            </div>

                            <!-- PASSWORD -->
                            <div class="form-group">
                                <label>Password</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">person</span>
                                        </span>
                                    </div>

                                    <input type="password" name="password"
                                        id="passwordTambah"
                                        class="form-control"
                                        placeholder="Password">
                                    <div class="input-group-append">
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="togglePassword()"><span class="material-icons">remove_red_eye</span></button>
                                    </div>
                                </div>
                            </div>

                            <!-- TEMPAT LAHIR -->
                            <div class="form-group">
                                <label>Tempat Lahir</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">location_city</span>
                                        </span>
                                    </div>

                                    <input type="text" name="tempat_lahir"
                                        class="form-control"
                                        placeholder="Jakarta">
                                </div>
                            </div>

                            <!-- TANGGAL LAHIR -->
                            <div class="form-group">

                                <label>Tanggal Lahir</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">
                                                date_range
                                            </span>
                                        </span>
                                    </div>

                                    <input type="date"
                                        id="tanggalLahirTambah" name="tanggal_lahir"
                                        class="form-control">

                                </div>

                                <!-- TEXT UMUR -->
                                <small id="umurText"
                                    class="text-muted d-block mt-2"
                                    style="font-size:13px;
        ">

                                    Umur akan muncul di sini

                                </small>

                            </div>

                            <!-- TELEPON -->
                            <div class="form-group">
                                <label>No Telp / HP</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">call</span>
                                        </span>
                                    </div>

                                    <input type="text" name="phone_number"
                                        class="form-control"
                                        placeholder="081xxxxxxxx">
                                </div>
                            </div>

                            <!-- GENDER -->
                            <div class="form-group">
                                <label>Jenis Kelamin</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">wc</span>
                                        </span>
                                    </div>

                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">---Pilih Jenis Kelamin---</option>
                                        <option>Laki-laki</option>
                                        <option>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- STATUS -->
                            <div class="form-group">
                                <label>Status Perkawinan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">favorite</span>
                                        </span>
                                    </div>

                                    <select name="status_kawin" class="form-control">
                                        <option value="">---Pilih Status---</option>
                                        <option>Belum Nikah</option>
                                        <option>Menikah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- ROLES -->
                            <div class="form-group">
                                <label>Role</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">group</span>
                                        </span>
                                    </div>

                                    <select name="role_id" class="form-control">
                                        <option value="">---Pilih Roles---</option>
                                        <?php
                                        mysqli_data_seek($roles, 0);
                                        while ($role = mysqli_fetch_assoc($roles)):
                                        ?>

                                            <option value="<?= $role['id']; ?>">

                                                <?= htmlspecialchars($role['role_name']); ?>

                                            </option>

                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <!-- DEPARTEMEN -->
                            <div class="form-group">
                                <label>Departemen</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">domain</span>
                                        </span>
                                    </div>

                                    <select name="department_id" class="form-control">
                                        <option value="">---Pilih Departemen---</option>
                                        <?php
                                        mysqli_data_seek($departments, 0);
                                        while ($department = mysqli_fetch_assoc($departments)):
                                        ?>

                                            <option value="<?= $department['id']; ?>">

                                                <?= htmlspecialchars($department['department_name']); ?>

                                            </option>

                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- JABATAN -->
                            <div class="form-group">
                                <label>Jabatan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">work</span>
                                        </span>
                                    </div>

                                    <select name="position_id" class="form-control mb-2">
                                        <option value="">---Pilih Jabatan---</option>
                                        <?php
                                        mysqli_data_seek($positions, 0);
                                        while ($position = mysqli_fetch_assoc($positions)):
                                        ?>

                                            <option value="<?= $position['id']; ?>">

                                                <?= htmlspecialchars($position['position_name']); ?>

                                            </option>

                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- CABANG -->
                            <div class="form-group">
                                <label>Cabang</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">location_city</span>
                                        </span>
                                    </div>

                                    <select name="region_id" class="form-control mb-2">
                                        <option value="">---Pilih Cabang---</option>
                                        <?php
                                        mysqli_data_seek($regions, 0);
                                        while ($region = mysqli_fetch_assoc($regions)):
                                        ?>

                                            <option value="<?= $region['id']; ?>">

                                                <?= htmlspecialchars($region['region_name']); ?>

                                            </option>

                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- KTP -->
                            <div class="form-group">
                                <label>KTP</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text" name="ktp_number"
                                        class="form-control"
                                        placeholder="3216221235646354">
                                </div>
                            </div>

                            <!-- NPWP -->
                            <div class="form-group">
                                <label>NPWP</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text" name="npwp"
                                        class="form-control"
                                        placeholder="3216221235646354">
                                </div>
                            </div>

                            <!-- BPJS KESEHATAN -->
                            <div class="form-group">
                                <label>BPJS Kesehatan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text" name="bpjs_kesehatan"
                                        class="form-control"
                                        placeholder="3216221235646354">
                                </div>
                            </div>

                            <!-- BPJS TK -->
                            <div class="form-group">
                                <label>BPJS KetenagaKerjaan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text" name="bpjstk"
                                        class="form-control"
                                        placeholder="3216221235646354">
                                </div>
                            </div>

                            <!-- REKENING -->
                            <div class="form-group">
                                <label>Rekening (WAJIB BCA)</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text" name="no_rekening"
                                        class="form-control"
                                        placeholder="3216221235646354">
                                </div>
                            </div>

                            <textarea name="address" class="form-control mb-2" placeholder="Alamat"></textarea>

                            <input
                                type="file"
                                id="photoTambah"
                                name="photo_profile"
                                class="form-control mb-2"
                                accept=".jpg,.jpeg,.png">

                            <img
                                id="previewPhoto"
                                class="rounded mt-2"
                                style=" width:80px; height:80px; object-fit:cover; display:none;">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="submit"
                            name="tambah_user"
                            class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <?php
    mysqli_data_seek($users, 0);
    while ($row = mysqli_fetch_assoc($users)):
    ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="modal fade" id="modalEdit<?= $row['id']; ?>">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit User</h5>
                        </div>

                        <div class="modal-body row">
                            <input
                                type="hidden"
                                name="id"
                                value="<?= $row['id']; ?>">

                            <input
                                type="hidden"
                                name="profile_id"
                                value="<?= $row['profile_id']; ?>">

                            <div class="col-md-6">

                                <!-- NAMA -->
                                <div class="form-group">
                                    <label>Nama Lengkap</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">person</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="full_name"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['full_name']); ?>">
                                    </div>
                                </div>

                                <!-- EMAIL -->
                                <div class="form-group">
                                    <label>Email</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">email</span>
                                            </span>
                                        </div>

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['email']); ?>">
                                    </div>
                                </div>

                                <!-- PASSWORD -->
                                <div class="form-group">
                                    <label>Password</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">person</span>
                                            </span>
                                        </div>

                                        <input
                                            type="password"
                                            id="passwordEdit<?= $row['id']; ?>"
                                            name="password"
                                            class="form-control"
                                            placeholder="Kosongkan jika tidak diganti">
                                        <div class="input-group-append">
                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary"
                                                onclick="togglePasswordEdit(event, <?= $row['id']; ?>)"><span class="material-icons">remove_red_eye</span></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- TEMPAT LAHIR -->
                                <div class="form-group">
                                    <label>Tempat Lahir</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">location_city</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="tempat_lahir"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['tempat_lahir']); ?>">
                                    </div>
                                </div>

                                <!-- TANGGAL LAHIR -->
                                <div class="form-group">

                                    <label>Tanggal Lahir</label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">
                                                    date_range
                                                </span>
                                            </span>
                                        </div>

                                        <input
                                            type="date"
                                            id="tanggalLahirEdit"
                                            name="tanggal_lahir"
                                            class="form-control"
                                            value="<?= $row['tanggal_lahir']; ?>">

                                    </div>

                                    <!-- TEXT UMUR -->
                                    <small id="umurTextEdit"
                                        class="text-muted d-block mt-2"
                                        style="font-size:13px;">

                                        Umur akan muncul di sini

                                    </small>

                                </div>

                                <!-- TELEPON -->
                                <div class="form-group">
                                    <label>No Telp / HP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">call</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="phone_number"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['phone_number']); ?>">
                                    </div>
                                </div>

                                <!-- GENDER -->
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">wc</span>
                                            </span>
                                        </div>

                                        <select
                                            name="jenis_kelamin" class="form-control">
                                            <option value="">---Pilih Jenis Kelamin---</option>
                                            <<option
                                                value="Laki-laki"
                                                <?= ($row['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>
                                                Laki-laki
                                                </option>
                                                <option
                                                    value="Perempuan"
                                                    <?= ($row['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
                                                    Perempuan
                                                </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- STATUS -->
                                <div class="form-group">
                                    <label>Status Perkawinan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">favorite</span>
                                            </span>
                                        </div>

                                        <select
                                            name="status_kawin"
                                            id="statusEdit" class="form-control">
                                            <option value="">---Pilih Status---</option>
                                            <option
                                                value="Belum Menikah"
                                                <?= ($row['status_kawin'] == 'Belum Menikah') ? 'selected' : ''; ?>>
                                                Belum Nikah
                                            </option>
                                            <option
                                                value="Menikah"
                                                <?= ($row['status_kawin'] == 'Menikah') ? 'selected' : ''; ?>>
                                                Menikah
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- ROLES -->
                                <div class="form-group">
                                    <label>Role</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">group</span>
                                            </span>
                                        </div>

                                        <select
                                            name="role_id"
                                            class="form-control">

                                            <?php
                                            mysqli_data_seek($roles, 0);
                                            while ($role = mysqli_fetch_assoc($roles)):
                                            ?>

                                                <option
                                                    value="<?= $role['id']; ?>"
                                                    <?= ($role['id'] == $row['role_id']) ? 'selected' : ''; ?>>

                                                    <?= htmlspecialchars($role['role_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">


                                <!-- DEPARTEMEN -->
                                <div class="form-group">
                                    <label>Departemen</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">domain</span>
                                            </span>
                                        </div>

                                        <select
                                            name="department_id"
                                            class="form-control">

                                            <?php
                                            mysqli_data_seek($departments, 0);
                                            while ($department = mysqli_fetch_assoc($departments)):
                                            ?>

                                                <option
                                                    value="<?= $department['id']; ?>"
                                                    <?= ($department['id'] == $row['department_id']) ? 'selected' : ''; ?>>

                                                    <?= htmlspecialchars($department['department_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>
                                    </div>
                                </div>

                                <!-- JABATAN -->
                                <div class="form-group">
                                    <label>Jabatan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">work</span>
                                            </span>
                                        </div>

                                        <select
                                            name="position_id"
                                            class="form-control">

                                            <?php
                                            mysqli_data_seek($positions, 0);
                                            while ($position = mysqli_fetch_assoc($positions)):
                                            ?>

                                                <option
                                                    value="<?= $position['id']; ?>"
                                                    <?= ($position['id'] == $row['position_id']) ? 'selected' : ''; ?>>

                                                    <?= htmlspecialchars($position['position_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>
                                    </div>
                                </div>

                                <!-- CABANG -->
                                <div class="form-group">
                                    <label>Cabang</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">location_city</span>
                                            </span>
                                        </div>

                                        <select
                                            name="region_id"
                                            class="form-control">

                                            <?php
                                            mysqli_data_seek($regions, 0);
                                            while ($region = mysqli_fetch_assoc($regions)):
                                            ?>

                                                <option
                                                    value="<?= $region['id']; ?>"
                                                    <?= ($region['id'] == $row['region_id']) ? 'selected' : ''; ?>>

                                                    <?= htmlspecialchars($region['region_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>
                                    </div>
                                </div>

                                <!-- KTP -->
                                <div class="form-group">
                                    <label>No. KTP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="ktp_number"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['ktp_number']); ?>">
                                    </div>
                                </div>

                                <!-- NPWP -->
                                <div class="form-group">
                                    <label>No. NPWP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="npwp"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['npwp']); ?>">
                                    </div>
                                </div>

                                <!-- BPJS KESEHATAN -->
                                <div class="form-group">
                                    <label>No. BPJS Kesehatan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="bpjs_kesehatan"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['bpjs_kesehatan']); ?>">
                                    </div>
                                </div>

                                <!-- BPJS TK -->
                                <div class="form-group">
                                    <label>No. BPJSTK</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="bpjstk"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['bpjstk']); ?>">
                                    </div>
                                </div>

                                <!-- REKENING -->
                                <div class="form-group">
                                    <label>No. Rekening (BCA)</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input
                                            type="text"
                                            name="no_rekening"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['no_rekening']); ?>">
                                    </div>
                                </div>

                                <textarea
                                    name="address"
                                    class="form-control mb-2"><?= htmlspecialchars($row['address']); ?></textarea>

                                <label class="mt-2">
                                    Upload Gambar Baru
                                </label>

                                <input
                                    type="file"
                                    id="photoEdit<?= $row['id']; ?>"
                                    name="photo_profile"
                                    class="form-control mb-3"
                                    accept=".jpg,.jpeg,.png">

                                <div class="d-flex align-items-center mt-2">

                                    <!-- FOTO AKTIF -->
                                    <div class="mr-3 text-center">

                                        <small class="d-block mb-1">
                                            Foto Aktif
                                        </small>

                                        <img
                                            src="../assets/images/profile/users/<?= !empty($row['photo_profile'])
                                                                                    ? $row['photo_profile']
                                                                                    : 'default.png'; ?>"
                                            class="rounded border"
                                            style="
                width:80px;
                height:80px;
                object-fit:cover;
            ">
                                    </div>

                                    <!-- FOTO UPDATE -->
                                    <div class="text-center">

                                        <small class="d-block mb-1">
                                            Preview Baru
                                        </small>

                                        <img
                                            id="previewPhotoEdit<?= $row['id']; ?>"
                                            src="../assets/images/profile/users/default.png"
                                            class="rounded border"
                                            style="
                width:80px;
                height:80px;
                object-fit:cover;
            ">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="submit"
                                name="update_user"
                                class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endwhile ?>

    <div class="modal fade" id="modalView">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0">

                <div class="modal-body p-0">

                    <!-- LOADING -->
                    <div id="loadingView" class="text-center p-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Loading profile...</p>
                    </div>

                    <!-- PROFILE -->
                    <div id="contentView" style="display:none;">

                        <!-- HEADER COVER -->
                        <div class="profile-cover">
                            <div class="avatar-wrapper">
                                <img id="viewPhoto" class="profile-avatar">
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="profile-body text-center">

                            <!-- NAME -->
                            <h4 id="viewNama" class="mt-2 mb-1"></h4>

                            <span
                                id="viewRoles"
                                class="badge badge-pill badge-primary px-3 py-1">
                            </span>

                            <!-- INFO GRID -->
                            <div class="profile-grid mt-4">

                                <div>
                                    <small>Tempat Lahir</small>
                                    <p id="viewTempatlahir"></p>
                                </div>

                                <div>
                                    <small>Tanggal Lahir</small>
                                    <p id="viewTanggalLahir"></p>
                                </div>

                                <div>
                                    <small>No. Telepon</small>
                                    <p id="viewTelepon"></p>
                                </div>

                                <div>
                                    <small>Email</small>
                                    <p id="viewEmail"></p>
                                </div>

                                <div>
                                    <small>Jenis Kelamin</small>
                                    <p id="viewGender"></p>
                                </div>

                                <div>
                                    <small>Departemen</small>
                                    <p id="viewDepartment"></p>
                                </div>

                                <div>
                                    <small>Jabatan</small>
                                    <p id="viewJabatan"></p>
                                </div>

                                <div>
                                    <small>NPWP</small>
                                    <p id="viewNpwp"></p>
                                </div>

                                <div>
                                    <small>BPJS</small>
                                    <p id="viewBpjs"></p>
                                </div>

                                <div>
                                    <small>BPJS TK</small>
                                    <p id="viewBpjstk"></p>
                                </div>

                                <div>
                                    <small>Rekening</small>
                                    <p id="viewRekening"></p>
                                </div>

                                <div>
                                    <small>Alamat</small>
                                    <p id="viewAlamat"></p>
                                </div>

                                <div>
                                    <small>Cabang</small>
                                    <p id="viewCabang"></p>
                                </div>

                                <div>
                                    <small>No KTP</small>
                                    <p id="viewKtp"></p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <?php
    mysqli_data_seek($users, 0);
    while ($row = mysqli_fetch_assoc($users)):
    ?>

        <form method="POST">

            <div class="modal fade" id="modalDelete<?= $row['id']; ?>">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Hapus User</h5>
                        </div>

                        <div class="modal-body text-center">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $row['id']; ?>">

                            <p>

                                Yakin ingin menghapus
                                <br>

                                <strong>
                                    <?= htmlspecialchars($row['full_name']); ?>
                                </strong>

                                ?

                            </p>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">

                                Batal

                            </button>

                            <button
                                type="submit"
                                name="delete_single"
                                class="btn btn-danger">

                                Hapus

                            </button>

                        </div>

                    </div>
                </div>
            </div>

        </form>

    <?php endwhile; ?>

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
    <script src="../assets/js/pagination.js"></script>

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
    <script src="assets/js/flatpickr.js"></script>

    <!-- Global Settings -->
    <script src="../assets/js/settings.js"></script>

    <!-- Moment.js -->
    <script src="../assets/vendor/moment.min.js"></script>
    <script src="../assets/vendor/moment-range.js"></script>

    <script>
        // ====================================== TAMBAH FOTO PROFILE =======================================

        let photoBase64 = "";

        document.getElementById("photoTambah").onchange = function(e) {
            let file = e.target.files[0];

            if (!file) return;

            let allowed = ['image/png', 'image/jpeg', 'image/jpg'];

            if (!allowed.includes(file.type)) {
                alert("Format harus PNG JPG JPEG");

                e.target.value = "";

                return;
            }

            let reader = new FileReader();

            reader.onload = function(event) {
                let img = new Image();

                img.src = event.target.result;

                img.onload = function() {
                    let canvas = document.createElement("canvas");

                    let maxSize = 300;

                    let scale = Math.min(
                        maxSize / img.width,
                        maxSize / img.height
                    );

                    canvas.width = img.width * scale;

                    canvas.height = img.height * scale;

                    let ctx = canvas.getContext("2d");

                    ctx.drawImage(
                        img,
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    );

                    photoBase64 =
                        canvas.toDataURL("image/jpeg");

                    let preview =
                        document.getElementById("previewPhoto");

                    preview.src = photoBase64;

                    preview.style.display = "block";
                };
            };

            reader.readAsDataURL(file);
        };

        // ====================================== EDIT FOTO PROFILE =======================================

        <?php
        mysqli_data_seek($users, 0);
        while ($row = mysqli_fetch_assoc($users)):
        ?>

            document.getElementById("photoEdit<?= $row['id']; ?>")
                .onchange = function(e) {
                    let file = e.target.files[0];

                    if (!file) return;

                    let allowed = ['image/png', 'image/jpeg', 'image/jpg'];

                    if (!allowed.includes(file.type)) {
                        alert("Format harus PNG JPG JPEG");

                        e.target.value = "";

                        return;
                    }

                    let reader = new FileReader();

                    reader.onload = function(event) {
                        let img = new Image();

                        img.src = event.target.result;

                        img.onload = function() {
                            let canvas =
                                document.createElement("canvas");

                            let maxSize = 300;

                            let scale = Math.min(
                                maxSize / img.width,
                                maxSize / img.height
                            );

                            canvas.width = img.width * scale;

                            canvas.height = img.height * scale;

                            let ctx = canvas.getContext("2d");

                            ctx.drawImage(
                                img,
                                0,
                                0,
                                canvas.width,
                                canvas.height
                            );

                            let photoEditBase64 =
                                canvas.toDataURL("image/jpeg");

                            document.getElementById(
                                "previewPhotoEdit<?= $row['id']; ?>"
                            ).src = photoEditBase64;
                        };
                    };

                    reader.readAsDataURL(file);
                };

        <?php endwhile; ?>
    </script>

    <script>
        $('#tanggalLahirTambah').on('change', function() {

            const birth = new Date($(this).val());

            const today = new Date();

            let age = today.getFullYear() - birth.getFullYear();

            $('#umurText').text(age + ' Tahun');

        });

        $('#tanggalLahirEdit').on('change', function() {

            const birth = new Date($(this).val());

            const today = new Date();

            let age = today.getFullYear() - birth.getFullYear();

            $('#umurTextEdit').text(age + ' Tahun');

        });
    </script>

    <script>
        function viewData(user) {
            $("#modalView").modal("show");

            document.getElementById("loadingView").style.display = "block";

            document.getElementById("contentView").style.display = "none";

            setTimeout(() => {

                document.getElementById("viewPhoto").src =
                    "../assets/images/profile/users/" +
                    (user.photo ? user.photo : "default.png");

                document.getElementById("viewNama").innerHTML =
                    user.nama;

                document.getElementById("viewRoles").innerHTML =
                    user.role;

                document.getElementById("viewTempatlahir").innerHTML =
                    user.tempat_lahir;

                document.getElementById("viewTanggalLahir").innerHTML =
                    user.tanggal_lahir;

                document.getElementById("viewTelepon").innerHTML =
                    user.telepon;

                document.getElementById("viewEmail").innerHTML =
                    user.email;

                document.getElementById("viewGender").innerHTML =
                    user.gender;

                document.getElementById("viewDepartment").innerHTML =
                    user.department;

                document.getElementById("viewJabatan").innerHTML =
                    user.jabatan;

                document.getElementById("viewNpwp").innerHTML =
                    user.npwp;

                document.getElementById("viewBpjs").innerHTML =
                    user.bpjs;

                document.getElementById("viewBpjstk").innerHTML =
                    user.bpjstk;

                document.getElementById("viewRekening").innerHTML =
                    user.rekening;

                document.getElementById("viewAlamat").innerHTML =
                    user.alamat;

                document.getElementById("viewCabang").innerHTML =
                    user.cabang;

                document.getElementById("viewKtp").innerHTML =
                    user.ktp;

                document.getElementById("loadingView").style.display =
                    "none";

                let cover =
                    user.cover ||
                    "../assets/images/bg-banner-profiles/role-profiles.png";

                document.querySelector(".profile-cover").style.backgroundImage =
                    `url('${cover}')`;

                document.getElementById("contentView").style.display = "block";
            }, 600);
        }
    </script>

    <script>
        function togglePassword() {
            let input =
                document.getElementById("passwordTambah");

            input.type =
                input.type === "password" ?
                "text" :
                "password";
        }

        function togglePasswordEdit(event, id) {
            let input =
                document.getElementById("passwordEdit" + id);

            input.type =
                input.type === "password" ?
                "text" :
                "password";
        }
    </script>

    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | CHECK ALL
            |--------------------------------------------------------------------------
            */

            $('#checkAll').on('click', function() {

                $('.rowCheck').prop(
                    'checked',
                    $(this).prop('checked')
                );

            });

            /*
            |--------------------------------------------------------------------------
            | JIKA SEMUA TERCENTANG
            |--------------------------------------------------------------------------
            */

            $('.rowCheck').on('click', function() {

                if (
                    $('.rowCheck:checked').length ==
                    $('.rowCheck').length
                ) {

                    $('#checkAll').prop('checked', true);

                } else {

                    $('#checkAll').prop('checked', false);

                }

            });

        });
    </script>

    <script>
        $(document).ready(function() {

            let tableRows = $("#dataTable tbody tr");

            /*
            |--------------------------------------------------------------------------
            | FILTER FUNCTION
            |--------------------------------------------------------------------------
            */

            function filterTable() {

                let search =
                    $("#searchInput").val().toLowerCase();

                let role =
                    $("#filterRole").val().toLowerCase();

                let cabang =
                    $("#filterCabang").val().toLowerCase();

                let visibleRows = [];

                tableRows.each(function() {

                    let row = $(this);

                    let text =
                        row.text().toLowerCase();

                    let roleText =
                        row.find("td:eq(5)").text().toLowerCase();

                    let cabangText =
                        row.find("td:eq(6)").text().toLowerCase();

                    let matchSearch =
                        text.indexOf(search) > -1;

                    let matchRole =
                        role === "" ||
                        roleText.indexOf(role) > -1;

                    let matchCabang =
                        cabang === "" ||
                        cabangText.indexOf(cabang) > -1;

                    if (
                        matchSearch &&
                        matchRole &&
                        matchCabang
                    ) {

                        visibleRows.push(row);

                    } else {

                        row.hide();

                    }

                });

                /*
                |--------------------------------------------------------------------------
                | SHOW ENTRIES
                |--------------------------------------------------------------------------
                */

                let limit =
                    parseInt($("#showEntries").val());

                $.each(visibleRows, function(index, row) {

                    if (index < limit) {

                        row.show();

                    } else {

                        row.hide();

                    }

                });

            }

            /*
            |--------------------------------------------------------------------------
            | EVENTS
            |--------------------------------------------------------------------------
            */

            $("#searchInput").on("keyup", filterTable);

            $("#filterRole").on("change", filterTable);

            $("#filterCabang").on("change", filterTable);

            $("#showEntries").on("change", filterTable);

            /*
            |--------------------------------------------------------------------------
            | FIRST LOAD
            |--------------------------------------------------------------------------
            */

            filterTable();

        });
    </script>
</body>

</html>