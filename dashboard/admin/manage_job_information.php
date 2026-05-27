<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/* =========================================
   QUERY JOB VACANCY + REGION
========================================= */
$jobVacancy = mysqli_query($conn, "
    SELECT
        job_vacancy.id,
        job_vacancy.job_title,
        job_vacancy.id_region,
        job_vacancy.type_vacancy,
        job_vacancy.job_desc,
        job_vacancy.job_quota,
        job_vacancy.start_info,
        job_vacancy.end_info,
        job_vacancy.link_info,
        job_vacancy.status,

        regions.region_name,
        regions.region_address

    FROM job_vacancy

    LEFT JOIN regions
    ON job_vacancy.id_region = regions.id

    ORDER BY job_vacancy.id DESC
");

/* =========================================
   TOTAL LOWONGAN
========================================= */
$totalLowongan = mysqli_num_rows($jobVacancy);

/* =========================================
   LOWONGAN AKTIF
========================================= */
$totalAktifQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total_aktif
    FROM job_vacancy
    WHERE status = 'Aktif'
");

$totalAktif = mysqli_fetch_assoc($totalAktifQuery);

/* =========================================
   LOWONGAN SELESAI
========================================= */
$totalSelesaiQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total_selesai
    FROM job_vacancy
    WHERE status = 'Tidak Aktif'
");

$totalSelesai = mysqli_fetch_assoc($totalSelesaiQuery);

/* =========================================
   AKAN BERAKHIR
========================================= */
$totalAkanBerakhirQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total_akan_berakhir
    FROM job_vacancy
    WHERE end_info >= CURDATE()
    AND end_info <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
");

$totalAkanBerakhir = mysqli_fetch_assoc($totalAkanBerakhirQuery);

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Lowongan Kerja - Dashboard | Konig Guard Bureau</title>
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
                                        Manage Lowongan Kerja
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Lowongan Kerja</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- HEADER -->
                <div class="card mt-4 mb-4" style="border-radius:12px;">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

                        <div class="d-flex align-items-center">
                            <div style="width:65px;height:65px;border-radius:12px;background:#eef2ff;
                display:flex;align-items:center;justify-content:center;margin-right:18px;">
                                <span class="material-icons" style="font-size:34px;color:var(--primary);">
                                    work
                                </span>
                            </div>

                            <div>
                                <h3 class="mb-1">Daftar Lowongan Kerja</h3>
                                <small class="text-muted">
                                    Kelola semua informasi lowongan kerja yang telah diposting.
                                </small>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- STATISTIC -->
                <div class="row mb-4">

                    <div class="col-md-3">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#eef2ff;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:var(--primary);font-size:30px;">
                                        work
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalLowongan; ?></h3>
                                    <small class="text-muted">Total Lowongan</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#eafaf1;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:var(--success);font-size:30px;">
                                        alarm_on
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalAktif['total_aktif']; ?></h3>
                                    <small class="text-muted">Lowongan Aktif</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#fff6e8;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:#f5b666;font-size:30px;">
                                        alarm
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalAkanBerakhir['total_akan_berakhir']; ?></h3>
                                    <small class="text-muted">Akan Berakhir</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#f3f4f6;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:#6c757d;font-size:30px;">
                                        check_circle
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalSelesai['total_selesai']; ?></h3>
                                    <small class="text-muted">Tidak Aktif</small>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- TABLE HEAD -->
                <div class="card" style="border-radius:12px;">
                    <div class="card-body">

                        <!-- FILTER -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4"
                            style="gap:15px;">

                            <!-- LEFT FILTER -->
                            <div class="d-flex align-items-center flex-wrap"
                                style="gap:15px;">

                                <!-- SHOW ENTRIES -->
                                <div class="d-flex align-items-center">
                                    <label class="mb-0 mr-2 text-muted" style="white-space:nowrap;">
                                        Show
                                    </label>

                                    <select id="showEntries" class="form-control"
                                        style="width:80px;">
                                        <option>10</option>
                                        <option>25</option>
                                        <option>50</option>
                                        <option>100</option>
                                    </select>

                                    <label class="mb-0 ml-2 text-muted" style="white-space:nowrap;">
                                        entries
                                    </label>
                                </div>

                                <!-- SEARCH -->
                                <div style="position:relative;">
                                    <span class="material-icons"
                                        style="position:absolute;
                left:12px;
                top:50%;
                transform:translateY(-50%);
                color:#999;
                font-size:20px;">
                                        search
                                    </span>

                                    <input id="searchInput" type="text"
                                        class="form-control"
                                        placeholder="Cari lowongan kerja..."
                                        style="padding-left:40px;width:260px;">
                                </div>

                            </div>

                            <!-- RIGHT FILTER -->
                            <div class="d-flex align-items-center flex-wrap"
                                style="gap:15px;">

                                <!-- TYPE -->
                                <select id="typeFilter" class="form-control"
                                    style="width:190px;">
                                    <option>--Type Pekerjaan--</option>
                                    <option>Semua Tipe</option>
                                    <option>Full Time</option>
                                    <option>Part Time</option>
                                    <option>Freelance</option>
                                    <option>Magang</option>
                                </select>

                                <!-- LOCATION -->
                                <select id="locationFilter" class="form-control"
                                    style="width:190px;">

                                    <option>--Lokasi--</option>
                                    <option>Semua Lokasi</option>

                                    <?php

                                    $filterRegion = mysqli_query($conn, "
        SELECT *
        FROM regions
        ORDER BY region_name ASC
    ");

                                    while ($fr = mysqli_fetch_assoc($filterRegion)) :

                                    ?>

                                        <option>
                                            <?= htmlspecialchars($fr['region_name']); ?>
                                        </option>

                                    <?php endwhile; ?>

                                </select>

                                <!-- STATUS -->
                                <select id="statusFilter" class="form-control"
                                    style="width:170px;">
                                    <option>--Status--</option>
                                    <option>Semua Status</option>
                                    <option>Aktif</option>
                                    <option>Tidak Aktif</option>
                                    <option>Akan Berakhir</option>
                                </select>

                            </div>

                        </div>

                        <!-- TABLE CONTAIN -->
                        <div class="table-responsive">

                            <table class="table table-hover">

                                <thead style="background:#f8f9fc;">
                                    <tr>
                                        <th>Judul Lowongan</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Post</th>
                                        <th>Kuota Pelamar</th>
                                        <th>Tutup Loker</th>
                                        <th>Status</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="jobTable">

                                    <?php if (mysqli_num_rows($jobVacancy) > 0) : ?>

                                        <?php while ($job = mysqli_fetch_assoc($jobVacancy)) : ?>

                                            <?php

                                            $today = date('Y-m-d');

                                            /* =========================================
   AUTO STATUS DATABASE
========================================= */

                                            /* SUDAH LEWAT */
                                            if ($job['end_info'] < $today) {

                                                mysqli_query($conn, "
        UPDATE job_vacancy
        SET status = 'Tidak Aktif'
        WHERE id = '{$job['id']}'
    ");

                                                $job['status'] = 'Tidak Aktif';
                                            }

                                            /* BELUM MULAI */
                                            if ($job['start_info'] > $today) {

                                                mysqli_query($conn, "
        UPDATE job_vacancy
        SET status = 'Tidak Aktif'
        WHERE id = '{$job['id']}'
    ");

                                                $job['status'] = 'Tidak Aktif';
                                            }

                                            /* MASIH AKTIF */
                                            if (
                                                $job['start_info'] <= $today &&
                                                $job['end_info'] >= $today &&
                                                $job['status'] != 'Tidak Aktif'
                                            ) {

                                                mysqli_query($conn, "
        UPDATE job_vacancy
        SET status = 'Aktif'
        WHERE id = '{$job['id']}'
    ");

                                                $job['status'] = 'Aktif';
                                            }

                                            /* =========================================
   BADGE STATUS
========================================= */
                                            $statusBadge = 'badge-secondary';
                                            $statusText  = $job['status'];

                                            if ($job['status'] == 'Aktif') {

                                                $statusBadge = 'badge-success';
                                            }

                                            /* AKAN BERAKHIR */
                                            if (
                                                $job['status'] == 'Aktif' &&
                                                $job['end_info'] >= $today &&
                                                $job['end_info'] <= date('Y-m-d', strtotime('+7 days'))
                                            ) {

                                                $statusBadge = 'badge-warning';
                                                $statusText  = 'Akan Berakhir';
                                            }
                                            ?>

                                            <tr class="job-row">

                                                <!-- JUDUL -->
                                                <td>
                                                    <strong>
                                                        <?= htmlspecialchars($job['job_title']); ?>
                                                    </strong>
                                                    <br>

                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($job['region_name']); ?>
                                                    </small>
                                                </td>

                                                <!-- TIPE -->
                                                <td>
                                                    <?= htmlspecialchars($job['type_vacancy']); ?>
                                                </td>

                                                <!-- TANGGAL POST -->
                                                <td>
                                                    <?= date('d M Y', strtotime($job['start_info'])); ?>
                                                </td>

                                                <!-- KUOTA -->
                                                <td>
                                                    <?= $job['job_quota']; ?>
                                                </td>

                                                <!-- TANGGAL TUTUP -->
                                                <td>
                                                    <?= date('d M Y', strtotime($job['end_info'])); ?>
                                                </td>

                                                <!-- STATUS -->
                                                <td>

                                                    <span class="badge <?= $statusBadge; ?>"
                                                        style="padding:8px 14px;border-radius:8px;">

                                                        <?= $statusText; ?>

                                                    </span>

                                                </td>

                                                <!-- AKSI -->
                                                <td>

                                                    <div class="d-flex justify-content-center">

                                                        <!-- DETAIL -->
                                                        <button class="btn btn-outline-primary btn-sm mr-2"
                                                            onclick="showDetailLowongan(this)"

                                                            data-id="<?= $job['id']; ?>"
                                                            data-title="<?= htmlspecialchars($job['job_title']); ?>"
                                                            data-location="<?= $job['id_region']; ?>"
                                                            data-type="<?= htmlspecialchars($job['type_vacancy']); ?>"
                                                            data-description="<?= htmlspecialchars(strip_tags($job['job_desc'])); ?>"
                                                            data-quota="<?= $job['job_quota']; ?>"
                                                            data-post="<?= $job['start_info']; ?>"
                                                            data-close="<?= $job['end_info']; ?>"
                                                            data-link="<?= htmlspecialchars($job['link_info']); ?>">


                                                            <span class="material-icons"
                                                                style="font-size:16px;vertical-align:middle;">

                                                                visibility

                                                            </span>

                                                            Lihat Lowongan

                                                        </button>

                                                        <!-- STATUS -->
                                                        <button class="btn btn-outline-primary btn-sm mr-2"
                                                            onclick="toggleStatus(this)"
                                                            data-id="<?= $job['id']; ?>">

                                                            <span class="material-icons"
                                                                style="font-size:16px;vertical-align:middle;">

                                                                check

                                                            </span>

                                                            Selesai

                                                        </button>

                                                        <!-- DELETE -->
                                                        <button
                                                            class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteRow(this)"
                                                            data-id="<?= $job['id']; ?>">

                                                            <span class="material-icons"
                                                                style="font-size:16px;vertical-align:middle;">

                                                                delete

                                                            </span>

                                                            Hapus

                                                        </button>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <tr>

                                            <td colspan="7" class="text-center text-muted py-4">

                                                Tidak ada data lowongan kerja

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                        <!-- PAGINATION -->
                        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap"
                            style="gap:15px;">

                            <!-- INFO -->
                            <small class="text-muted" id="paginationInfo">
                                Menampilkan 1 - 10 dari 120 data
                            </small>

                            <!-- PAGINATION -->
                            <div id="pagination"
                                class="d-flex align-items-center flex-wrap"
                                style="gap:6px; max-width:100%; overflow-x:auto; padding-bottom:4px;">

                            </div>

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

    <!-- DETAIL LOWONGAN -->
    <div id="detailLowongan"
        class="card mt-4"
        style="border-radius:12px; display:none; overflow:hidden;">

        <div class="card-header d-flex justify-content-between align-items-center"
            style="background:#f8f9fc;">

            <div class="d-flex align-items-center">
                <span class="material-icons mr-2"
                    style="color:var(--primary);">
                    visibility
                </span>

                <h5 class="mb-0">
                    Detail Lowongan Kerja
                </h5>
            </div>

            <button class="btn btn-light btn-sm"
                onclick="closeDetailLowongan()">

                <span class="material-icons"
                    style="font-size:18px;vertical-align:middle;">
                    close
                </span>

            </button>

        </div>

        <div class="card-body">

            <form>
                <input type="hidden" id="detailId">
                <!-- JUDUL -->
                <div class="form-group">
                    <label>Judul Lowongan Kerja</label>
                    <input
                        id="detailTitle"
                        type="text"
                        class="form-control"
                        readonly>
                </div>

                <!-- LOKASI -->
                <div class="form-group">

                    <label>Lokasi</label>

                    <select
                        id="detailLocation"
                        class="form-control"
                        disabled>

                        <option value="">-- Pilih Lokasi --</option>

                        <?php

                        $regionQuery = mysqli_query($conn, "
            SELECT *
            FROM regions
            ORDER BY region_name ASC
        ");

                        while ($region = mysqli_fetch_assoc($regionQuery)) :

                        ?>

                            <option value="<?= $region['id']; ?>">

                                <?= htmlspecialchars($region['region_name']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- JENIS -->
                <div class="form-group">

                    <label>Jenis Lowongan</label>

                    <select
                        id="detailType"
                        class="form-control"
                        disabled>

                        <option value="">-- Pilih Jenis Lowongan --</option>

                        <option value="Full Time">
                            Full Time
                        </option>

                        <option value="Part Time">
                            Part Time
                        </option>

                        <option value="Freelance">
                            Freelance
                        </option>

                        <option value="Magang">
                            Magang
                        </option>

                    </select>

                </div>

                <!-- DESKRIPSI -->
                <div class="form-group">

                    <label>Deskripsi Lowongan</label>

                    <textarea
                        id="detailDescription"
                        class="form-control"
                        rows="5"
                        readonly></textarea>

                </div>

                <!-- KUOTA -->
                <div class="form-group">
                    <label>Kuota Pelamar</label>

                    <input
                        id="detailQuota"
                        type="number"
                        class="form-control"
                        readonly>
                </div>

                <!-- TANGGAL -->
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">

                            <label>Tanggal Post</label>

                            <input
                                id="detailPost"
                                type="date"
                                class="form-control"
                                readonly>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">

                            <label>Tanggal Tutup</label>

                            <input
                                id="detailClose"
                                type="date"
                                class="form-control"
                                readonly>

                        </div>
                    </div>

                </div>

                <!-- LINK -->
                <div class="form-group">

                    <label>Link Loker</label>

                    <input
                        id="detailLink"
                        type="text"
                        class="form-control"
                        readonly>

                </div>
                <!-- BUTTON ACTION -->
                <div class="d-flex justify-content-end mt-4">

                    <!-- BATAL -->
                    <button
                        type="button"
                        id="btnCancelDetail"
                        class="btn btn-light mr-2"
                        style="display:none;"
                        onclick="cancelEditDetail()">

                        <span class="material-icons"
                            style="font-size:18px;vertical-align:middle;">
                            close
                        </span>

                        Batal

                    </button>

                    <!-- EDIT / SIMPAN -->
                    <button
                        type="button"
                        id="btnEditDetail"
                        class="btn btn-primary"
                        onclick="toggleEditDetail()">

                        <span class="material-icons"
                            id="btnEditIcon"
                            style="font-size:18px;vertical-align:middle;">
                            edit
                        </span>

                        <span id="btnEditText">
                            Edit
                        </span>

                    </button>

                </div>
            </form>

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
        let currentPage = 1;
        let perPage = 10;

        const searchInput =
            document.getElementById("searchInput");

        const typeFilter =
            document.getElementById("typeFilter");

        const locationFilter =
            document.getElementById("locationFilter");

        const statusFilter =
            document.getElementById("statusFilter");

        const showEntries =
            document.getElementById("showEntries");

        /* =========================================
           GET FILTERED ROWS
        ========================================= */
        function getFilteredRows() {

            const search =
                searchInput.value.toLowerCase();

            const type =
                typeFilter.value.toLowerCase();

            const location =
                locationFilter.value.toLowerCase();

            const status =
                statusFilter.value.toLowerCase();

            const rows =
                document.querySelectorAll(".job-row");

            return Array.from(rows).filter((row) => {

                const title =
                    row.querySelector("strong")
                    .innerText
                    .toLowerCase();

                const lokasi =
                    row.querySelector("small")
                    .innerText
                    .toLowerCase();

                const tipe =
                    row.children[1]
                    .innerText
                    .toLowerCase();

                const statusText =
                    row.children[5]
                    .innerText
                    .trim()
                    .toLowerCase();

                /* SEARCH */
                const matchSearch =
                    title.includes(search);

                /* TYPE */
                const matchType =
                    type.includes("--type") ||
                    type.includes("semua") ||
                    tipe.includes(type);

                /* LOCATION */
                const matchLocation =
                    location.includes("--lokasi") ||
                    location.includes("semua") ||
                    lokasi.includes(location);

                /* STATUS */
                const matchStatus =
                    status.includes("--status") ||
                    status.includes("semua") ||
                    statusText.includes(status);

                return (
                    matchSearch &&
                    matchType &&
                    matchLocation &&
                    matchStatus
                );

            });

        }

        /* =========================================
           RENDER TABLE
        ========================================= */
        function renderTable() {

            const rows =
                getFilteredRows();

            perPage =
                parseInt(showEntries.value);

            const totalData =
                rows.length;

            const totalPages =
                Math.ceil(totalData / perPage);

            /* RESET PAGE */
            if (currentPage > totalPages) {

                currentPage =
                    totalPages || 1;

            }

            /* HIDE ALL */
            document
                .querySelectorAll(".job-row")
                .forEach((row) => {

                    row.style.display = "none";

                });

            /* SHOW DATA */
            const start =
                (currentPage - 1) * perPage;

            const end =
                start + perPage;

            rows.slice(start, end)
                .forEach((row) => {

                    row.style.display = "";

                });

            renderPagination(totalPages, totalData);

        }

        /* =========================================
           PAGINATION
        ========================================= */
        function renderPagination(totalPages, totalData) {

            const pagination =
                document.getElementById("pagination");

            pagination.innerHTML = "";

            /* PREV */
            const prevBtn =
                document.createElement("button");

            prevBtn.className =
                "page-btn icon-btn";

            prevBtn.disabled =
                currentPage === 1;

            prevBtn.innerHTML =
                '<span class="material-icons" style="font-size:18px;">chevron_left</span>';

            prevBtn.onclick = () => {

                if (currentPage > 1) {

                    currentPage--;
                    renderTable();

                }

            };

            pagination.appendChild(prevBtn);

            /* PAGE */
            for (let i = 1; i <= totalPages; i++) {

                if (
                    i === 1 ||
                    i === totalPages ||
                    (i >= currentPage - 2 &&
                        i <= currentPage + 2)
                ) {

                    const btn =
                        document.createElement("button");

                    btn.className =
                        "page-btn";

                    if (i === currentPage) {

                        btn.classList.add("active");

                    }

                    btn.innerText = i;

                    btn.onclick = () => {

                        currentPage = i;
                        renderTable();

                    };

                    pagination.appendChild(btn);

                } else if (
                    i === currentPage - 3 ||
                    i === currentPage + 3
                ) {

                    const dots =
                        document.createElement("span");

                    dots.className =
                        "page-dots";

                    dots.innerText = "...";

                    pagination.appendChild(dots);

                }

            }

            /* NEXT */
            const nextBtn =
                document.createElement("button");

            nextBtn.className =
                "page-btn icon-btn";

            nextBtn.disabled =
                currentPage === totalPages ||
                totalPages === 0;

            nextBtn.innerHTML =
                '<span class="material-icons" style="font-size:18px;">chevron_right</span>';

            nextBtn.onclick = () => {

                if (currentPage < totalPages) {

                    currentPage++;
                    renderTable();

                }

            };

            pagination.appendChild(nextBtn);

            /* INFO */
            const startData =
                totalData === 0 ?
                0 :
                ((currentPage - 1) * perPage) + 1;

            let endData =
                currentPage * perPage;

            if (endData > totalData) {

                endData = totalData;

            }

            document.getElementById("paginationInfo")
                .innerText =
                `Menampilkan ${startData} - ${endData} dari ${totalData} data`;

        }

        /* =========================================
           EVENTS
        ========================================= */
        searchInput.addEventListener(
            "keyup",
            () => {

                currentPage = 1;
                renderTable();

            }
        );

        typeFilter.addEventListener(
            "change",
            () => {

                currentPage = 1;
                renderTable();

            }
        );

        locationFilter.addEventListener(
            "change",
            () => {

                currentPage = 1;
                renderTable();

            }
        );

        statusFilter.addEventListener(
            "change",
            () => {

                currentPage = 1;
                renderTable();

            }
        );

        showEntries.addEventListener(
            "change",
            () => {

                currentPage = 1;
                renderTable();

            }
        );

        /* =========================================
           INIT
        ========================================= */
        renderTable();
    </script>

    <script>
        /* TOGGLE STATUS */
        function toggleStatus(button) {

            const id =
                button.getAttribute("data-id");

            const row =
                button.closest("tr");

            const badge =
                row.children[5]
                .querySelector(".badge");

            const currentStatus =
                badge.innerText.trim();

            let newStatus = "Aktif";

            /* =========================================
               DEFAULT = TIDAK AKTIF
            ========================================= */
            if (
                currentStatus === "Aktif" ||
                currentStatus === "Akan Berakhir"
            ) {

                newStatus = "Tidak Aktif";

            } else {

                newStatus = "Aktif";

            }

            /* =========================================
               VALIDASI
            ========================================= */
            if (
                !confirm(
                    "Yakin ingin mengubah status lowongan ini?"
                )
            ) {
                return;
            }

            /* =========================================
               AJAX UPDATE STATUS
            ========================================= */
            $.ajax({

                url: "logic/toggle_job_status.php",
                type: "POST",

                data: {
                    id: id,
                    status: newStatus
                },

                success: function(response) {

                    if (response == "success") {

                        location.reload();

                    } else {

                        alert(response);

                    }

                }

            });

        }

        /* =========================================
       DELETE JOB VACANCY
    ========================================= */
        function deleteRow(button) {

            const id =
                button.getAttribute("data-id");

            const row =
                button.closest("tr");

            const title =
                row.querySelector("strong")
                .innerText;

            /* =========================================
               VALIDASI
            ========================================= */
            const confirmDelete =
                confirm(
                    `Yakin ingin menghapus lowongan "${title}" ?`
                );

            if (!confirmDelete) {
                return;
            }

            /* =========================================
               AJAX DELETE
            ========================================= */
            $.ajax({

                url: "logic/delete_job_information.php",
                type: "POST",

                data: {
                    id: id
                },

                success: function(response) {

                    if (response == "success") {

                        /* ANIMATION */
                        row.style.transition =
                            "all .3s ease";

                        row.style.opacity = "0";

                        row.style.transform =
                            "translateX(40px)";

                        setTimeout(() => {

                            row.remove();

                            renderTable();

                        }, 300);

                    } else {

                        alert(response);

                    }

                }

            });

        }
    </script>

    <script>
        function showDetailLowongan(button) {

            /* =========================================
               AMBIL DATA DARI BUTTON
            ========================================= */
            const id =
                button.getAttribute("data-id");
            const title =
                button.getAttribute("data-title");
            document.getElementById("detailId")
                .value = id;

            const location =
                button.getAttribute("data-location");

            const type =
                button.getAttribute("data-type");

            const description =
                button.getAttribute("data-description");

            const quota =
                button.getAttribute("data-quota");

            const postDate =
                button.getAttribute("data-post");

            const closeDate =
                button.getAttribute("data-close");

            const link =
                button.getAttribute("data-link");

            /* =========================================
               SET VALUE DETAIL
            ========================================= */
            document.getElementById("detailTitle")
                .value = title;

            document.getElementById("detailLocation")
                .value = location;

            document.getElementById("detailType")
                .value = type;

            document.getElementById("detailDescription")
                .value = description;

            document.getElementById("detailQuota")
                .value = quota;

            document.getElementById("detailPost")
                .value = postDate;

            document.getElementById("detailClose")
                .value = closeDate;

            document.getElementById("detailLink")
                .value = link;

            /* =========================================
               SHOW DETAIL CARD
            ========================================= */
            const detail =
                document.getElementById("detailLowongan");

            detail.style.display = "block";

            /* =========================================
               ANIMATION
            ========================================= */
            detail.style.opacity = "0";
            detail.style.transform = "translateY(-20px)";

            setTimeout(() => {

                detail.style.transition =
                    "all .3s ease";

                detail.style.opacity = "1";

                detail.style.transform =
                    "translateY(0px)";

            }, 10);

            /* =========================================
               SCROLL
            ========================================= */
            detail.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });

        }

        /* =========================================
           CLOSE DETAIL
        ========================================= */
        function closeDetailLowongan() {

            const detail =
                document.getElementById("detailLowongan");

            detail.style.opacity = "0";

            detail.style.transform =
                "translateY(-20px)";

            setTimeout(() => {

                detail.style.display = "none";

            }, 300);

        }
    </script>

    <script>
        let editMode = false;

        /* =========================================
           TOGGLE EDIT
        ========================================= */
        function toggleEditDetail() {

            const inputs =
                document.querySelectorAll(
                    "#detailLowongan input:not(#detailId), #detailLowongan textarea, #detailLowongan select"
                );

            const btnText =
                document.getElementById("btnEditText");

            const btnIcon =
                document.getElementById("btnEditIcon");

            const btnCancel =
                document.getElementById("btnCancelDetail");

            /* =========================================
               EDIT MODE
            ========================================= */
            if (!editMode) {

                inputs.forEach((input) => {

                    input.removeAttribute("readonly");

                });

                document.getElementById("detailLocation")
                    .removeAttribute("disabled");

                document.getElementById("detailType")
                    .removeAttribute("disabled");

                btnText.innerText = "Simpan";

                btnIcon.innerText = "save";

                btnCancel.style.display =
                    "inline-block";

                editMode = true;

            }

            /* =========================================
               SAVE MODE
            ========================================= */
            else {

                if (
                    !confirm(
                        "Yakin ingin menyimpan perubahan?"
                    )
                ) {
                    return;
                }

                /* =========================================
                   AMBIL DATA
                ========================================= */
                const id =
                    document.getElementById("detailId").value;

                const title =
                    document.getElementById("detailTitle").value;

                const location =
                    document.getElementById("detailLocation").value;

                const type =
                    document.getElementById("detailType").value;

                const description =
                    document.getElementById("detailDescription").value;

                const quota =
                    document.getElementById("detailQuota").value;

                const post =
                    document.getElementById("detailPost").value;

                const close =
                    document.getElementById("detailClose").value;

                const link =
                    document.getElementById("detailLink").value;

                /* =========================================
                   AJAX UPDATE
                ========================================= */
                $.ajax({

                    url: "logic/update_job_information.php",
                    type: "POST",

                    data: {

                        id: id,
                        job_title: title,
                        id_region: location,
                        type_vacancy: type,
                        job_desc: description,
                        job_quota: quota,
                        start_info: post,
                        end_info: close,
                        link_info: link

                    },

                    success: function(response) {

                        if (response == "success") {

                            inputs.forEach((input) => {

                                input.setAttribute(
                                    "readonly",
                                    true
                                );

                            });

                            btnText.innerText = "Edit";

                            btnIcon.innerText = "edit";

                            btnCancel.style.display =
                                "none";

                            editMode = false;

                            document.getElementById("detailLocation")
                                .setAttribute("disabled", true);

                            document.getElementById("detailType")
                                .setAttribute("disabled", true);

                            alert(
                                "Perubahan berhasil disimpan"
                            );

                            location.reload();

                        } else {

                            alert(response);

                        }

                    }

                });

            }

        }

        /* =========================================
           CANCEL EDIT
        ========================================= */
        function cancelEditDetail() {

            if (
                !confirm(
                    "Batalkan perubahan?"
                )
            ) {
                return;
            }

            const inputs =
                document.querySelectorAll(
                    "#detailLowongan input:not(#detailId), #detailLowongan textarea"
                );

            inputs.forEach((input) => {

                input.setAttribute(
                    "readonly",
                    true
                );

            });

            document.getElementById(
                "btnEditText"
            ).innerText = "Edit";

            document.getElementById(
                "btnEditIcon"
            ).innerText = "edit";

            document.getElementById(
                "btnCancelDetail"
            ).style.display = "none";

            editMode = false;

            document.getElementById("detailLocation")
                .setAttribute("disabled", true);
            document.getElementById("detailType")
                .setAttribute("disabled", true);

        }
    </script>
</body>

</html>