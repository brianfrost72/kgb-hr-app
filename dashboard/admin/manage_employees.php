<?php
session_start();

require_once __DIR__ . "/../koneksi.php";

/*
|--------------------------------------------------------------------------
| GET DEPARTMENTS
|--------------------------------------------------------------------------
*/

$departments = mysqli_query($conn, "
    SELECT *
    FROM departments
    ORDER BY department_name ASC
");

/*
|--------------------------------------------------------------------------
| GET POSITIONS
|--------------------------------------------------------------------------
*/

$positions = mysqli_query($conn, "
    SELECT *
    FROM positions
    ORDER BY position_name ASC
");

/*
|--------------------------------------------------------------------------
| GET REGIONS
|--------------------------------------------------------------------------
*/

$regions = mysqli_query($conn, "
    SELECT *
    FROM regions
    ORDER BY region_name ASC
");

/*
|--------------------------------------------------------------------------
| GET EMPLOYEES
|--------------------------------------------------------------------------
*/

$employees = mysqli_query($conn, "
    SELECT
        employee.*,

        departments.department_name,

        positions.position_name,

        regions.region_name

    FROM employee

    LEFT JOIN departments
        ON departments.id = employee.id_department

    LEFT JOIN positions
        ON positions.id = employee.id_position

    LEFT JOIN regions
        ON regions.id = employee.id_region

    ORDER BY employee.id ASC
");

/*
|--------------------------------------------------------------------------
| TAMBAH EMPLOYEE
|--------------------------------------------------------------------------
*/

if (isset($_POST['tambah_employee'])) {

    $full_name         = trim($_POST['full_name']);
    $birth_place       = trim($_POST['birth_place']);
    $date_birth        = trim($_POST['date_birth']);

    $email             = trim($_POST['email']);
    $no_telephone      = trim($_POST['no_telephone']);

    $gender            = trim($_POST['gender']);
    $marital_status    = trim($_POST['marital_status']);

    $id_region     = (int) $_POST['id_region'];
    $id_department     = (int) $_POST['id_department'];
    $id_position       = (int) $_POST['id_position'];

    $no_ktp            = trim($_POST['no_ktp']);
    $no_npwp           = trim($_POST['no_npwp']);

    $bpjs_kesehatan    = trim($_POST['bpjs_kesehatan']);
    $bpjstk            = trim($_POST['bpjstk']);

    $no_kta            = trim($_POST['no_kta']);
    $no_rekening       = trim($_POST['no_rekening']);

    $address           = trim($_POST['address']);

    $join_date         = trim($_POST['join_date']);

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    if (

        $full_name != '' &&
        $birth_place != '' &&
        $date_birth != '' &&

        $email != '' &&
        $no_telephone != '' &&

        $gender != '' &&
        $marital_status != '' &&

        $id_region > 0 &&
        $id_department > 0 &&
        $id_position > 0 &&

        $no_ktp != '' &&
        $no_npwp != '' &&

        $bpjs_kesehatan != '' &&
        $bpjstk != '' &&

        $no_rekening != '' &&
        $address != ''

    ) {

        /*
        |--------------------------------------------------------------------------
        | CEK EMAIL DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $check = mysqli_query($conn, "
            SELECT id
            FROM employee
            WHERE email = '$email'
        ");

        if (mysqli_num_rows($check) > 0) {

            $_SESSION['error'] =
                "Email sudah digunakan.";

            header("Location:manage_employees");

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO
        |--------------------------------------------------------------------------
        */

        $profile_picture = 'default.png';

        if (!empty($_FILES['profile_picture']['name'])) {

            $file = $_FILES['profile_picture'];

            $tmp  = $file['tmp_name'];

            $ext  = strtolower(
                pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {

                $_SESSION['error'] =
                    "Format foto harus JPG JPEG PNG.";

                header("Location:manage_employees");

                exit;
            }

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

            move_uploaded_file(
                $tmp,
                "../assets/images/profile/employees/" .
                    $photo_name
            );

            $profile_picture = $photo_name;
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $insert = mysqli_query($conn, "
            INSERT INTO employee (

                full_name,
                birth_place,
                date_birth,

                email,
                no_telephone,

                gender,
                marital_status,

                id_region,
                id_department,
                id_position,

                no_ktp,
                no_npwp,

                bpjs_kesehatan,
                bpjstk,

                no_kta,
                no_rekening,

                address,

                profile_picture,

                join_date,
                update_at

            ) VALUES (

                '$full_name',
                '$birth_place',
                '$date_birth',

                '$email',
                '$no_telephone',

                '$gender',
                '$marital_status',

                '$id_region',
                '$id_department',
                '$id_position',

                '$no_ktp',
                '$no_npwp',

                '$bpjs_kesehatan',
                '$bpjstk',

                '$no_kta',
                '$no_rekening',

                '$address',

                '$profile_picture',

                '$join_date',
                NOW()
            )
        ");

        if ($insert) {

            $_SESSION['success'] =
                "Employee berhasil ditambahkan.";
        } else {

            $_SESSION['error'] =
                "Employee gagal ditambahkan.";
        }
    } else {

        $_SESSION['error'] =
            "Field wajib belum lengkap.";
    }

    header("Location:manage_employees");

    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE EMPLOYEE
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_employee'])) {

    $id = (int) $_POST['id'];

    $full_name         = trim($_POST['full_name']);

    $email             = trim($_POST['email']);

    $id_region    = (int) $_POST['id_region'];

    $id_department     = (int) $_POST['id_department'];

    $id_position       = (int) $_POST['id_position'];

    if (

        $full_name == '' ||
        trim($_POST['birth_place']) == '' ||
        trim($_POST['date_birth']) == '' ||

        $email == '' ||
        trim($_POST['no_telephone']) == '' ||

        trim($_POST['gender']) == '' ||
        trim($_POST['marital_status']) == '' ||

        $id_region <= 0 ||
        $id_department <= 0 ||
        $id_position <= 0 ||

        trim($_POST['no_ktp']) == '' ||
        trim($_POST['no_npwp']) == '' ||

        trim($_POST['bpjs_kesehatan']) == '' ||
        trim($_POST['bpjstk']) == '' ||

        trim($_POST['no_rekening']) == '' ||
        trim($_POST['address']) == ''

    ) {

        $_SESSION['error'] =
            "Semua field wajib diisi kecuali KTA.";

        header("Location:manage_employees");

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO UPDATE
    |--------------------------------------------------------------------------
    */

    $photo_sql = "";

    if (!empty($_FILES['profile_picture']['name'])) {

        $file = $_FILES['profile_picture'];

        $tmp  = $file['tmp_name'];

        $ext  = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowed)) {

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

            move_uploaded_file(
                $tmp,
                "../assets/images/profile/employees/" .
                    $photo_name
            );

            $photo_sql =
                ", profile_picture = '$photo_name'";
        }
    }

    mysqli_query($conn, "
        UPDATE employee
        SET

            full_name = '" . mysqli_real_escape_string($conn, $_POST['full_name']) . "',

            birth_place = '" . mysqli_real_escape_string($conn, $_POST['birth_place']) . "',

            date_birth = '" . mysqli_real_escape_string($conn, $_POST['date_birth']) . "',

            email = '$email',

            no_telephone = '" . mysqli_real_escape_string($conn, $_POST['no_telephone']) . "',

            gender = '" . mysqli_real_escape_string($conn, $_POST['gender']) . "',

            marital_status = '" . mysqli_real_escape_string($conn, $_POST['marital_status']) . "',

id_region = '$id_region',

            id_department = '$id_department',

            id_position = '$id_position',

            no_ktp = '" . mysqli_real_escape_string($conn, $_POST['no_ktp']) . "',

            no_npwp = '" . mysqli_real_escape_string($conn, $_POST['no_npwp']) . "',

            bpjs_kesehatan = '" . mysqli_real_escape_string($conn, $_POST['bpjs_kesehatan']) . "',

            bpjstk = '" . mysqli_real_escape_string($conn, $_POST['bpjstk']) . "',

            no_kta = '" . mysqli_real_escape_string($conn, $_POST['no_kta']) . "',

            no_rekening = '" . mysqli_real_escape_string($conn, $_POST['no_rekening']) . "',

            address = '" . mysqli_real_escape_string($conn, $_POST['address']) . "',

join_date = '" . mysqli_real_escape_string($conn, $_POST['join_date']) . "'

$photo_sql

, update_at = NOW()

        WHERE id = '$id'
    ");

    $_SESSION['success'] =
        "Employee berhasil diupdate.";

    header("Location:manage_employees");

    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE EMPLOYEE
|--------------------------------------------------------------------------
*/

if (isset($_POST['delete_employee'])) {

    $id = (int) $_POST['id'];

    mysqli_query($conn, "
        DELETE FROM employee
        WHERE id = '$id'
    ");

    $_SESSION['success'] =
        "Employee berhasil dihapus.";

    header("Location:manage_employees");

    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE SELECTED
|--------------------------------------------------------------------------
*/

if (isset($_POST['delete_selected'])) {

    if (!empty($_POST['selected'])) {

        foreach ($_POST['selected'] as $id) {

            $id = (int) $id;

            mysqli_query($conn, "
                DELETE FROM employee
                WHERE id = '$id'
            ");
        }

        $_SESSION['success'] =
            "Employee terpilih berhasil dihapus.";
    } else {

        $_SESSION['error'] =
            "Tidak ada data dipilih.";
    }

    header("Location:manage_employees");

    exit;
}


?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Personel - Dashboard | Konig Guard Bureau</title>
    <link href="../assets/images/favicon.png" rel="icon" />

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
                                        Manage Personel
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Personel</h1>
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
                            <h4 class="card-title">Manage Personel</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
                        </div>

                        <div class="card-body">
                            <!-- FILTER -->
                            <div class="row align-items-end mb-4">

                                <!-- SHOW ENTRIES -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Show Entries
                                    </label>

                                    <select id="showEntries" class="form-control">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>20</option>
                                    </select>
                                </div>

                                <!-- FILTER DEPARTEMEN -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Departemen
                                    </label>

                                    <select id="filterDepartment" class="form-control">
                                        <option value="">Semua Departemen</option>
                                        <?php
                                        mysqli_data_seek($departments, 0);
                                        while ($department = mysqli_fetch_assoc($departments)):
                                        ?>

                                            <option value="<?= strtolower($department['department_name']); ?>">

                                                <?= htmlspecialchars($department['department_name']); ?>

                                            </option>

                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- FILTER JABATAN -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Jabatan
                                    </label>

                                    <select id="filterJabatan" class="form-control">
                                        <option value="">Semua Jabatan</option>
                                        <?php
                                        mysqli_data_seek($positions, 0);
                                        while ($position = mysqli_fetch_assoc($positions)):
                                        ?>

                                            <option value="<?= strtolower($position['position_name']); ?>">

                                                <?= htmlspecialchars($position['position_name']); ?>

                                            </option>

                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- FILTER GENDER -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Jenis Kelamin
                                    </label>

                                    <select id="filterGender" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="laki-laki">
                                            Laki-laki
                                        </option>

                                        <option value="perempuan">
                                            Perempuan
                                        </option>
                                    </select>
                                </div>

                                <!-- SEARCH -->
                                <div class="col-lg-4 col-md-12 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Search
                                    </label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">search</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            id="searchInput"
                                            class="form-control"
                                            placeholder="Cari personel...">
                                    </div>
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
                                                <th>NIK Karyawan</th>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin (L/P)</th>
                                                <th>Departemen</th>
                                                <th>Jabatan</th>
                                                <th>Tgl. Bergabung</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($employees)):
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="rowCheck" name="selected[]" value="<?= $row['id'] ?>">
                                                    </td>

                                                    <td><?= $no++; ?></td>

                                                    <?php
                                                    $nik = '-';

                                                    if (
                                                        !empty($row['join_date']) &&
                                                        $row['join_date'] != '0000-00-00'
                                                    ) {

                                                        $tahun = date('y', strtotime($row['join_date']));
                                                        $tanggal = date('d', strtotime($row['join_date']));
                                                        $bulan = date('m', strtotime($row['join_date']));

                                                        $urutan = str_pad($row['id'], 3, '0', STR_PAD_LEFT);

                                                        // format:
                                                        // 26(tahun)02(tanggal)02(bulan)001(id)
                                                        $nik = $tahun . $tanggal . $bulan . $urutan;
                                                    }
                                                    ?>

                                                    <td><?= $nik; ?></td>

                                                    <td class="d-flex align-items-center">
                                                        <img src="../assets/images/profile/employees/<?= !empty($row['profile_picture']) ? $row['profile_picture'] : 'default.png'; ?>"
                                                            class="rounded-circle mr-2"
                                                            width="40" height="40"
                                                            style="object-fit:cover;">
                                                        <!-- NAMA DISINI-->
                                                        <?= htmlspecialchars($row['full_name']); ?>

                                                    </td>

                                                    <td> <?= htmlspecialchars($row['gender']); ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['department_name']); ?></td>
                                                    <td><?= htmlspecialchars($row['position_name']); ?></td>
                                                    <!-- format hari, bulan, tahun -->
                                                    <td>
                                                        <?=
                                                        (
                                                            !empty($row['join_date']) &&
                                                            $row['join_date'] != '0000-00-00'
                                                        )

                                                            ? date('d/m/Y', strtotime($row['join_date']))
                                                            : '-';
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <button
                                                            type="button"
                                                            class="btn btn-info btn-sm mb-1"
                                                            title="Lihat Detail User Role"
                                                            onclick='viewData(<?= json_encode([

                                                                                    "photo" => !empty($row['profile_picture'])
                                                                                        ? $row['profile_picture']
                                                                                        : "default.png",

                                                                                    "nama" => $row['full_name'],

                                                                                    "birth_place" => $row['birth_place'],

                                                                                    "date_birth" => $row['date_birth'],

                                                                                    "email" => $row['email'],

                                                                                    "no_telephone" => $row['no_telephone'],

                                                                                    "gender" => $row['gender'],

                                                                                    "status" => $row['marital_status'],

                                                                                    "department" => $row['department_name'],

                                                                                    "jabatan" => $row['position_name'],

                                                                                    "cabang" => $row['region_name'],

                                                                                    "ktp" => $row['no_ktp'],

                                                                                    "kta" => $row['no_kta'],

                                                                                    "npwp" => $row['no_npwp'],

                                                                                    "bpjs" => $row['bpjs_kesehatan'],

                                                                                    "bpjstk" => $row['bpjstk'],

                                                                                    "rekening" => $row['no_rekening'],

                                                                                    "alamat" => $row['address']

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
                        <h5>Tambah Personel</h5>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <!-- LEFT -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Tanggal Bergabung</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">event</span>
                                            </span>
                                        </div>

                                        <input
                                            type="date"
                                            name="join_date"
                                            class="form-control"
                                            value="<?= $row['join_date']; ?>">
                                    </div>
                                </div>

                                <!-- NAMA -->
                                <div class="form-group">
                                    <label>Nama Lengkap</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">person</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="full_name"
                                            class="form-control"
                                            placeholder="Andy Lau">
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

                                        <input type="text"
                                            name="birth_place"
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
                                            name="date_birth"
                                            id="tanggalLahirTambah"
                                            class="form-control">

                                    </div>

                                    <!-- TEXT UMUR -->
                                    <small id="umurText"
                                        class="text-muted d-block mt-2"
                                        style="
            font-size:13px;
        ">

                                        Umur akan muncul di sini

                                    </small>

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

                                        <input type="email"
                                            name="email"
                                            class="form-control"
                                            placeholder="email@gmail.com">
                                    </div>
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

                                        <input type="text"
                                            name="no_telephone"
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

                                        <select name="gender" class="form-control">

                                            <option value="">
                                                ---Pilih Jenis Kelamin---
                                            </option>

                                            <option value="Laki-laki">
                                                Laki-laki
                                            </option>

                                            <option value="Perempuan">
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

                                        <select name="marital_status" class="form-control">
                                            <option value="">---Pilih Status---</option>
                                            <option>Belum Nikah</option>
                                            <option>Menikah</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- CABANG -->
                                <div class="form-group">

                                    <label>Cabang</label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text bg-light">

                                                <span class="material-icons">
                                                    location_city
                                                </span>

                                            </span>

                                        </div>

                                        <select
                                            name="id_region"
                                            class="form-control">

                                            <option value="">
                                                ---Pilih Cabang---
                                            </option>

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

                                <!-- DEPARTEMEN -->
                                <div class="form-group">
                                    <label>Departemen</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">domain</span>
                                            </span>
                                        </div>

                                        <select name="id_department" class="form-control"
                                            onchange="filterPositions(this)">
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

                                        <select name="id_position" class="form-control mb-2 position-select">
                                            <option value="">---Pilih Jabatan---</option>
                                            <?php
                                            mysqli_data_seek($positions, 0);
                                            while ($position = mysqli_fetch_assoc($positions)):
                                            ?>

                                                <option value="<?= $position['id']; ?>"
                                                    data-department="<?= $position['department_id']; ?>">

                                                    <?= htmlspecialchars($position['position_name']); ?>

                                                </option>

                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- KTP -->
                                <div class="form-group">
                                    <label>Nomor KTP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_ktp"
                                            class="form-control"
                                            placeholder="Nomor KTP">
                                    </div>
                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

                                <!-- NPWP -->
                                <div class="form-group">
                                    <label>Nomor NPWP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_npwp"
                                            class="form-control"
                                            placeholder="Nomor NPWP">
                                    </div>
                                </div>

                                <!-- BPJS KESEHATAN -->
                                <div class="form-group">
                                    <label>Nomor BPJS Kesehatan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="bpjs_kesehatan"
                                            class="form-control"
                                            placeholder="Nomor BPJS Kesehatan">
                                    </div>
                                </div>

                                <!-- BPJS TK -->
                                <div class="form-group">
                                    <label>Nomor BPJS KetenagaKerjaan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="bpjstk"
                                            class="form-control"
                                            placeholder="Nomor BPJS TK">
                                    </div>
                                </div>

                                <!-- KTA -->
                                <div class="form-group">
                                    <label>Nomor KTA Petugas</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">assignment_ind</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_kta"
                                            class="form-control"
                                            placeholder="Nomor KTA">
                                    </div>
                                </div>

                                <!-- NO. REKENING -->
                                <div class="form-group">
                                    <label>No Rekening</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">account_balance</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_rekening"
                                            class="form-control"
                                            placeholder="BCA 123456789">
                                    </div>
                                </div>

                                <!-- ALAMAT -->
                                <div class="form-group">
                                    <label>Alamat</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend align-items-stretch">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">home</span>
                                            </span>
                                        </div>

                                        <textarea name="address" class="form-control"
                                            rows="5"
                                            placeholder="Masukkan alamat lengkap"></textarea>
                                    </div>
                                </div>

                                <!-- FOTO -->
                                <div class="form-group">
                                    <label>Upload Foto</label>

                                    <div class="custom-file">
                                        <input
                                            type="file"
                                            id="photoTambah"
                                            name="profile_picture"
                                            class="custom-file-input mb-2"
                                            accept=".jpg,.jpeg,.png">

                                        <label class="custom-file-label">
                                            Pilih Foto
                                        </label>
                                    </div>
                                </div>

                                <!-- PREVIEW -->
                                <div class="mt-4 text-center">

                                    <img id="previewPhoto"
                                        src="../assets/images/profile/employees/default.png"
                                        class="rounded-circle shadow-sm border"
                                        style="width:120px; height:120px; object-fit:cover;">
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="tambah_employee" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
    mysqli_data_seek($employees, 0);
    while ($row = mysqli_fetch_assoc($employees)):
    ?>
        <div class="modal fade" id="modalEdit<?= $row['id']; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form
                        method="POST"
                        enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5>Edit Personel</h5>
                        </div>

                        <div class="modal-body row">
                            <input
                                type="hidden"
                                name="id"
                                value="<?= $row['id']; ?>">

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Tanggal Bergabung</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">event</span>
                                            </span>
                                        </div>

                                        <input
                                            type="date"
                                            name="join_date"
                                            class="form-control"
                                            value="<?= $row['join_date']; ?>">
                                    </div>
                                </div>

                                <!-- NAMA -->
                                <div class="form-group">
                                    <label>Nama Lengkap</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">person</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="full_name"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['full_name']); ?>">
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

                                        <input type="text"
                                            name="birth_place"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['birth_place']); ?>">
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
                                            id="tanggalLahirEdit"
                                            name="date_birth"
                                            class="form-control"
                                            value="<?= $row['date_birth']; ?>">

                                    </div>

                                    <!-- TEXT UMUR -->
                                    <small id="umurTextEdit"
                                        class="text-muted d-block mt-2"
                                        style="
            font-size:13px;
        ">

                                        Umur akan muncul di sini

                                    </small>

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

                                        <input type="email"
                                            name="email"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['email']); ?>">
                                    </div>
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

                                        <input type="text"
                                            name="no_telephone"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['no_telephone']); ?>">
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

                                        <select name="gender" class="form-control">

                                            <option value="">
                                                ---Pilih Jenis Kelamin---
                                            </option>

                                            <option
                                                value="Laki-laki"
                                                <?= ($row['gender'] == 'Laki-laki')
                                                    ? 'selected'
                                                    : ''; ?>>

                                                Laki-laki

                                            </option>

                                            <option
                                                value="Perempuan"
                                                <?= ($row['gender'] == 'Perempuan')
                                                    ? 'selected'
                                                    : ''; ?>>

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

                                        <select name="marital_status" class="form-control">
                                            <option value="">----Pilih Status---</option>
                                            <option
                                                value="Belum Menikah"
                                                <?= ($row['marital_status'] == 'Belum Menikah') ? 'selected' : ''; ?>>
                                                Belum Nikah
                                            </option>
                                            <option
                                                value="Menikah"
                                                <?= ($row['marital_status'] == 'Menikah') ? 'selected' : ''; ?>>
                                                Menikah
                                            </option>
                                        </select>
                                    </div>
                                </div>

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
                                            name="id_department"
                                            class="form-control"
                                            onchange="filterPositions(this)">

                                            <?php
                                            mysqli_data_seek($departments, 0);
                                            while ($department = mysqli_fetch_assoc($departments)):
                                            ?>

                                                <option
                                                    value="<?= $department['id']; ?>"
                                                    <?= ($department['id'] == $row['id_department']) ? 'selected' : ''; ?>>

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
                                            name="id_position"
                                            class="form-control position-select">

                                            <?php
                                            mysqli_data_seek($positions, 0);
                                            while ($position = mysqli_fetch_assoc($positions)):
                                            ?>

                                                <option
                                                    value="<?= $position['id']; ?>"
                                                    <?= ($position['id'] == $row['id_position']) ? 'selected' : ''; ?> data-department="<?= $position['department_id']; ?>">

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

                                                <span class="material-icons">
                                                    location_city
                                                </span>

                                            </span>

                                        </div>

                                        <select
                                            name="id_region"
                                            class="form-control">

                                            <option value="">
                                                ---Pilih Cabang---
                                            </option>

                                            <?php
                                            mysqli_data_seek($regions, 0);
                                            while ($region = mysqli_fetch_assoc($regions)):
                                            ?>

                                                <option
                                                    value="<?= $region['id']; ?>"
                                                    <?= ($region['id'] == $row['id_region']) ? 'selected' : ''; ?>>

                                                    <?= htmlspecialchars($region['region_name']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>

                                    </div>

                                </div>

                                <!-- KTP -->
                                <div class="form-group">
                                    <label>Nomor KTP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_ktp"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['no_ktp']); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <!-- NPWP -->
                                <div class="form-group">
                                    <label>Nomor NPWP</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_npwp"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['no_npwp']); ?>">
                                    </div>
                                </div>

                                <!-- BPJS KESEHATAN -->
                                <div class="form-group">
                                    <label>Nomor BPJS Kesehatan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="bpjs_kesehatan"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['bpjs_kesehatan']); ?>">
                                    </div>
                                </div>

                                <!-- BPJS TK -->
                                <div class="form-group">
                                    <label>Nomor BPJS KetenagaKerjaan</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">credit_card</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="bpjstk"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['bpjstk']); ?>">
                                    </div>
                                </div>

                                <!-- KTA -->
                                <div class="form-group">
                                    <label>Nomor KTA Petugas</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">assignment_ind</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_kta"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['no_kta']); ?>">
                                    </div>
                                </div>

                                <!-- REKENING -->
                                <div class="form-group">
                                    <label>No Rekening</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">account_balance</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            name="no_rekening"
                                            class="form-control"
                                            value="<?= htmlspecialchars($row['no_rekening']); ?>">
                                    </div>
                                </div>

                                <!-- ALAMAT -->
                                <div class="form-group">
                                    <label>Alamat</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend align-items-stretch">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">home</span>
                                            </span>
                                        </div>

                                        <textarea name="address"
                                            class="form-control mb-2"
                                            rows="5"><?= htmlspecialchars($row['address']); ?></textarea>
                                    </div>
                                </div>

                                <!-- FOTO -->
                                <div class="form-group">
                                    <label>Upload Foto</label>

                                    <div class="custom-file">
                                        <input type="file"
                                            id="photoEdit<?= $row['id']; ?>"
                                            name="profile_picture"
                                            class="custom-file-input mb-3"
                                            accept=".jpg,.jpeg,.png">

                                        <label class="custom-file-label">
                                            Pilih Foto
                                        </label>
                                    </div>
                                </div>

                                <!-- PREVIEW -->
                                <div class="mt-4 text-center">

                                    <img id="previewPhotoEdit<?= $row['id']; ?>"
                                        src="../assets/images/profile/employees/<?= !empty($row['profile_picture']) ? $row['profile_picture'] : 'default.png'; ?>"
                                        class="rounded-circle shadow-sm border"
                                        style="display:block; margin:auto; width:120px;">

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button name="update_employee"
                                class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- ------------------------------------- MODAL VIEW ------------------------------------- -->
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

                            <!-- AVATAR -->
                            <!-- <img id="viewPhoto" class="profile-avatar"> -->

                            <!-- NAME -->
                            <h4 id="viewNama" class="mt-2 mb-1"></h4>


                            <!-- INFO GRID -->
                            <div class="profile-grid mt-4">

                                <div>
                                    <small>Tempat Lahir</small>
                                    <p id="viewTempatlahir"></p>
                                </div>

                                <div>
                                    <small>Tanggal Lahir</small>
                                    <p id="viewTanggallahir"></p>
                                </div>

                                <div>
                                    <small>Email</small>
                                    <p id="viewEmail"></p>
                                </div>

                                <div>
                                    <small>No. Telepon/HP</small>
                                    <p id="viewTelp"></p>
                                </div>

                                <div>
                                    <small>Status</small>
                                    <p id="viewStatus"></p>
                                </div>

                                <div>

                                    <small>Cabang</small>

                                    <p id="viewCabang"></p>

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
                                    <small>Jenis Kelamin</small>
                                    <p id="viewGender"></p>
                                </div>

                                <div>
                                    <small>Alamat</small>
                                    <p id="viewAlamat"></p>
                                </div>

                                <div>
                                    <small>No. KTP</small>
                                    <p id="viewKtp"></p>
                                </div>

                                <div>
                                    <small>No. KTA</small>
                                    <p id="viewKta"></p>
                                </div>

                                <div>
                                    <small>No. NPWP</small>
                                    <p id="viewNpwp"></p>
                                </div>

                                <div>
                                    <small>No. BPJS Kesehatan</small>
                                    <p id="viewBpjs"></p>
                                </div>

                                <div>
                                    <small>No. BPJS TK</small>
                                    <p id="viewBpjsTK"></p>
                                </div>

                                <div>
                                    <small>No. Rekening</small>
                                    <p id="viewRekening"></p>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <?php
    mysqli_data_seek($employees, 0);
    while ($row = mysqli_fetch_assoc($employees)):
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
                                name="delete_employee"
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
        $(document).ready(function() {

            let tableRows = $("#dataTable tbody tr");

            function filterTable() {

                let search =
                    $("#searchInput").val().toLowerCase();

                let department =
                    $("#filterDepartment").val().toLowerCase();

                let jabatan =
                    $("#filterJabatan").val().toLowerCase();

                let gender =
                    $("#filterGender").val().toLowerCase();

                let visibleRows = [];

                tableRows.each(function() {

                    let row = $(this);

                    let text =
                        row.text().toLowerCase();

                    let departmentText =
                        row.find("td:eq(5)").text().toLowerCase();

                    let jabatanText =
                        row.find("td:eq(6)").text().toLowerCase();

                    let genderText =
                        row.find("td:eq(4)").text().toLowerCase();

                    let matchSearch =
                        text.indexOf(search) > -1;

                    let matchDepartment =
                        department === "" ||
                        departmentText.indexOf(department) > -1;

                    let matchJabatan =
                        jabatan === "" ||
                        jabatanText.indexOf(jabatan) > -1;

                    let matchGender =
                        gender === "" ||
                        genderText.indexOf(gender) > -1;

                    if (
                        matchSearch &&
                        matchDepartment &&
                        matchJabatan &&
                        matchGender
                    ) {

                        visibleRows.push(row);

                    } else {

                        row.hide();

                    }

                });

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

            $("#searchInput").on("keyup", filterTable);

            $("#filterDepartment").on("change", filterTable);

            $("#filterJabatan").on("change", filterTable);

            $("#filterGender").on("change", filterTable);

            $("#showEntries").on("change", filterTable);

            filterTable();

        });
    </script>

    <script>
        function viewData(user) {
            $("#modalView").modal("show");

            document.getElementById("loadingView").style.display = "block";

            document.getElementById("contentView").style.display = "none";

            setTimeout(() => {

                document.getElementById("viewPhoto").src =
                    "../assets/images/profile/employees/" +
                    (user.photo ? user.photo : "default.png");

                document.getElementById("viewNama").innerHTML =
                    user.nama;

                document.getElementById("viewTempatlahir").innerHTML =
                    user.birth_place;

                document.getElementById("viewTanggallahir").innerHTML =
                    user.date_birth;

                document.getElementById("viewTelp").innerHTML =
                    user.no_telephone;

                document.getElementById("viewEmail").innerHTML =
                    user.email;

                document.getElementById("viewGender").innerHTML =
                    user.gender;

                document.getElementById("viewDepartment").innerHTML =
                    user.department;

                document.getElementById("viewJabatan").innerHTML =
                    user.jabatan;

                document.getElementById("viewStatus").innerHTML =
                    user.status;

                document.getElementById("viewKta").innerHTML =
                    user.kta && user.kta != 0 ?
                    user.kta :
                    "-";

                document.getElementById("viewNpwp").innerHTML =
                    user.npwp;

                document.getElementById("viewBpjs").innerHTML =
                    user.bpjs;

                document.getElementById("viewBpjsTK").innerHTML =
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
        <?php
        mysqli_data_seek($employees, 0);
        while ($row = mysqli_fetch_assoc($employees)):
        ?>

            document.getElementById(
                "photoEdit<?= $row['id']; ?>"
            ).onchange = function(e) {
                let file = e.target.files[0];

                if (!file) return;

                let reader = new FileReader();

                reader.onload = function(event) {
                    document.getElementById(
                        "previewPhotoEdit<?= $row['id']; ?>"
                    ).src = event.target.result;
                };

                reader.readAsDataURL(file);
            };

        <?php endwhile; ?>
    </script>

    <script>
        $('#tanggalLahirTambah').on('change', function() {

            const birth = new Date($(this).val());

            const today = new Date();

            let age =
                today.getFullYear() -
                birth.getFullYear();

            $('#umurText').text(age + ' Tahun');

        });
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
        $(document).on('change', '.custom-file-input', function() {

            let fileName = $(this).val().split('\\').pop();

            $(this)
                .next('.custom-file-label')
                .html(fileName);

        });
    </script>

    <script>
        // ======================================
        // PREVIEW FOTO TAMBAH
        // ======================================

        document.getElementById("photoTambah").onchange = function(e) {

            let file = e.target.files[0];

            if (!file) return;

            let reader = new FileReader();

            reader.onload = function(event) {

                document.getElementById(
                    "previewPhoto"
                ).src = event.target.result;

            };

            reader.readAsDataURL(file);

        };
    </script>

    <script>
        function filterPositions(departmentSelect) {

            const departmentId =
                departmentSelect.value;

            /*
            |--------------------------------------------------------------------------
            | MODAL
            |--------------------------------------------------------------------------
            */

            const modal =
                departmentSelect.closest('.modal');

            const positionSelect =
                modal.querySelector('.position-select');

            /*
            |--------------------------------------------------------------------------
            | SIMPAN VALUE LAMA
            |--------------------------------------------------------------------------
            */

            const currentValue =
                positionSelect.value;

            /*
            |--------------------------------------------------------------------------
            | FILTER OPTION
            |--------------------------------------------------------------------------
            */

            positionSelect
                .querySelectorAll('option')
                .forEach(option => {

                    /*
                    |--------------------------------------------------------------------------
                    | OPTION DEFAULT
                    |--------------------------------------------------------------------------
                    */

                    if (!option.dataset.department) {

                        option.hidden = false;
                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TAMPILKAN SESUAI DEPARTEMEN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        option.dataset.department === departmentId
                    ) {

                        option.hidden = false;

                    } else {

                        option.hidden = true;
                    }
                });

            /*
            |--------------------------------------------------------------------------
            | KEMBALIKAN VALUE LAMA
            |--------------------------------------------------------------------------
            */

            const selectedOption =
                positionSelect.querySelector(
                    `option[value="${currentValue}"]`
                );

            if (
                selectedOption &&
                selectedOption.dataset.department === departmentId
            ) {

                positionSelect.value = currentValue;

            } else {

                positionSelect.value = "";
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /*
            |--------------------------------------------------------------------------
            | FILTER SEMUA MODAL EDIT
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll(
                '[name="department_id"]'
            ).forEach(select => {

                filterPositions(select);

            });

        });
    </script>

</body>

</html>