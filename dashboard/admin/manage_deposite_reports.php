<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

/* =========================================================
   GET DATA PEMASUKAN
========================================================= */
$income_query = mysqli_query($conn, "
    SELECT *
    FROM report_income
    ORDER BY date_income DESC
");

$income_data = [];
$total_income = 0;

while ($row = mysqli_fetch_assoc($income_query)) {

    $row['date_sort'] = strtotime($row['date_income']);

    $income_data[] = $row;

    $total_income += (int)$row['amount_income'];
}

/* =========================================================
   GET DATA PENGELUARAN
========================================================= */
$expense_query = mysqli_query($conn, "
    SELECT *
    FROM report_expense
    ORDER BY date_expense DESC
");

$expense_data = [];
$total_expense = 0;

while ($row = mysqli_fetch_assoc($expense_query)) {

    $row['date_sort'] = strtotime($row['date_expense']);

    $expense_data[] = $row;

    $total_expense += (int)$row['amount_expense'];
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
    <title>Manage Laporan Deposit - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Laporan Deposit
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Laporan Deposit</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- SUMMARY -->
                <div class="card p-4 text-center mt-4 mb-4">
                    <h5>Total Deposit</h5>
                    <h1 id="totalDeposit" style="font-weight:700;">
                        Rp. <?= number_format($total_income, 0, ',', '.'); ?>
                    </h1>

                    <hr>

                    <h6>Total Pengeluaran</h6>
                    <h4 id="totalPengeluaran" class="text-danger">
                        Rp. <?= number_format($total_expense, 0, ',', '.'); ?>
                    </h4>
                </div>

                <!-- ===================== TABEL PEMASUKAN ===================== -->
                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h5><span class="material-icons text-success">trending_up</span> Tabel Pemasukan</h5>

                        <div class="d-flex gap-2">
                            <select id="filterBulanMasuk" class="form-control">
                                <option value="">Semua Bulan</option>
                            </select>

                            <select id="filterTahunMasuk" class="form-control">
                                <option value="">Semua Tahun</option>
                            </select>

                            <select id="filterTypeMasuk" class="form-control">
                                <option value="">Semua Metode Pembayaran</option>
                                <option>Tunai</option>
                                <option>Transfer Bank</option>
                            </select>

                            <input type="text" id="searchMasuk" class="form-control" placeholder="Search...">
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-2" style="gap:10px;">
                        <span>Show</span>
                        <select id="entriesMasuk" class="form-control" style="width:80px;">
                            <option>5</option>
                            <option>10</option>
                            <option>25</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Sumber</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody id="tableMasuk">

                                <?php foreach ($income_data as $income): ?>

                                    <tr
                                        data-bulan="<?= date('m', strtotime($income['date_income'])); ?>"
                                        data-tahun="<?= date('Y', strtotime($income['date_income'])); ?>"
                                        data-type="<?= strtolower($income['payment_method']); ?>">

                                        <td>
                                            <?= date('d M Y', strtotime($income['date_income'])); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($income['category_income']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($income['source_income']); ?>
                                        </td>

                                        <td class="text-success font-weight-bold">
                                            Rp. <?= number_format($income['amount_income'], 0, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($income['payment_method']); ?>
                                        </td>

                                        <td>

                                            <?php if (!empty($income['picture_payment'])): ?>

                                                <img
                                                    src="/my-dashboard/fixed-v2/dashboard/assets/images/uploads/income_reports/<?= htmlspecialchars($income['picture_payment']); ?>"
                                                    class="preview-image"
                                                    style="width:70px; height:70px; object-fit:cover; border-radius:8px; cursor:pointer;">

                                            <?php else: ?>

                                                -

                                            <?php endif; ?>

                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-2">
                            <div id="paginationMasuk"></div>
                        </div>
                    </div>
                </div>

                <!-- ===================== TABEL PENGELUARAN ===================== -->
                <div class="card p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <h5><span class="material-icons text-danger">trending_down</span> Tabel Pengeluaran</h5>

                        <div class="d-flex gap-2">
                            <select id="filterBulanKeluar" class="form-control">
                                <option value="">Bulan</option>
                            </select>

                            <select id="filterTahunKeluar" class="form-control">
                                <option value="">Tahun</option>
                            </select>

                            <select id="filterTypeKeluar" class="form-control">
                                <option value="">Semua Type</option>
                                <option value="biaya operasional (opex)">Biaya Operasional (Opex)</option>
                                <option value="non-operasional">Non-Operasional</option>
                                <option value="belanja modal (capex)">Belanja Modal (Capex)</option>
                                <option value="biaya tetap">Biaya Tetap</option>
                                <option value="biaya variabel">Biaya Variabel</option>
                            </select>

                            <input type="text" id="searchKeluar" class="form-control" placeholder="Search...">
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-2" style="gap:10px;">
                        <span>Show</span>
                        <select id="entriesKeluar" class="form-control" style="width:80px;">
                            <option>5</option>
                            <option>10</option>
                            <option>25</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Type Pengeluaran</th>
                                    <th>Judul</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody id="tableKeluar">

                                <?php foreach ($expense_data as $expense): ?>

                                    <tr
                                        data-bulan="<?= date('m', strtotime($expense['date_expense'])); ?>"
                                        data-tahun="<?= date('Y', strtotime($expense['date_expense'])); ?>"
                                        data-type="<?= strtolower($expense['type_expense']); ?>">

                                        <td>
                                            <?= date('d M Y', strtotime($expense['date_expense'])); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($expense['type_expense']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($expense['title_expense']); ?>
                                        </td>

                                        <td class="text-danger font-weight-bold">
                                            Rp. <?= number_format($expense['amount_expense'], 0, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($expense['desc_expense']); ?>
                                        </td>

                                        <td>

                                            <?php if (!empty($expense['file_payment'])): ?>

                                                <img
                                                    src="/my-dashboard/fixed-v2/dashboard/assets/images/uploads/expense_reports/<?= htmlspecialchars($expense['file_payment']); ?>"
                                                    class="preview-image"
                                                    style="width:70px; height:70px; object-fit:cover; border-radius:8px; cursor:pointer;">

                                            <?php else: ?>

                                                -

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-2">
                            <div id="paginationKeluar"></div>
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

    <div id="previewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center;">
        <img id="previewImg" style="max-width:80%; max-height:80%;">
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
        /* =========================================================
       PREVIEW IMAGE
    ========================================================= */
        $(document).on('click', '.preview-image', function() {

            $('#previewImg').attr('src', $(this).attr('src'));

            $('#previewModal').css('display', 'flex');

        });

        $('#previewModal').click(function() {

            $(this).hide();

        });

        /* =========================================================
           GENERATE BULAN & TAHUN
        ========================================================= */
        function generateFilter(selectBulan, selectTahun, dateField) {

            const bulanNama = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            for (let i = 1; i <= 12; i++) {

                $(selectBulan).append(`
                <option value="${String(i).padStart(2, '0')}">
                    ${bulanNama[i - 1]}
                </option>
            `);
            }

            const currentYear = new Date().getFullYear();

            for (let y = currentYear; y >= currentYear - 10; y--) {

                $(selectTahun).append(`
                <option value="${y}">
                    ${y}
                </option>
            `);
            }
        }

        generateFilter('#filterBulanMasuk', '#filterTahunMasuk');
        generateFilter('#filterBulanKeluar', '#filterTahunKeluar');

        /* =========================================================
           TABLE FILTER + PAGINATION
        ========================================================= */
        function setupTable(tableId, searchId, bulanId, tahunId, typeId, entriesId, paginationId) {

            let currentPage = 1;

            function renderTable() {

                let rows = $(`${tableId} tr`);

                let keyword = $(searchId).val().toLowerCase();

                let bulan = $(bulanId).val();

                let tahun = $(tahunId).val();

                let type = $(typeId).val().toLowerCase();

                let entries = parseInt($(entriesId).val());

                let filtered = [];

                rows.each(function() {

                    let text = $(this).text().toLowerCase();

                    let rowBulan = $(this).data('bulan').toString();

                    let rowTahun = $(this).data('tahun').toString();

                    let rowType = $(this).data('type').toString();

                    let match =
                        text.includes(keyword) &&
                        (bulan === '' || bulan === rowBulan) &&
                        (tahun === '' || tahun === rowTahun) &&
                        (type === '' || rowType === type);

                    if (match) {

                        filtered.push(this);

                    }

                    $(this).hide();

                });

                let totalPages = Math.ceil(filtered.length / entries);

                if (currentPage > totalPages) {

                    currentPage = 1;

                }

                let start = (currentPage - 1) * entries;

                let end = start + entries;

                filtered.slice(start, end).forEach(row => {

                    $(row).show();

                });

                /* PAGINATION */
                let pagination = '';

                for (let i = 1; i <= totalPages; i++) {

                    pagination += `
                    <button
                        class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-light'} page-btn"
                        data-page="${i}"
                        style="margin:2px;">
                        ${i}
                    </button>
                `;
                }

                $(paginationId).html(pagination);

            }

            $(document).on('click', `${paginationId} .page-btn`, function() {

                currentPage = parseInt($(this).data('page'));

                renderTable();

            });

            $(searchId + ',' + bulanId + ',' + tahunId + ',' + typeId + ',' + entriesId)
                .on('keyup change', function() {

                    currentPage = 1;

                    renderTable();

                });

            renderTable();
        }

        setupTable(
            '#tableMasuk',
            '#searchMasuk',
            '#filterBulanMasuk',
            '#filterTahunMasuk',
            '#filterTypeMasuk',
            '#entriesMasuk',
            '#paginationMasuk'
        );

        setupTable(
            '#tableKeluar',
            '#searchKeluar',
            '#filterBulanKeluar',
            '#filterTahunKeluar',
            '#filterTypeKeluar',
            '#entriesKeluar',
            '#paginationKeluar'
        );
    </script>
</body>

</html>