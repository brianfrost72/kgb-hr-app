<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

/* =========================================================
   FUNCTION
========================================================= */
function cleanRupiah($value)
{
    return preg_replace('/[^0-9]/', '', $value);
}

/* =========================================================
   GET DATA DEPARTMENT
========================================================= */
$queryDepartment = mysqli_query($conn, "
    SELECT 
        id,
        department_name
    FROM departments
    ORDER BY department_name ASC
");

/* =========================================================
   GET DATA POSITIONS
========================================================= */
$queryPositions = mysqli_query($conn, "
    SELECT 
        positions.id,
        positions.position_name,
        positions.department_id,
        departments.department_name
    FROM positions
    LEFT JOIN departments
        ON positions.department_id = departments.id
    ORDER BY positions.position_name ASC
");

/* =========================================================
   GET DATA EMPLOYEE PAYMENT
========================================================= */
$queryEmployeePayments = mysqli_query($conn, "
    SELECT
        employee_payment.id,

        employee_payment.id_employee,
        employee_payment.id_department,
        employee_payment.id_position,

        employee_payment.period_date,
        employee_payment.payment_date,
        employee_payment.payment_method,

        employee_payment.basic_salary,
        employee_payment.benefit_salary,
        employee_payment.bonus_salary,
        employee_payment.overtime_salary,

        employee_payment.bpjs_deduction,
        employee_payment.bpjstk_deduction,
        employee_payment.pph21_deduction,
        employee_payment.etc_deduction,

        employee_payment.note_payment,
        employee_payment.update_at,

        departments.department_name,

        positions.position_name

    FROM employee_payment

    LEFT JOIN departments
        ON employee_payment.id_department = departments.id

    LEFT JOIN positions
        ON employee_payment.id_position = positions.id

    ORDER BY employee_payment.id DESC
");

/* =========================================================
   TAMBAH DATA PAYMENT
========================================================= */
if (isset($_POST['add_employee_payment'])) {

    $id_employee      = mysqli_real_escape_string($conn, $_POST['id_employee']);
    $id_department    = mysqli_real_escape_string($conn, $_POST['id_department']);
    $id_position      = mysqli_real_escape_string($conn, $_POST['id_position']);

    $period_date_input = mysqli_real_escape_string($conn, $_POST['period_date']);
    $period_date = $period_date_input . "-01";
    $payment_date     = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_method   = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $basic_salary     = cleanRupiah($_POST['basic_salary']);
    $benefit_salary   = cleanRupiah($_POST['benefit_salary']);
    $bonus_salary     = cleanRupiah($_POST['bonus_salary']);
    $overtime_salary  = cleanRupiah($_POST['overtime_salary']);

    $bpjs_deduction   = cleanRupiah($_POST['bpjs_deduction']);
    $bpjstk_deduction = cleanRupiah($_POST['bpjstk_deduction']);
    $pph21_deduction  = cleanRupiah($_POST['pph21_deduction']);
    $etc_deduction    = cleanRupiah($_POST['etc_deduction']);

    /* =========================================
   CEK DUPLICATE PAYROLL
========================================= */

    $checkPayroll = mysqli_query($conn, "

    SELECT id
    FROM employee_payment

    WHERE id_employee = '$id_employee'
    AND period_date = '$period_date'

");

    if (mysqli_num_rows($checkPayroll) > 0) {

        $_SESSION['error'] = "Payroll personil sudah tersedia";

        header("Location: manage_employee_payment");

        exit;
    }

    $note_payment     = mysqli_real_escape_string($conn, $_POST['note_payment']);

    $update_at = date('Y-m-d H:i:s');

    $insertPayment = mysqli_query($conn, "
        INSERT INTO employee_payment (

            id_employee,
            id_department,
            id_position,

            period_date,
            payment_date,
            payment_method,

            basic_salary,
            benefit_salary,
            bonus_salary,
            overtime_salary,

            bpjs_deduction,
            bpjstk_deduction,
            pph21_deduction,
            etc_deduction,

            note_payment,
            update_at

        ) VALUES (

            '$id_employee',
            '$id_department',
            '$id_position',

            '$period_date',
            '$payment_date',
            '$payment_method',

            '$basic_salary',
            '$benefit_salary',
            '$bonus_salary',
            '$overtime_salary',

            '$bpjs_deduction',
            '$bpjstk_deduction',
            '$pph21_deduction',
            '$etc_deduction',

            '$note_payment',
            '$update_at'
        )
    ");

    if ($insertPayment) {

        $_SESSION['success'] = "Data payroll " . $_POST['employee_name_hidden'] . " berhasil ditambahkan";
    } else {

        $_SESSION['error'] = "Gagal menambahkan payroll : " . mysqli_error($conn);
    }

    header("Location: manage_employee_payment");
    exit;
}

/* =========================================================
   DELETE PAYMENT
========================================================= */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $deletePayment = mysqli_query($conn, "
        DELETE FROM employee_payment
        WHERE id = '$id'
    ");

    if ($deletePayment) {

        $_SESSION['success'] = "Data payroll berhasil dihapus";
    } else {

        $_SESSION['error'] = "Gagal menghapus payroll";
    }

    header("Location: manage_employee_payment");
    exit;
}

/* =========================================================
   GET DETAIL PAYMENT
========================================================= */
if (isset($_GET['detail'])) {

    $id = intval($_GET['detail']);

    $queryDetailPayment = mysqli_query($conn, "
    SELECT

        employee_payment.*,

        employee.full_name,

        departments.department_name,

        positions.position_name

    FROM employee_payment

    LEFT JOIN employee
        ON employee_payment.id_employee = employee.id

    LEFT JOIN departments
        ON employee_payment.id_department = departments.id

    LEFT JOIN positions
        ON employee_payment.id_position = positions.id

    WHERE employee_payment.id = '$id'
");

    $detailPayment = mysqli_fetch_assoc($queryDetailPayment);
}

/* =========================================================
   UPDATE PAYMENT
========================================================= */
if (isset($_POST['update_employee_payment'])) {

    $id = intval($_POST['id']);

    $id_employee      = mysqli_real_escape_string($conn, $_POST['id_employee']);
    $id_department    = mysqli_real_escape_string($conn, $_POST['id_department']);
    $id_position      = mysqli_real_escape_string($conn, $_POST['id_position']);

    $period_date_input = mysqli_real_escape_string($conn, $_POST['period_date']);
    $period_date = $period_date_input . "-01";
    $payment_date     = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_method   = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $basic_salary     = cleanRupiah($_POST['basic_salary']);
    $benefit_salary   = cleanRupiah($_POST['benefit_salary']);
    $bonus_salary     = cleanRupiah($_POST['bonus_salary']);
    $overtime_salary  = cleanRupiah($_POST['overtime_salary']);

    $bpjs_deduction   = cleanRupiah($_POST['bpjs_deduction']);
    $bpjstk_deduction = cleanRupiah($_POST['bpjstk_deduction']);
    $pph21_deduction  = cleanRupiah($_POST['pph21_deduction']);
    $etc_deduction    = cleanRupiah($_POST['etc_deduction']);

    $note_payment     = mysqli_real_escape_string($conn, $_POST['note_payment']);

    $update_at = date('Y-m-d H:i:s');

    $updatePayment = mysqli_query($conn, "
        UPDATE employee_payment SET

            id_employee      = '$id_employee',
            id_department    = '$id_department',
            id_position      = '$id_position',

            period_date      = '$period_date',
            payment_date     = '$payment_date',
            payment_method   = '$payment_method',

            basic_salary     = '$basic_salary',
            benefit_salary   = '$benefit_salary',
            bonus_salary     = '$bonus_salary',
            overtime_salary  = '$overtime_salary',

            bpjs_deduction   = '$bpjs_deduction',
            bpjstk_deduction = '$bpjstk_deduction',
            pph21_deduction  = '$pph21_deduction',
            etc_deduction    = '$etc_deduction',

            note_payment     = '$note_payment',

            update_at        = '$update_at'

        WHERE id = '$id'
    ");

    if ($updatePayment) {

        $_SESSION['success'] = "Data payroll " . $_POST['employee_name_hidden'] . " berhasil diupdate";
    } else {

        $_SESSION['error'] = "Gagal update payroll : " . mysqli_error($conn);
    }

    header("Location: manage_employee_payment");
    exit;
}

/* =========================================================
   PERSONIL BARU SESUAI PERIODE
========================================================= */
$queryEmployeeAll = mysqli_query($conn, "

    SELECT
        employee.id,
        employee.full_name,
        employee.id_department,
        employee.id_position

    FROM employee

    ORDER BY employee.full_name ASC

");

/* =========================================================
   GET DEPARTMENT
========================================================= */
$queryDepartment = mysqli_query($conn, "
    SELECT
        id,
        department_name
    FROM departments
    ORDER BY department_name ASC
");

/* =========================================================
   GET POSITIONS
========================================================= */
$queryPositions = mysqli_query($conn, "
    SELECT
        positions.id,
        positions.position_name,
        positions.department_id,
        departments.department_name
    FROM positions

    LEFT JOIN departments
        ON positions.department_id = departments.id

    ORDER BY positions.position_name ASC
");

/* =========================================================
   GET ENUM PAYMENT METHOD
========================================================= */
$queryPaymentMethod = mysqli_query($conn, "
    SHOW COLUMNS 
    FROM employee_payment 
    LIKE 'payment_method'
");

$dataPaymentMethod = mysqli_fetch_assoc($queryPaymentMethod);

preg_match("/^enum\(\'(.*)\'\)$/", $dataPaymentMethod['Type'], $matches);

$paymentMethods = explode("','", $matches[1]);

/* =========================================================
   NOTIFICATION
========================================================= */

$successMessage = "";

$errorMessage = "";

if (isset($_SESSION['success'])) {

    $successMessage = $_SESSION['success'];

    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {

    $errorMessage = $_SESSION['error'];

    unset($_SESSION['error']);
}

/* =========================================================
   GET DATA TABLE PAYROLL
========================================================= */

$queryTablePayroll = mysqli_query($conn, "
    SELECT

        employee_payment.id,

        employee.full_name,

        departments.department_name,

        positions.position_name,

        employee_payment.period_date,

        employee_payment.payment_date,

        employee_payment.basic_salary,
        employee_payment.benefit_salary,
        employee_payment.bonus_salary,
        employee_payment.overtime_salary,

        employee_payment.bpjs_deduction,
        employee_payment.bpjstk_deduction,
        employee_payment.pph21_deduction,
        employee_payment.etc_deduction,

        employee_payment.update_at

    FROM employee_payment

    LEFT JOIN employee
        ON employee_payment.id_employee = employee.id

    LEFT JOIN departments
        ON employee_payment.id_department = departments.id

    LEFT JOIN positions
        ON employee_payment.id_position = positions.id

    ORDER BY employee_payment.id DESC
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
    <title>Manage Gaji Karyawan - Dashboard | Konig Guard Bureau</title>
    <link href="../assets/images/favicon.png" rel="icon" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

    <!-- Vector Maps -->
    <link
        type="text/css"
        href="../assets/vendor/jqvmap/jqvmap.min.css"
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
                                        Manage Gaji Karyawan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Gaji Karyawan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- TITLE -->
                <div class="mt-4 mb-3">
                    <h4>Tambah Penggajian Karyawan</h4>
                    <small class="text-muted">Input data gaji, bonus, lembur dan potongan</small>
                </div>

                <!-- FORM -->
                <form action="" method="POST">
                    <div class="card p-3 mb-4" style="background:#fff;border-radius:8px;">

                        <!-- TOP FORM -->
                        <div class="row mb-4 pb-3" style="border-bottom:1px solid #ECEEF0;">

                            <!-- PERIODE -->
                            <div class="col-md-3">

                                <label>Periode</label>

                                <input type="month" name="period_date" class="form-control"
                                    value="<?= date('Y-m'); ?>" required>

                            </div>

                            <!-- DEPARTMENT -->
                            <div class="col-md-3 mb-2">

                                <label>Department</label>

                                <select
                                    name="id_department"
                                    id="departmentSelect"
                                    class="form-control"
                                    disabled
                                    required>

                                    <option value="">
                                        -- Pilih Department --
                                    </option>

                                    <?php while ($department = mysqli_fetch_assoc($queryDepartment)) : ?>

                                        <option value="<?= $department['id']; ?>">

                                            <?= $department['department_name']; ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>

                            </div>

                            <!-- POSITION -->
                            <div class="col-md-3 mb-2">

                                <label>Jabatan</label>

                                <select
                                    name="id_position"
                                    id="positionSelect"
                                    class="form-control"
                                    disabled
                                    required>

                                    <option value="">
                                        -- Pilih Jabatan --
                                    </option>

                                    <?php while ($position = mysqli_fetch_assoc($queryPositions)) : ?>

                                        <option value="<?= $position['id']; ?>" data-department="<?= $position['department_id']; ?>">

                                            <?= $position['position_name']; ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>

                            </div>

                            <!-- PERSONIL -->
                            <div class="col-md-3">

                                <label>Personil</label>

                                <select
                                    name="id_employee"
                                    id="employeeSelect"
                                    class="form-control"
                                    disabled
                                    required>

                                    <option value="">
                                        -- Pilih Personil --
                                    </option>

                                </select>

                                <select id="employeeData" style="display:none;">

                                    <?php while ($employee = mysqli_fetch_assoc($queryEmployeeAll)) : ?>

                                        <option
                                            value="<?= $employee['id']; ?>"
                                            data-department="<?= $employee['id_department']; ?>"
                                            data-position="<?= $employee['id_position']; ?>">

                                            <?= $employee['full_name']; ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>

                            </div>

                            <div class="col-md-3">
                                <label>Tanggal Bayar</label>
                                <input type="date" name="payment_date" class="form-control">
                            </div>

                            <!-- PAYMENT METHOD -->
                            <div class="col-md-3">

                                <label>Metode Pembayaran</label>

                                <select
                                    name="payment_method"
                                    class="form-control"
                                    required>

                                    <option value="">
                                        -- Pilih Metode --
                                    </option>

                                    <?php foreach ($paymentMethods as $method) : ?>

                                        <option value="<?= $method; ?>">

                                            <?= $method; ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>

                        <!-- MAIN CONTENT -->
                        <div class="row">

                            <!-- PENDAPATAN -->
                            <div class="col-md-4" style="border-right:1px solid #ECEEF0;">
                                <h6 class="mb-3 text-success">Pendapatan</h6>

                                <label>Gaji Pokok</label>
                                <input type="number" name="basic_salary" class="form-control mb-2 rupiah-input pendapatan-input">

                                <label>Tunjangan</label>
                                <input type="number" name="benefit_salary" class="form-control mb-2 rupiah-input pendapatan-input">

                                <label>Bonus</label>
                                <input type="number" name="bonus_salary" class="form-control mb-2 rupiah-input pendapatan-input">

                                <label>Lembur</label>
                                <input type="number" name="overtime_salary" class="form-control mb-3 rupiah-input pendapatan-input">

                                <div class="p-2" style="background:#FAFBFE;border-radius:6px;">
                                    <strong>Total Pendapatan</strong>
                                    <span id="totalPendapatan" class="float-right text-success">Rp 0</span>
                                </div>
                            </div>

                            <!-- POTONGAN -->
                            <div class="col-md-4" style="border-right:1px solid #ECEEF0;">
                                <h6 class="mb-3 text-danger">Potongan</h6>

                                <label>BPJS Kesehatan</label>
                                <input type="number" name="bpjs_deduction" class="form-control mb-2 rupiah-input potongan-input">

                                <label>BPJS Ketenagakerjaan</label>
                                <input type="number" name="bpjstk_deduction" class="form-control mb-2 rupiah-input potongan-input">

                                <label>PPh 21</label>
                                <input type="number" name="pph21_deduction" class="form-control mb-2 rupiah-input potongan-input">

                                <label>Lain-lain</label>
                                <input type="number" name="etc_deduction" class="form-control mb-3 rupiah-input potongan-input">

                                <div class="p-2" style="background:#FAFBFE;border-radius:6px;">
                                    <strong>Total Potongan</strong>
                                    <span id="totalPotongan" class="float-right text-danger">Rp 0</span>
                                </div>
                            </div>

                            <!-- RINGKASAN -->
                            <div class="col-md-4">
                                <h6 class="mb-3">Ringkasan</h6>

                                <div class="p-3 mb-3" style="background:#FAFBFE;border-radius:8px;">
                                    <p>Total Pendapatan
                                        <span id="summaryPendapatan" class="float-right text-success">Rp 0</span>
                                    </p>
                                    <p>Total Potongan
                                        <span id="summaryPotongan" class="float-right text-danger">Rp 0</span>
                                    </p>

                                    <hr>

                                    <h5>Take Home Pay
                                        <span id="takeHomePay" class="float-right text-primary">Rp 0</span>
                                    </h5>
                                </div>

                                <textarea name="note_payment" class="form-control mb-3" placeholder="Catatan..."></textarea>

                                <div class="text-right">
                                    <button
                                        type="submit"
                                        name="add_employee_payment"
                                        id="btnSimpanPayroll"
                                        class="btn btn-primary">
                                        Simpan
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>

                <!-- NOTIF DISINI -->
                <?php if (!empty($successMessage)) : ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">

                        <?= $successMessage; ?>

                        <button type="button"
                            class="close"
                            data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <?php if (!empty($errorMessage)) : ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                        <?= $errorMessage; ?>

                        <button type="button"
                            class="close"
                            data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <!-- TABLE WIDTH -->
                <div class="mb-3">

                    <label class="mb-1">

                        Lebar Tabel

                    </label>

                    <input
                        type="range"
                        min="800"
                        max="3000"
                        value="1200"
                        id="slider"
                        class="w-100">

                </div>
                <!-- TABLE -->
                <div class="card p-3" style="background:#fff;border-radius:8px;">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                        <!-- TITLE -->
                        <div>
                            <h6 class="mb-0">List Penggajian</h6>
                        </div>

                        <!-- FILTER -->
                        <div class="d-flex align-items-center flex-wrap">

                            <!-- SHOW ENTRIES -->
                            <div class="mr-2">
                                <select id="showEntries"
                                    class="form-control"
                                    style="width:90px;">

                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>

                                </select>
                            </div>

                            <!-- FILTER DIVISI -->
                            <div class="mr-2">
                                <select id="filterDepartment"
                                    class="form-control"
                                    style="width:170px;">

                                    <option value="">
                                        Semua Department
                                    </option>

                                    <?php

                                    mysqli_data_seek($queryDepartment, 0);

                                    while ($department = mysqli_fetch_assoc($queryDepartment)) :

                                    ?>

                                        <option value="<?= strtolower($department['department_name']); ?>">

                                            <?= $department['department_name']; ?>

                                        </option>

                                    <?php endwhile; ?>

                                </select>
                            </div>

                            <!-- FILTER PERIODE -->
                            <div class="mr-2">
                                <input type="month"
                                    id="filterPeriode"
                                    class="form-control"
                                    style="width:170px;">
                            </div>

                            <!-- SEARCH -->
                            <div>
                                <input type="text"
                                    id="searchInput"
                                    class="form-control"
                                    style="width:200px;"
                                    placeholder="Cari...">
                            </div>

                        </div>

                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive" id="tableContainer">

                        <table class="table table-hover" id="payrollTable">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Department</th>
                                    <th>Jabatan</th>
                                    <th>Periode</th>
                                    <th>Tgl Bayar</th>
                                    <th>Total</th>
                                    <th>Potongan</th>
                                    <th>Take Home Pay</th>
                                    <th>Tanggal Update</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="payrollTableBody">

                                <?php

                                $no = 1;

                                while ($row = mysqli_fetch_assoc($queryTablePayroll)) :

                                    $totalPendapatan =
                                        $row['basic_salary'] +
                                        $row['benefit_salary'] +
                                        $row['bonus_salary'] +
                                        $row['overtime_salary'];

                                    $totalPotongan =
                                        $row['bpjs_deduction'] +
                                        $row['bpjstk_deduction'] +
                                        $row['pph21_deduction'] +
                                        $row['etc_deduction'];

                                    $takeHomePay = $totalPendapatan - $totalPotongan;

                                ?>

                                    <tr data-name="<?= strtolower($row['full_name']); ?>"

                                        data-department="<?= strtolower($row['department_name']); ?>"

                                        data-period="<?= date('Y-m', strtotime($row['period_date'])); ?>">

                                        <!-- NO -->
                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <!-- KARYAWAN -->
                                        <td>
                                            <?= $row['full_name']; ?>
                                        </td>

                                        <!-- DEPARTMENT -->
                                        <td>
                                            <?= $row['department_name']; ?>
                                        </td>

                                        <!-- POSITION -->
                                        <td>
                                            <?= $row['position_name']; ?>
                                        </td>

                                        <!-- PERIODE -->
                                        <td>
                                            <?= !empty($row['period_date']) && $row['period_date'] != '0000-00-00'
                                                ? date('F Y', strtotime($row['period_date']))
                                                : '-'; ?>
                                        </td>

                                        <!-- TGL BAYAR -->
                                        <td>
                                            <?= date('d/m/Y', strtotime($row['payment_date'])); ?>
                                        </td>

                                        <!-- TOTAL -->
                                        <td class="text-success">

                                            Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?>

                                        </td>

                                        <!-- POTONGAN -->
                                        <td class="text-danger">

                                            Rp <?= number_format($totalPotongan, 0, ',', '.'); ?>

                                        </td>

                                        <!-- TAKE HOME -->
                                        <td class="text-primary">

                                            Rp <?= number_format($takeHomePay, 0, ',', '.'); ?>

                                        </td>

                                        <!-- UPDATE -->
                                        <td>

                                            <?= date('d/m/Y H:i', strtotime($row['update_at'])); ?>

                                        </td>

                                        <!-- AKSI -->
                                        <td>

                                            <!-- VIEW -->
                                            <a href="?detail=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-primary my-1">

                                                View

                                            </a>

                                            <!-- DELETE -->
                                            <a href="?delete=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus payroll ini?')">

                                                Hapus

                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>
                        <!-- PAGINATION -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

                            <!-- INFO -->
                            <div id="paginationInfo" class="mb-2">
                                Menampilkan 1 sampai 10 dari 100 data
                            </div>

                            <!-- PAGINATION -->
                            <div class="d-flex align-items-center flex-wrap" id="pagination">

                                <!-- generated by js -->

                            </div>

                        </div>

                        <!-- EXPORT -->
                        <a href="includes/export_employee_payment_excel.php"
                            class="btn btn-success">

                            Export Excel

                        </a>
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

    <?php if (isset($detailPayment)) :

        $detailTotalPendapatan =
            $detailPayment['basic_salary'] +
            $detailPayment['benefit_salary'] +
            $detailPayment['bonus_salary'] +
            $detailPayment['overtime_salary'];

        $detailTotalPotongan =
            $detailPayment['bpjs_deduction'] +
            $detailPayment['bpjstk_deduction'] +
            $detailPayment['pph21_deduction'] +
            $detailPayment['etc_deduction'];

        $detailTakeHome =
            $detailTotalPendapatan - $detailTotalPotongan;

    ?>

        <!-- DETAIL VIEW -->
        <div id="detailPayrollCard"
            class="card p-3 mt-4"
            style="background:#fff;border-radius:8px;">

            <!-- TITLE -->
            <div class="mb-3">

                <h4>
                    Detail Penggajian Karyawan
                </h4>

                <small class="text-muted">

                    Informasi lengkap data penggajian

                </small>

            </div>

            <!-- FORM -->
            <form
                action=""
                method="POST"
                id="formEditPayroll">
                <div class="card p-3"
                    style="background:#fff;border-radius:8px;">

                    <!-- TOP -->
                    <div class="row mb-4 pb-3"
                        style="border-bottom:1px solid #ECEEF0;">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $detailPayment['id']; ?>">

                        <input
                            type="hidden"
                            name="id_employee"
                            value="<?= $detailPayment['id_employee']; ?>">

                        <input
                            type="hidden"
                            name="id_department"
                            value="<?= $detailPayment['id_department']; ?>">

                        <input
                            type="hidden"
                            name="id_position"
                            value="<?= $detailPayment['id_position']; ?>">

                        <input
                            type="hidden"
                            name="period_date"
                            value="<?= date('Y-m', strtotime($detailPayment['period_date'])); ?>">

                        <input
                            type="hidden"
                            name="employee_name_hidden"
                            value="<?= $detailPayment['full_name']; ?>">

                        <!-- PERSONIL -->
                        <div class="col-md-3">

                            <label>Personil</label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= $detailPayment['full_name'] ?? '-'; ?>"
                                readonly>

                        </div>

                        <!-- DEPARTMENT -->
                        <div class="col-md-3">

                            <label>Department</label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= $detailPayment['department_name'] ?? '-'; ?>"
                                readonly>

                        </div>

                        <!-- JABATAN -->
                        <div class="col-md-3">

                            <label>Jabatan</label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= $detailPayment['position_name'] ?? '-'; ?>"
                                readonly>

                        </div>

                        <!-- PERIODE -->
                        <div class="col-md-3">

                            <label>Periode</label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= !empty($detailPayment['period_date']) && $detailPayment['period_date'] != '0000-00-00'
                                            ? date('F Y', strtotime($detailPayment['period_date']))
                                            : '-'; ?>"
                                readonly>

                        </div>

                        <!-- TANGGAL -->
                        <div class="col-md-3 mt-3">

                            <label>Tanggal Bayar</label>

                            <input
                                type="date"
                                name="payment_date"
                                class="form-control"
                                value="<?= date('d/m/Y', strtotime($detailPayment['payment_date'])); ?>"
                                readonly>

                        </div>

                        <!-- METODE -->
                        <div class="col-md-3 mt-3">

                            <label>Metode Pembayaran</label>

                            <select
                                name="payment_method"
                                class="form-control editable-field"
                                disabled>

                                <?php foreach ($paymentMethods as $method) : ?>

                                    <option
                                        value="<?= $method; ?>"
                                        <?= $detailPayment['payment_method'] == $method ? 'selected' : ''; ?>>

                                        <?= $method; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                    </div>

                    <!-- MAIN -->
                    <div class="row">

                        <!-- PENDAPATAN -->
                        <div class="col-md-4"
                            style="border-right:1px solid #ECEEF0;">

                            <h6 class="mb-3 text-success">

                                Pendapatan

                            </h6>

                            <label>Gaji Pokok</label>

                            <input
                                type="text"
                                name="basic_salary"
                                class="form-control mb-2 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['basic_salary'], 0, ',', '.'); ?>"
                                readonly>

                            <label>Tunjangan</label>

                            <input
                                type="text"
                                name="benefit_salary"
                                class="form-control mb-2 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['benefit_salary'], 0, ',', '.'); ?>"
                                readonly>

                            <label>Bonus</label>

                            <input
                                type="text"
                                name="bonus_salary"
                                class="form-control mb-2 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['bonus_salary'], 0, ',', '.'); ?>"
                                readonly>

                            <label>Lembur</label>

                            <input
                                type="text"
                                name="overtime_salary"
                                class="form-control mb-3 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['overtime_salary'], 0, ',', '.'); ?>"
                                readonly>

                            <div class="p-2"
                                style="background:#FAFBFE;border-radius:6px;">

                                <strong>Total Pendapatan</strong>

                                <span class="float-right text-success">

                                    Rp <?= number_format($detailTotalPendapatan, 0, ',', '.'); ?>

                                </span>

                            </div>

                        </div>

                        <!-- POTONGAN -->
                        <div class="col-md-4"
                            style="border-right:1px solid #ECEEF0;">

                            <h6 class="mb-3 text-danger">

                                Potongan

                            </h6>

                            <label>BPJS Kesehatan</label>

                            <input
                                type="text"
                                name="bpjs_deduction"
                                class="form-control mb-2 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['bpjs_deduction'], 0, ',', '.'); ?>"
                                readonly>

                            <label>BPJS Ketenagakerjaan</label>

                            <input
                                type="text"
                                name="bpjstk_deduction"
                                class="form-control mb-2 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['bpjstk_deduction'], 0, ',', '.'); ?>"
                                readonly>

                            <label>PPh 21</label>

                            <input
                                type="text"
                                name="pph21_deduction"
                                class="form-control mb-2 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['pph21_deduction'], 0, ',', '.'); ?>"
                                readonly>

                            <label>Lain-lain</label>

                            <input
                                type="text"
                                name="etc_deduction"
                                class="form-control mb-3 editable-field rupiah-input-edit"
                                value="Rp <?= number_format($detailPayment['etc_deduction'], 0, ',', '.'); ?>"
                                readonly>

                            <div class="p-2"
                                style="background:#FAFBFE;border-radius:6px;">

                                <strong>Total Potongan</strong>

                                <span class="float-right text-danger">

                                    Rp <?= number_format($detailTotalPotongan, 0, ',', '.'); ?>

                                </span>

                            </div>

                        </div>

                        <!-- RINGKASAN -->
                        <div class="col-md-4">

                            <h6 class="mb-3">

                                Ringkasan

                            </h6>

                            <div class="p-3 mb-3"
                                style="background:#FAFBFE;border-radius:8px;">

                                <p>

                                    Total Pendapatan

                                    <span class="float-right text-success">

                                        Rp <?= number_format($detailTotalPendapatan, 0, ',', '.'); ?>

                                    </span>

                                </p>

                                <p>

                                    Total Potongan

                                    <span class="float-right text-danger">

                                        Rp <?= number_format($detailTotalPotongan, 0, ',', '.'); ?>

                                    </span>

                                </p>

                                <hr>

                                <h5>

                                    Take Home Pay

                                    <span class="float-right text-primary">

                                        Rp <?= number_format($detailTakeHome, 0, ',', '.'); ?>

                                    </span>

                                </h5>

                            </div>

                            <!-- CATATAN -->
                            <label>
                                Catatan
                            </label>

                            <textarea
                                class="form-control mb-3 editable-field"
                                name="note_payment"
                                readonly><?= $detailPayment['note_payment']; ?></textarea>

                            <!-- BUTTON -->
                            <div class="text-right">

                                <!-- TUTUP -->
                                <a href="manage_employee_payment"
                                    class="btn btn-light">

                                    Tutup

                                </a>

                                <!-- TOGGLE EDIT -->
                                <button
                                    type="button"
                                    id="btnToggleEdit"
                                    class="btn btn-warning">

                                    Edit

                                </button>

                                <!-- SIMPAN -->
                                <button
                                    type="submit"
                                    form="formEditPayroll"
                                    name="update_employee_payment"
                                    id="btnSaveEdit"
                                    class="btn btn-success"
                                    style="display:none;">

                                    Simpan

                                </button>

                                <!-- BATAL -->
                                <button
                                    type="button"
                                    id="btnCancelEdit"
                                    class="btn btn-secondary"
                                    style="display:none;">

                                    Batal

                                </button>

                                <!-- PRINT -->
                                <a href="includes/print_slip_gaji.php?id=<?= $detailPayment['id']; ?>"
                                    target="_blank"
                                    class="btn btn-md btn-primary">

                                    Print Slip

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    <?php endif; ?>

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

            $('#employeeSelect').select2({

                placeholder: '-- Pilih Personil --',

                allowClear: true,

                width: '100%'

            });

        });
    </script>

    <script>
        // LOGIC JS UNTUK LEBAR TABEL COSTUME
        $("#slider").on("input", function() {

            let val = $(this).val();

            $("#payrollTable").css("min-width", val + "px");

        });
    </script>

    <script>
        // =========================================== FORMAT RUPIAH ===========================================
        // FORMAT RUPIAH
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            return rupiah ? "Rp " + rupiah : "";
        }

        // AMBIL ANGKA
        function getNumber(value) {
            return parseInt(value.replace(/[^0-9]/g, "")) || 0;
        }

        // INPUT
        const pendapatanInputs = document.querySelectorAll(".pendapatan-input");
        const potonganInputs = document.querySelectorAll(".potongan-input");

        // TOTAL
        const totalPendapatan = document.getElementById("totalPendapatan");
        const totalPotongan = document.getElementById("totalPotongan");

        const summaryPendapatan = document.getElementById("summaryPendapatan");
        const summaryPotongan = document.getElementById("summaryPotongan");

        const takeHomePay = document.getElementById("takeHomePay");

        // FORMAT INPUT
        document.querySelectorAll(".rupiah-input").forEach((input) => {
            input.setAttribute("type", "text");

            input.addEventListener("input", function() {
                let angka = this.value.replace(/[^0-9]/g, "");
                this.value = formatRupiah(angka);

                hitungTotal();
            });
        });

        // HITUNG TOTAL
        function hitungTotal() {
            let totalP = 0;
            let totalPot = 0;

            pendapatanInputs.forEach((input) => {
                totalP += getNumber(input.value);
            });

            potonganInputs.forEach((input) => {
                totalPot += getNumber(input.value);
            });

            let takeHome = totalP - totalPot;

            totalPendapatan.innerHTML = formatRupiah(totalP.toString());
            totalPotongan.innerHTML = formatRupiah(totalPot.toString());

            summaryPendapatan.innerHTML = formatRupiah(totalP.toString());
            summaryPotongan.innerHTML = formatRupiah(totalPot.toString());

            takeHomePay.innerHTML = formatRupiah(takeHome.toString());
        }
    </script>

    <script>
        $("#employeeSelect").on("change", function() {

            let employeeName = $("#employeeSelect option:selected").text();

            $("#employeeNameHidden").val(employeeName);

        });
    </script>

    <script>
        let originalForm = $("#formEditPayroll").html();

        $("#btnToggleEdit").click(function() {

            $(".editable-field").each(function() {

                $(this).prop("readonly", false);

                $(this).prop("disabled", false);

            });

            $("#btnToggleEdit").hide();

            $("#btnSaveEdit").show();

            $("#btnCancelEdit").show();

        });

        $("#btnCancelEdit").click(function() {

            location.reload();

        });

        /* =========================================
           FORMAT RUPIAH EDIT
        ========================================= */

        $(document).on("input", ".rupiah-input-edit", function() {

            let angka = $(this).val().replace(/[^0-9]/g, "");

            angka = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            $(this).val(angka);

        });

        /* =========================================
           AUTO HITUNG EDIT
        ========================================= */

        function getNumberEdit(value) {

            return parseInt(value.replace(/\./g, '')) || 0;

        }

        function updateSummaryEdit() {

            let totalPendapatan =
                getNumberEdit($("[name='basic_salary']").val()) +
                getNumberEdit($("[name='benefit_salary']").val()) +
                getNumberEdit($("[name='bonus_salary']").val()) +
                getNumberEdit($("[name='overtime_salary']").val());

            let totalPotongan =
                getNumberEdit($("[name='bpjs_deduction']").val()) +
                getNumberEdit($("[name='bpjstk_deduction']").val()) +
                getNumberEdit($("[name='pph21_deduction']").val()) +
                getNumberEdit($("[name='etc_deduction']").val());

            let takeHome = totalPendapatan - totalPotongan;

            $("#detailTotalPendapatan").html(
                "Rp " + totalPendapatan.toLocaleString("id-ID")
            );

            $("#detailTotalPotongan").html(
                "Rp " + totalPotongan.toLocaleString("id-ID")
            );

            $("#summaryPendapatanView").html(
                "Rp " + totalPendapatan.toLocaleString("id-ID")
            );

            $("#summaryPotonganView").html(
                "Rp " + totalPotongan.toLocaleString("id-ID")
            );

            $("#summaryTakeHomeView").html(
                "Rp " + takeHome.toLocaleString("id-ID")
            );

        }

        $(document).on("input", ".rupiah-input-edit", function() {

            updateSummaryEdit();

        });
    </script>

    <script>
        $(document).ready(function() {

            $('#employeeSelect').select2({

                placeholder: '-- Pilih Personil --',

                allowClear: true,

                width: '100%'

            });

            /* =========================================
               DISABLE AWAL
            ========================================= */

            $("#departmentSelect").prop("disabled", true);

            $("#positionSelect").prop("disabled", true);

            $("#employeeSelect").prop("disabled", true);

            /* =========================================
               PILIH PERIODE
            ========================================= */

            $("[name='period_date']").on("change", function() {

                let periode = $(this).val();

                if (periode != "") {

                    $("#departmentSelect").prop("disabled", false);

                } else {

                    $("#departmentSelect").prop("disabled", true);

                    $("#positionSelect").prop("disabled", true);

                    $("#employeeSelect").prop("disabled", true);

                }

            });

            /* =========================================
               CEK DUPLICATE PERIODE
            ========================================= */

            $.ajax({

                url: "ajax/get_used_employee_payroll.php",

                type: "POST",

                data: {

                    period_date: periode

                },

                success: function(response) {

                    let usedEmployees = JSON.parse(response);

                    $("#employeeSelect option").each(function() {

                        let employeeId = $(this).val();

                        if (usedEmployees.includes(employeeId)) {

                            $(this).hide();

                        }

                    });

                }

            });

        });
    </script>

    <script>
        $(document).ready(function() {

            /* =========================================
               SELECT2
            ========================================= */

            $('#employeeSelect').select2({

                placeholder: '-- Pilih Personil --',

                allowClear: true,

                width: '100%'

            });

            /* =========================================
               DISABLE AWAL
            ========================================= */

            $("#departmentSelect").prop("disabled", true);

            $("#positionSelect").prop("disabled", true);

            $("#employeeSelect").prop("disabled", true);

            /* =========================================
               PILIH PERIODE
            ========================================= */

            $("[name='period_date']").on("change", function() {

                let periode = $(this).val();

                if (periode != "") {

                    $("#departmentSelect").prop("disabled", false);

                } else {

                    $("#departmentSelect").prop("disabled", true);

                    $("#positionSelect").prop("disabled", true);

                    $("#employeeSelect").prop("disabled", true);

                }

            });

            /* =========================================
               FILTER JABATAN
            ========================================= */

            $("#departmentSelect").on("change", function() {

                let departmentId = $(this).val();

                $("#positionSelect").prop("disabled", false);

                $("#positionSelect").val("");

                $("#employeeSelect").prop("disabled", true);

                $("#employeeSelect").html(
                    '<option value="">-- Pilih Personil --</option>'
                );

                $("#positionSelect option").hide();

                $("#positionSelect option:first").show();

                $("#positionSelect option").each(function() {

                    if ($(this).data("department") == departmentId) {

                        $(this).show();

                    }

                });

            });

            /* =========================================
               FILTER EMPLOYEE
            ========================================= */

            $("#positionSelect").on("change", function() {

                let departmentId = $("#departmentSelect").val();

                let positionId = $("#positionSelect").val();

                let periode = $("[name='period_date']").val();

                $("#employeeSelect").prop("disabled", false);

                /* =========================================
                   GET DUPLICATE EMPLOYEE
                ========================================= */

                $.ajax({

                    url: "ajax/get_used_employee_payroll.php",

                    type: "POST",

                    data: {

                        period_date: periode

                    },

                    success: function(response) {

                        let usedEmployees = JSON.parse(response);

                        /* =========================================
                           LOOP ALL EMPLOYEE
                        ========================================= */

                        $("#employeeData option").each(function() {

                            let employeeId = $(this).val();

                            let empDepartment = $(this).data("department");

                            let empPosition = $(this).data("position");

                            /* =========================================
                               FILTER
                            ========================================= */

                            if (

                                empDepartment == departmentId &&
                                empPosition == positionId &&
                                !usedEmployees.includes(employeeId)

                            ) {

                                let option = `

                            <option value="${employeeId}">

                                ${$(this).text()}

                            </option>

                        `;

                                $("#employeeSelect").append(option);

                            }

                        });

                        $("#employeeSelect").trigger("change");

                    }

                });

            });

        });
    </script>

    <script>
        $(document).ready(function() {

            let currentPage = 1;

            let rowsPerPage = parseInt($("#showEntries").val());

            /* =========================================
               GET ALL ROWS
            ========================================= */

            function getRows() {

                return $("#payrollTableBody tr");

            }

            /* =========================================
               RENDER TABLE
            ========================================= */

            function renderTable() {

                let tableRows = getRows();

                let search = $("#searchInput").val().toLowerCase();

                let department = $("#filterDepartment").val();

                let period = $("#filterPeriode").val();

                let filteredRows = [];

                tableRows.each(function() {

                    let row = $(this);

                    let name = String(row.data("name") || "").toLowerCase();

                    let rowDepartment = String(row.data("department") || "").toLowerCase();

                    let rowPeriod = String(row.data("period") || "");

                    /* =========================================
                       SEARCH
                    ========================================= */

                    let matchSearch =
                        search == "" ||
                        name.includes(search);

                    /* =========================================
                       FILTER DEPARTMENT
                    ========================================= */

                    let matchDepartment =
                        department == "" ||
                        rowDepartment == department;

                    /* =========================================
                       FILTER PERIODE
                    ========================================= */

                    let matchPeriod =
                        period == "" ||
                        rowPeriod == period;

                    /* =========================================
                       PUSH
                    ========================================= */

                    if (
                        matchSearch &&
                        matchDepartment &&
                        matchPeriod
                    ) {

                        filteredRows.push(row);

                    }

                });

                /* =========================================
                   HIDE ALL
                ========================================= */

                tableRows.hide();

                /* =========================================
                   TOTAL PAGE
                ========================================= */

                let totalRows = filteredRows.length;

                let totalPages = Math.ceil(totalRows / rowsPerPage);

                if (totalPages < 1) {

                    totalPages = 1;

                }

                if (currentPage > totalPages) {

                    currentPage = totalPages;

                }

                /* =========================================
                   START END
                ========================================= */

                let start = (currentPage - 1) * rowsPerPage;

                let end = start + rowsPerPage;

                /* =========================================
                   SHOW ROW
                ========================================= */

                for (let i = start; i < end; i++) {

                    if (filteredRows[i]) {

                        filteredRows[i].show();

                    }

                }

                /* =========================================
                   INFO
                ========================================= */

                let showingStart =
                    totalRows == 0 ?
                    0 :
                    start + 1;

                let showingEnd =
                    end > totalRows ?
                    totalRows :
                    end;

                $("#paginationInfo").html(

                    `Menampilkan ${showingStart}
             sampai ${showingEnd}
             dari ${totalRows} data`

                );

                /* =========================================
                   PAGINATION
                ========================================= */

                renderPagination(totalPages);

            }

            /* =========================================
               PAGINATION RENDER
            ========================================= */

            function renderPagination(totalPages) {

                $("#pagination").html("");

                function addButton(page) {

                    let activeClass =
                        page == currentPage ?
                        'btn-primary' :
                        'btn-light';

                    $("#pagination").append(`

                <button
                    class="btn ${activeClass} btn-sm mx-1 pagination-btn"
                    data-page="${page}">

                    ${page}

                </button>

            `);

                }

                /* =========================================
                   MAX 5 PAGE + ...
                ========================================= */

                if (totalPages <= 5) {

                    for (let i = 1; i <= totalPages; i++) {

                        addButton(i);

                    }

                } else {

                    /* FIRST 5 */

                    for (let i = 1; i <= 5; i++) {

                        addButton(i);

                    }

                    /* DOT */

                    $("#pagination").append(

                        `<span class="mx-2">...</span>`

                    );

                    /* LAST 4 */

                    for (let i = totalPages - 3; i <= totalPages; i++) {

                        addButton(i);

                    }

                }

            }

            /* =========================================
               SEARCH
            ========================================= */

            $("#searchInput").on("keyup", function() {

                currentPage = 1;

                renderTable();

            });

            /* =========================================
               FILTER DEPARTMENT
            ========================================= */

            $("#filterDepartment").on("change", function() {

                currentPage = 1;

                renderTable();

            });

            /* =========================================
               FILTER PERIODE
            ========================================= */

            $("#filterPeriode").on("change", function() {

                currentPage = 1;

                renderTable();

            });

            /* =========================================
               SHOW ENTRIES
            ========================================= */

            $("#showEntries").on("change", function() {

                rowsPerPage = parseInt($(this).val());

                currentPage = 1;

                renderTable();

            });

            /* =========================================
               CLICK PAGINATION
            ========================================= */

            $(document).on("click", ".pagination-btn", function() {

                currentPage = parseInt($(this).data("page"));

                renderTable();

            });

            /* =========================================
               FIRST LOAD
            ========================================= */

            renderTable();

        });
    </script>
</body>

</html>