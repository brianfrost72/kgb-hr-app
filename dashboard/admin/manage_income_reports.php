<?php
session_start();
require_once __DIR__ . "/../koneksi.php";

date_default_timezone_set('Asia/Jakarta');

function cleanRupiah($value)
{
    return preg_replace('/[^0-9]/', '', $value);
}

/* =========================================
   TAMBAH DATA
========================================= */
if (isset($_POST['add_income'])) {

    $date_income      = $_POST['date_income'];
    $category_income  = trim($_POST['category_income']);
    $source_income    = trim($_POST['source_income']);
    $amount_income    = cleanRupiah($_POST['amount_income']);
    $payment_method   = trim($_POST['payment_method']);

    if (
        empty($date_income) ||
        empty($category_income) ||
        empty($source_income) ||
        empty($amount_income) ||
        empty($payment_method)
    ) {

        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {

        $picture_payment = '';

        if (!empty($_FILES['picture_payment']['name'])) {

            $targetDir = "../assets/images/uploads/income_reports/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES["picture_payment"]["name"]);
            $targetFile = $targetDir . $fileName;

            $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            if (in_array($ext, $allowed)) {

                if (move_uploaded_file($_FILES["picture_payment"]["tmp_name"], $targetFile)) {
                    $picture_payment = $fileName;
                }
            }
        }

        $insert = mysqli_query($conn, "
            INSERT INTO report_income
            (
                date_income,
                category_income,
                source_income,
                amount_income,
                payment_method,
                picture_payment,
                update_at
            )
            VALUES
            (
                '$date_income',
                '$category_income',
                '$source_income',
                '$amount_income',
                '$payment_method',
                '$picture_payment',
                NOW()
            )
        ");

        if ($insert) {
            echo "<script>alert('Laporan pemasukan berhasil ditambahkan!'); window.location='manage_income_reports';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data!');</script>";
        }
    }
}

/* =========================================
   DELETE DATA
========================================= */
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    $get = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT picture_payment
        FROM report_income
        WHERE id='$id'
    "));

    if (!empty($get['picture_payment'])) {

        $path = "../assets/images/uploads/income_reports/" . $get['picture_payment'];

        if (file_exists($path)) {
            unlink($path);
        }
    }

    mysqli_query($conn, "
        DELETE FROM report_income
        WHERE id='$id'
    ");

    echo "<script>alert('Data berhasil dihapus!'); window.location='manage_income_reports';</script>";
}

/* ================= FILTER ================= */

$filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

$where = "WHERE 1=1";

if ($filterBulan !== '') {

    $bulan = $filterBulan + 1;

    $where .= " AND MONTH(date_income) = '$bulan'";
}

if ($filterTahun !== '') {

    $where .= " AND YEAR(date_income) = '$filterTahun'";
}

/* ================= TOTAL ================= */

$totalQuery = mysqli_query($conn, "
    SELECT SUM(amount_income) as total_income
    FROM report_income
    $where
");

$totalData = mysqli_fetch_assoc($totalQuery);

$totalIncome = $totalData['total_income'] ?? 0;

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Laporan Pemasukan - Dashboard | Konig Guard Bureau</title>
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
                                        Manage Laporan Pemasukan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Laporan Pemasukan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- ================= FORM INPUT ================= -->
                <div class="card mb-4">
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">
                            <h4 class="mb-3">
                                <span class="material-icons">add_circle</span>
                                Tambah Laporan Pemasukan
                            </h4>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tanggal</label>
                                    <input type="date" name="date_income" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label>Kategori Penjualan</label>
                                    <select name="category_income" class="form-control">
                                        <option>--Pilih Kategori--</option>
                                        <option>Penjualan</option>
                                        <option>Jasa</option>
                                        <option>Lain-lain</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Sumber</label>
                                    <input type="text" name="source_income" class="form-control" placeholder="Contoh: PT KARISMA">
                                </div>

                                <div class="col-md-3">
                                    <label>Jumlah (Rp)</label>
                                    <input type="text" name="amount_income" class="form-control" oninput="formatInputRupiah(this)" placeholder="Masukkan Nominal...">
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label>Metode Pembayaran</label>
                                    <select name="payment_method" class="form-control">
                                        <option>--Pilih Metode--</option>
                                        <option>Transfer Bank</option>
                                        <option>Tunai</option>
                                        <option>E-Wallet</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <label>Upload Bukti (PDF / Image)</label>
                                    <input type="file" name="picture_payment" class="form-control" accept=".image/*">
                                </div>
                            </div>

                            <div class="mt-3 text-right">
                                <button type="submit" name="add_income" class="btn btn-primary">
                                    <span class="material-icons">save</span>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- ================= TABLE ================= -->
                <div class="card">
                    <div class="card-body">

                        <!-- HEADER + TOTAL -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>
                                <span class="material-icons">receipt</span>
                                Daftar Laporan Pemasukan
                            </h4>

                            <h4 style="font-weight:bold;">
                                Total Rp. <?= number_format($totalIncome, 0, ',', '.'); ?>,-
                            </h4>

                        </div>

                        <form method="GET">
                            <!-- FILTER -->
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
                                        <th>Kategori Pemasukan</th>
                                        <th>Sumber</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        <th>Tanggal Update</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $no = 1;
                                    /* ================= TABLE ================= */

                                    $query = mysqli_query($conn, "
    SELECT *
    FROM report_income
    $where
    ORDER BY id DESC
");

                                    while ($row = mysqli_fetch_assoc($query)) :

                                        $file = $row['picture_payment'];
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    ?>

                                        <tr>

                                            <td><?= $no++; ?></td>

                                            <td>
                                                <?= date('d/m/Y', strtotime($row['date_income'])); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['category_income']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['source_income']); ?>
                                            </td>

                                            <td>
                                                Rp. <?= number_format($row['amount_income'], 0, ',', '.'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['payment_method']); ?>
                                            </td>

                                            <td>

                                                <?php if (!empty($file)) : ?>

                                                    <?php if ($ext == 'pdf') : ?>

                                                        <button
                                                            class="btn btn-sm btn-info"
                                                            onclick="window.open('../assets/images/uploads/income_reports/<?= $file ?>')">
                                                            PDF
                                                        </button>

                                                    <?php else : ?>

                                                        <img
                                                            src="../assets/images/uploads/income_reports/<?= $file ?>"
                                                            width="50"
                                                            style="cursor:pointer"
                                                            onclick="window.open('../assets/images/uploads/income_reports/<?= $file ?>')">

                                                    <?php endif; ?>

                                                <?php else : ?>

                                                    -

                                                <?php endif; ?>

                                            </td>

                                            <td>
                                                <?= date('d/m/Y H:i', strtotime($row['update_at'])); ?>
                                            </td>

                                            <td>

                                                <!-- EDIT -->
                                                <button
                                                    class="btn btn-sm btn-warning"
                                                    data-toggle="modal"
                                                    data-target="#editModal<?= $row['id']; ?>">

                                                    <span class="material-icons">edit</span>
                                                </button>

                                                <!-- DELETE -->
                                                <a
                                                    href="?delete=<?= $row['id']; ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus data ini?')">

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
    <!-- MODAL EDIT -->
    <?php
    $queryModal = mysqli_query($conn, "
    SELECT *
    FROM report_income
    ORDER BY id DESC
");

    while ($modal = mysqli_fetch_assoc($queryModal)) :

        $fileModal = $modal['picture_payment'];
        $extModal = strtolower(pathinfo($fileModal, PATHINFO_EXTENSION));
    ?>

        <div class="modal fade"
            id="editModal<?= $modal['id']; ?>"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Edit Laporan
                        </h5>

                        <button type="button"
                            class="close"
                            data-dismiss="modal">

                            <span>&times;</span>
                        </button>
                    </div>

                    <form method="POST" enctype="multipart/form-data">

                        <div class="modal-body" style="overflow-y:auto; max-height:70vh;">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $modal['id']; ?>">

                            <label>Tanggal</label>
                            <input
                                type="date"
                                name="date_income"
                                class="form-control mb-3"
                                value="<?= $modal['date_income']; ?>">

                            <label>Kategori</label>

                            <select
                                name="category_income"
                                class="form-control mb-3">

                                <option <?= $modal['category_income'] == 'Penjualan' ? 'selected' : ''; ?>>
                                    Penjualan
                                </option>

                                <option <?= $modal['category_income'] == 'Jasa' ? 'selected' : ''; ?>>
                                    Jasa
                                </option>

                                <option <?= $modal['category_income'] == 'Lain-lain' ? 'selected' : ''; ?>>
                                    Lain-lain
                                </option>

                            </select>

                            <label>Sumber</label>

                            <input
                                type="text"
                                name="source_income"
                                class="form-control mb-3"
                                value="<?= htmlspecialchars($modal['source_income']); ?>">

                            <label>Jumlah</label>

                            <input
                                type="text"
                                name="amount_income"
                                class="form-control mb-3"
                                value="Rp. <?= number_format($modal['amount_income'], 0, ',', '.'); ?>"
                                oninput="formatInputRupiah(this)">

                            <label>Metode Pembayaran</label>

                            <select
                                name="payment_method"
                                class="form-control mb-3">

                                <option <?= $modal['payment_method'] == 'Transfer Bank' ? 'selected' : ''; ?>>
                                    Transfer Bank
                                </option>

                                <option <?= $modal['payment_method'] == 'Tunai' ? 'selected' : ''; ?>>
                                    Tunai
                                </option>

                                <option <?= $modal['payment_method'] == 'E-Wallet' ? 'selected' : ''; ?>>
                                    E-Wallet
                                </option>

                            </select>

                            <label>Preview Bukti</label>

                            <div class="mb-3">

                                <?php if (!empty($fileModal)) : ?>

                                    <?php if ($extModal == 'pdf') : ?>

                                        <button
                                            type="button"
                                            class="btn btn-info"
                                            onclick="window.open('../assets/images/uploads/income_reports/<?= $fileModal ?>')">

                                            Lihat PDF
                                        </button>

                                    <?php else : ?>

                                        <img
                                            src="../assets/images/uploads/income_reports/<?= $fileModal ?>"
                                            width="120"
                                            class="img-thumbnail">

                                    <?php endif; ?>

                                <?php else : ?>

                                    -

                                <?php endif; ?>

                            </div>

                            <label>Upload Bukti Baru</label>

                            <input
                                type="file"
                                name="picture_payment"
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
                                name="update_income"
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
    <script src="../assets/js/costume-dom/m.in-reports.js"></script>

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

        const rows = Array.from(table.querySelectorAll("tr"));

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

            // =========================================
            // PAGINATION
            // =========================================
            let totalPage = Math.ceil(filtered.length / show);

            if (currentPage > totalPage) {
                currentPage = totalPage;
            }

            if (currentPage < 1) {
                currentPage = 1;
            }

            let start = (currentPage - 1) * show;
            let end = start + show;

            // HIDE ALL
            rows.forEach(row => row.style.display = "none");

            // SHOW FILTERED
            filtered.slice(start, end).forEach(row => {
                row.style.display = "";
            });

            renderPagination(totalPage);
        }

        // =========================================
        // PAGINATION BUTTON
        // =========================================
        function renderPagination(totalPage) {

            let container = document.getElementById("pagination");

            if (totalPage <= 1) {
                container.innerHTML = "";
                return;
            }

            let html = `<ul class="pagination justify-content-center">`;

            // =====================================
            // PREV
            // =====================================
            html += `
    <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
        <a class="page-link"
           href="#"
           onclick="changePage(${currentPage - 1})">

           Prev

        </a>
    </li>
    `;

            // =====================================
            // PAGE RANGE
            // =====================================
            let start = Math.max(1, currentPage - 2);
            let end = Math.min(totalPage, currentPage + 2);

            // FIRST PAGE
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

                // DOTS
                if (start > 2) {

                    html += `
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            `;
                }
            }

            // MIDDLE PAGE
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

            // LAST PAGE
            if (end < totalPage) {

                // DOTS
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

            // =====================================
            // NEXT
            // =====================================
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