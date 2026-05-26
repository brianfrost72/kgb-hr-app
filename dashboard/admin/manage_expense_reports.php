<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

function cleanRupiah($value)
{
    return preg_replace('/[^0-9]/', '', $value);
}

/* =========================================
   TAMBAH DATA
========================================= */
if (isset($_POST['add_expense'])) {

    $date_expense   = $_POST['date_expense'];
    $type_expense   = trim($_POST['type_expense']);
    $title_expense  = trim($_POST['title_expense']);
    $amount_expense = cleanRupiah($_POST['amount_expense']);
    $desc_expense   = trim($_POST['desc_expense']);

    if (
        empty($date_expense) ||
        empty($type_expense) ||
        empty($title_expense) ||
        empty($amount_expense) ||
        empty($desc_expense)
    ) {

        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {

        $file_payment = '';

        if (!empty($_FILES['file_payment']['name'])) {

            $targetDir = "../assets/images/uploads/expense_reports/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES["file_payment"]["name"]);

            $targetFile = $targetDir . $fileName;

            $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            if (in_array($ext, $allowed)) {

                if (move_uploaded_file($_FILES["file_payment"]["tmp_name"], $targetFile)) {

                    $file_payment = $fileName;
                }
            }
        }

        $insert = mysqli_query($conn, "
            INSERT INTO report_expense
            (
                date_expense,
                type_expense,
                title_expense,
                amount_expense,
                desc_expense,
                file_payment,
                update_at
            )
            VALUES
            (
                '$date_expense',
                '$type_expense',
                '$title_expense',
                '$amount_expense',
                '$desc_expense',
                '$file_payment',
                NOW()
            )
        ");

        if ($insert) {

            echo "<script>
                alert('Laporan pengeluaran berhasil ditambahkan!');
                window.location='manage_expense_reports.php';
            </script>";
        }
    }
}

/* =========================================
   UPDATE DATA
========================================= */
if (isset($_POST['update_expense'])) {

    $id             = $_POST['id'];
    $date_expense   = $_POST['date_expense'];
    $type_expense   = trim($_POST['type_expense']);
    $title_expense  = trim($_POST['title_expense']);
    $amount_expense = cleanRupiah($_POST['amount_expense']);
    $desc_expense   = trim($_POST['desc_expense']);

    $getOld = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT file_payment
        FROM report_expense
        WHERE id='$id'
    "));

    $file_payment = $getOld['file_payment'];

    if (!empty($_FILES['file_payment']['name'])) {

        $targetDir = "../assets/images/uploads/expense_reports/";

        $fileName = time() . '_' . basename($_FILES["file_payment"]["name"]);

        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["file_payment"]["tmp_name"], $targetFile)) {

            if (!empty($file_payment)) {

                $oldPath = $targetDir . $file_payment;

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file_payment = $fileName;
        }
    }

    $update = mysqli_query($conn, "
        UPDATE report_expense
        SET
            date_expense   = '$date_expense',
            type_expense   = '$type_expense',
            title_expense  = '$title_expense',
            amount_expense = '$amount_expense',
            desc_expense   = '$desc_expense',
            file_payment  = '$file_payment',
            update_at      = NOW()
        WHERE id='$id'
    ");

    if ($update) {

        echo "<script>
            alert('Data berhasil diupdate!');
            window.location='manage_expense_reports.php';
        </script>";
    }
}

/* =========================================
   DELETE DATA
========================================= */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $get = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT file_payment
        FROM report_expense
        WHERE id='$id'
    "));

    if (!empty($get['file_payment'])) {

        $path = "../assets/images/uploads/expense_reports/" . $get['file_payment'];

        if (file_exists($path)) {
            unlink($path);
        }
    }

    mysqli_query($conn, "
        DELETE FROM report_expense
        WHERE id='$id'
    ");

    echo "<script>
        alert('Data berhasil dihapus!');
        window.location='manage_expense_reports.php';
    </script>";
}

/* =========================================
   FILTER
========================================= */

$filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

$where = "WHERE 1=1";

if ($filterBulan !== '') {

    $bulan = $filterBulan + 1;

    $where .= " AND MONTH(date_expense) = '$bulan'";
}

if ($filterTahun !== '') {

    $where .= " AND YEAR(date_expense) = '$filterTahun'";
}

/* =========================================
   TOTAL
========================================= */

$totalQuery = mysqli_query($conn, "
    SELECT SUM(amount_expense) as total_expense
    FROM report_expense
    $where
");

$totalData = mysqli_fetch_assoc($totalQuery);

$totalExpense = $totalData['total_expense'] ?? 0;

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Laporan Pengeluaran - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Laporan Pengeluaran
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Laporan Pengeluaran</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- ================= FORM INPUT ================= -->
                <div class="card mb-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="card-body">

                            <h4 class="mb-3">
                                <span class="material-icons">add_circle</span>
                                Tambah Laporan Pengeluaran
                            </h4>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tanggal</label>
                                    <input type="date" name="date_expense" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label>Type Pengeluaran</label>
                                    <select name="type_expense" class="form-control">

                                        <option value="">
                                            -- Pilih Type --
                                        </option>

                                        <option value="Biaya Operasional (Opex)">
                                            Biaya Operasional (Opex)
                                        </option>

                                        <option value="Non-Operasional">
                                            Non-Operasional
                                        </option>

                                        <option value="Belanja Modal (Capex)">
                                            Belanja Modal (Capex)
                                        </option>

                                        <option value="Biaya Tetap">
                                            Biaya Tetap
                                        </option>

                                        <option value="Biaya Variabel">
                                            Biaya Variabel
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Sumber Pengeluaran</label>
                                    <input type="text" name="title_expense" class="form-control" placeholder="Contoh: Beli Kertas">
                                </div>

                                <div class="col-md-3">
                                    <label>Jumlah (Rp)</label>
                                    <input type="text" name="amount_expense" class="form-control" oninput="formatInputRupiah(this)">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label>Penjelasan Pengeluaran</label>
                                    <textarea name="desc_expense" class="form-control"></textarea>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label>Upload Bukti (PDF / Image)</label>
                                    <input type="file" name="file_payment" class="form-control" accept=".pdf,image/*">
                                    <small class="form-text text-muted">Format file yang diterima: PDF, JPG, PNG. Jika Laporan Banyak, silakan merge gambar menjadi file PDF</small>
                                </div>
                            </div>

                            <div class="mt-3 text-right">
                                <button type="submit" name="add_expense" class="btn btn-primary">
                                    <span class="material-icons">save</span>
                                    Simpan
                                </button>
                            </div>

                        </div>
                    </form>
                </div>


                <!-- ================= TABLE ================= -->
                <div class="card">
                    <div class="card-body">

                        <!-- HEADER + TOTAL -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>
                                <span class="material-icons">receipt</span>
                                Daftar Laporan Pengeluaran
                            </h4>

                            <div style="text-align:right; min-width:250px;">

                                <div class="mt-2">
                                    <small>Total Pengeluaran</small><br>
                                    <strong id="totalPengeluaranText">Total Rp. <?= number_format($totalExpense, 0, ',', '.'); ?></strong>
                                </div>

                            </div>

                        </div>

                        <!-- FILTER -->
                        <form method="GET">
                            <div class="row mb-3 align-items-center">

                                <!-- LEFT: FILTER -->
                                <div class="col-md-6 d-flex gap-2">
                                    <select name="bulan"
                                        class="form-control mr-2"
                                        onchange="this.form.submit()">
                                        <option value="">Semua Bulan</option>
                                        <option value="0" <?= $filterBulan === '0' ? 'selected' : ''; ?>>Jan</option>
                                        <option value="1" <?= $filterBulan === '1' ? 'selected' : ''; ?>>Feb</option>
                                        <option value="2" <?= $filterBulan === '2' ? 'selected' : ''; ?>>Mar</option>
                                        <option value="3" <?= $filterBulan === '3' ? 'selected' : ''; ?>>Apr</option>
                                        <option value="4" <?= $filterBulan === '4' ? 'selected' : ''; ?>>Mei</option>
                                        <option value="5" <?= $filterBulan === '5' ? 'selected' : ''; ?>>Jun</option>
                                        <option value="6" <?= $filterBulan === '6' ? 'selected' : ''; ?>>Jul</option>
                                        <option value="7" <?= $filterBulan === '7' ? 'selected' : ''; ?>>Agu</option>
                                        <option value="8" <?= $filterBulan === '8' ? 'selected' : ''; ?>>Sep</option>
                                        <option value="9" <?= $filterBulan === '9' ? 'selected' : ''; ?>>Okt</option>
                                        <option value="10" <?= $filterBulan === '10' ? 'selected' : ''; ?>>Nov</option>
                                        <option value="11" <?= $filterBulan === '11' ? 'selected' : ''; ?>>Des</option>
                                    </select>

                                    <select name="tahun"
                                        class="form-control"
                                        onchange="this.form.submit()">
                                        <option value="">Semua Tahun</option>

                                        <?php
                                        $currentYear = date('Y');

                                        for ($i = $currentYear; $i >= 2020; $i--) :
                                        ?>

                                            <option
                                                value="<?= $i; ?>"
                                                <?= $filterTahun == $i ? 'selected' : ''; ?>>

                                                <?= $i; ?>

                                            </option>

                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <!-- RIGHT: SHOW + SEARCH -->
                                <div class="col-md-6 d-flex justify-content-end align-items-center">

                                    <!-- SHOW ENTRIES -->
                                    <div class="d-flex align-items-center mr-3">
                                        <span class="mr-2">Show</span>
                                        <select id="showEntries" class="form-control" style="width:80px;" onchange="renderTable()">
                                            <option>5</option>
                                            <option selected>10</option>
                                            <option>25</option>
                                        </select>
                                        <span class="ml-2">entries</span>
                                    </div>

                                    <!-- SEARCH -->
                                    <input type="text" id="searchInput" placeholder="Search..."
                                        class="form-control" style="width:200px;" onkeyup="renderTable()">

                                </div>

                            </div>
                        </form>

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Type Pengeluaran</th>
                                        <th>Sumber</th>
                                        <th>Jumlah</th>
                                        <th>Deskripsi</th>
                                        <th>Bukti</th>
                                        <th>Tanggal Update</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    $no = 1;

                                    $query = mysqli_query($conn, "
    SELECT *
    FROM report_expense
    $where
    ORDER BY id DESC
");

                                    while ($row = mysqli_fetch_assoc($query)) :

                                        $file = $row['file_payment'];

                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                                    ?>

                                        <tr>

                                            <td><?= $no++; ?></td>

                                            <td>
                                                <?= date('d/m/Y', strtotime($row['date_expense'])); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['type_expense']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['title_expense']); ?>
                                            </td>

                                            <td>
                                                Rp. <?= number_format($row['amount_expense'], 0, ',', '.'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['desc_expense']); ?>
                                            </td>

                                            <td>

                                                <?php if (!empty($file)) : ?>

                                                    <?php if ($ext == 'pdf') : ?>

                                                        <button
                                                            class="btn btn-info btn-sm"
                                                            onclick="window.open('../assets/images/uploads/expense_reports/<?= $file ?>')">

                                                            PDF

                                                        </button>

                                                    <?php else : ?>

                                                        <img
                                                            src="../assets/images/uploads/expense_reports/<?= $file ?>"
                                                            width="50"
                                                            style="cursor:pointer"
                                                            onclick="window.open('../assets/images/uploads/expense_reports/<?= $file ?>')">

                                                    <?php endif; ?>

                                                <?php else : ?>

                                                <?php endif; ?>

                                            </td>

                                            <td>
                                                <?= date('d/m/Y H:i', strtotime($row['update_at'])); ?>
                                            </td>

                                            <td>

                                                <button
                                                    class="btn btn-warning btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#editModal<?= $row['id']; ?>">

                                                    <span class="material-icons">edit</span>

                                                </button>

                                                <a
                                                    href="?delete=<?= $row['id']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus data?')">

                                                    <span class="material-icons">delete</span>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                </tbody>
                            </table>
                            <div id="pagination" class="mt-3 text-center"></div>
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

    <!-- ********************************** // MODAL ********************************** -->
    <?php

    $queryModal = mysqli_query($conn, "
    SELECT *
    FROM report_expense
    ORDER BY id DESC
");

    while ($modal = mysqli_fetch_assoc($queryModal)) :

    ?>

        <div class="modal fade"
            id="editModal<?= $modal['id']; ?>"
            tabindex="-1">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Edit Pengeluaran
                            </h5>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal">

                                <span>&times;</span>

                            </button>

                        </div>

                        <div class="modal-body" style="overflow-y:auto; max-height:70vh;">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $modal['id']; ?>">

                            <label>Tanggal</label>

                            <input
                                type="date"
                                name="date_expense"
                                class="form-control mb-3"
                                value="<?= $modal['date_expense']; ?>">

                            <label>Type Pengeluaran</label>

                            <select name="type_expense" class="form-control">

                                <option value="">
                                    -- Pilih Type --
                                </option>

                                <option value="Biaya Operasional (Opex)">
                                    Biaya Operasional (Opex)
                                </option>

                                <option value="Non-Operasional">
                                    Non-Operasional
                                </option>

                                <option value="Belanja Modal (Capex)">
                                    Belanja Modal (Capex)
                                </option>

                                <option value="Biaya Tetap">
                                    Biaya Tetap
                                </option>

                                <option value="Biaya Variabel">
                                    Biaya Variabel
                                </option>

                            </select>

                            <label>Judul</label>

                            <input
                                type="text"
                                name="title_expense"
                                class="form-control mb-3"
                                value="<?= htmlspecialchars($modal['title_expense']); ?>">

                            <label>Jumlah</label>

                            <input
                                type="text"
                                name="amount_expense"
                                class="form-control mb-3"
                                value="<?= number_format($modal['amount_expense'], 0, ',', '.'); ?>"
                                oninput="formatInputRupiah(this)">

                            <label>Deskripsi</label>

                            <textarea
                                name="desc_expense"
                                class="form-control mb-3"><?= htmlspecialchars($modal['desc_expense']); ?></textarea>

                            <label>Preview Bukti</label>

                            <div class="mb-3">

                                <?php
                                $fileModal = $modal['file_payment'];
                                $extModal = strtolower(pathinfo($fileModal, PATHINFO_EXTENSION));
                                ?>

                                <?php if (!empty($fileModal)) : ?>

                                    <?php if ($extModal == 'pdf') : ?>

                                        <button
                                            type="button"
                                            class="btn btn-info"
                                            onclick="window.open('../assets/images/uploads/expense_reports/<?= $fileModal ?>')">

                                            Lihat PDF

                                        </button>

                                    <?php else : ?>

                                        <img
                                            src="../assets/images/uploads/expense_reports/<?= $fileModal ?>"
                                            width="120"
                                            class="img-thumbnail">

                                    <?php endif; ?>

                                <?php else : ?>

                                <?php endif; ?>

                            </div>

                            <label>Upload Bukti Baru</label>

                            <input
                                type="file"
                                name="file_payment"
                                class="form-control"
                                accept=".pdf,image/*">

                        </div>

                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">

                                Tutup

                            </button>

                            <button
                                type="submit"
                                name="update_expense"
                                class="btn btn-primary">

                                Update

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endwhile; ?>
    <!-- ********************************** // MODAL ********************************** -->

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
    <script src="../assets/js/costume-dom/m.ex-reports.js"></script>

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
        // =========================================
        // FORMAT INPUT RUPIAH
        // =========================================
        function formatInputRupiah(el) {

            let angka = el.value.replace(/\D/g, '');

            if (angka === '') {
                el.value = '';
                return;
            }

            let formatted = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            el.value = 'Rp. ' + formatted;
        }

        // =========================================
        // INIT
        // =========================================
        let currentPage = 1;

        const table = document.querySelector("table tbody");

        const rows = Array.from(
            table.querySelectorAll("tbody > tr")
        );

        // =========================================
        // RENDER TABLE
        // =========================================
        function renderTable() {

            let search = document
                .getElementById("searchInput")
                .value
                .toLowerCase();

            let show = parseInt(
                document.getElementById("showEntries").value
            );

            // FILTER SEARCH
            let filtered = rows.filter(row => {

                return row.innerText
                    .toLowerCase()
                    .includes(search);

            });

            // PAGINATION
            let totalPage = Math.ceil(filtered.length / show);

            if (currentPage > totalPage) {
                currentPage = totalPage;
            }

            if (currentPage < 1) {
                currentPage = 1;
            }

            let start = (currentPage - 1) * show;
            let end = start + show;

            rows.forEach(row => row.style.display = "none");

            filtered.slice(start, end).forEach(row => {
                row.style.display = "";
            });

            renderPagination(totalPage);
        }

        // =========================================
        // PAGINATION
        // =========================================
        function renderPagination(totalPage) {

            let container = document.getElementById("pagination");

            if (totalPage <= 1) {
                container.innerHTML = "";
                return;
            }

            let html = `<ul class="pagination justify-content-center">`;

            // PREV
            html += `
        <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
            <a class="page-link"
               href="#"
               onclick="changePage(${currentPage - 1})">

               Prev

            </a>
        </li>
        `;

            let start = Math.max(1, currentPage - 2);
            let end = Math.min(totalPage, currentPage + 2);

            // FIRST
            if (start > 1) {

                html += `
            <li class="page-item">
                <a class="page-link"
                   href="#"
                   onclick="changePage(1)">

                   1

                </a>
            </li>
            `;

                if (start > 2) {

                    html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                `;
                }
            }

            // MIDDLE
            for (let i = start; i <= end; i++) {

                html += `
            <li class="page-item ${currentPage == i ? 'active' : ''}">
                <a class="page-link"
                   href="#"
                   onclick="changePage(${i})">

                   ${i}

                </a>
            </li>
            `;
            }

            // LAST
            if (end < totalPage) {

                if (end < totalPage - 1) {

                    html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                `;
                }

                html += `
            <li class="page-item">
                <a class="page-link"
                   href="#"
                   onclick="changePage(${totalPage})">

                   ${totalPage}

                </a>
            </li>
            `;
            }

            // NEXT
            html += `
        <li class="page-item ${currentPage == totalPage ? 'disabled' : ''}">
            <a class="page-link"
               href="#"
               onclick="changePage(${currentPage + 1})">

               Next

            </a>
        </li>
        `;

            html += `</ul>`;

            container.innerHTML = html;
        }

        // =========================================
        // CHANGE PAGE
        // =========================================
        function changePage(page) {

            let search = document
                .getElementById("searchInput")
                .value
                .toLowerCase();

            let show = parseInt(
                document.getElementById("showEntries").value
            );

            let filtered = rows.filter(row => {

                return row.innerText
                    .toLowerCase()
                    .includes(search);

            });

            let totalPage = Math.ceil(filtered.length / show);

            if (page < 1) page = 1;

            if (page > totalPage) page = totalPage;

            currentPage = page;

            renderTable();
        }

        // =========================================
        // EVENT
        // =========================================
        document
            .getElementById("searchInput")
            .addEventListener("keyup", function() {

                currentPage = 1;

                renderTable();
            });

        document
            .getElementById("showEntries")
            .addEventListener("change", function() {

                currentPage = 1;

                renderTable();
            });

        // =========================================
        // FIRST LOAD
        // =========================================
        renderTable();
    </script>

</body>

</html>