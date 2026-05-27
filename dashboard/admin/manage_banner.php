<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

date_default_timezone_set('Asia/Jakarta');
/* =========================================
   UPLOAD DIRECTORY
========================================= */
$upload_dir = __DIR__ . '/../assets/images/uploads/banner/';

/* =========================================
   TAMBAH / UPDATE BANNER
========================================= */
if (isset($_POST['save_banner'])) {

    $title       = trim($_POST['title'] ?? '');
    $subtitle    = trim($_POST['subtitle'] ?? '');
    $desc        = trim($_POST['desc'] ?? '');
    $link        = trim($_POST['link'] ?? '');
    $mode_update = trim($_POST['mode_update'] ?? '');

    /* =========================================
       VALIDASI
    ========================================= */
    if (
        empty($title) ||
        empty($subtitle)
    ) {

        echo "
            <script>
                alert('Judul dan sub judul wajib diisi');
                window.history.back();
            </script>
        ";

        exit;
    }

    /* =========================================
       DEFAULT
    ========================================= */
    $schedule_datetime = null;
    $status            = 'publish';

    /* =========================================
       UPDATE DENGAN JADWAL
    ========================================= */
    if ($mode_update == 'schedule') {

        $tanggal = $_POST['schedule_date'] ?? '';
        $jam     = $_POST['schedule_time'] ?? '';

        if (
            empty($tanggal) ||
            empty($jam)
        ) {

            echo "
                <script>
                    alert('Tanggal dan jam jadwal wajib diisi');
                    window.history.back();
                </script>
            ";

            exit;
        }

        $schedule_datetime = $tanggal . ' ' . $jam . ':00';

        $status = 'pending';
    }

    /* =========================================
       UPDATE SEKARANG
    ========================================= */
    if ($mode_update == 'now') {

        $schedule_datetime = date('Y-m-d H:i:s');

        $status = 'publish';
    }

    /* =========================================
       UPLOAD IMAGE
    ========================================= */
    $image_name = '';

    if (
        isset($_FILES['image']) &&
        !empty($_FILES['image']['name'])
    ) {

        $tmp_name = $_FILES['image']['tmp_name'];

        $original_name = $_FILES['image']['name'];

        $extension = strtolower(
            pathinfo(
                $original_name,
                PATHINFO_EXTENSION
            )
        );

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowed)) {

            echo "
                <script>
                    alert('Format gambar tidak didukung');
                    window.history.back();
                </script>
            ";

            exit;
        }

        if (!is_dir($upload_dir)) {

            mkdir($upload_dir, 0777, true);
        }

        $image_name =
            'banner_' .
            time() .
            '_' .
            rand(1000, 9999) .
            '.' .
            $extension;

        $upload_path = $upload_dir . $image_name;

        if (!move_uploaded_file($tmp_name, $upload_path)) {

            echo "
                <script>
                    alert('Upload gambar gagal');
                    window.history.back();
                </script>
            ";

            exit;
        }
    }

    /* =========================================
       INSERT DATABASE
    ========================================= */
    $query = "
        INSERT INTO banners (
            title,
            subtitle,
            `desc`,
            link,
            image,
            schedule_datetime,
            status,
            created_at
        )
        VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            NOW()
        )
    ";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {

        die('Query Error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $title,
        $subtitle,
        $desc,
        $link,
        $image_name,
        $schedule_datetime,
        $status
    );

    $execute = mysqli_stmt_execute($stmt);

    if ($execute) {

        echo "
            <script>
                alert('Banner berhasil disimpan');
                window.location='manage_banner';
            </script>
        ";
    } else {

        echo "
            <script>
                alert('Gagal menyimpan banner');
            </script>
        ";

        echo mysqli_error($conn);
    }
}

/* =========================================
   AUTO PUBLISH BANNER
========================================= */
mysqli_query($conn, "
    UPDATE banners
    SET status = 'publish'
    WHERE status = 'pending'
    AND schedule_datetime <= NOW()
");

/* =========================================
   DELETE BANNER
========================================= */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $getBanner = mysqli_query($conn, "
        SELECT image
        FROM banners
        WHERE id = '$id'
    ");

    $banner = mysqli_fetch_assoc($getBanner);

    if (!empty($banner['image'])) {

        $image_path = $upload_dir . $banner['image'];

        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    mysqli_query($conn, "
        DELETE FROM banners
        WHERE id = '$id'
    ");

    echo "
        <script>
            alert('Banner berhasil dihapus');
            window.location='manage_banner';
        </script>
    ";
}

/* =========================================
   GET ALL BANNERS
========================================= */
$getBanners = mysqli_query($conn, "
    SELECT *
    FROM banners
    ORDER BY id DESC
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
    <title>Manage Member - Dashboard | Konig Guard Bureau</title>
    <link href="../assets/images/favicon.png" rel="icon" />

    <!-- Perfect Scrollbar -->
    <link
        type="text/css"
        href="../assets/vendor/perfect-scrollbar.css"
        rel="stylesheet" />

    <!-- SELECT2 -->
    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
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
                                        Manage Banner
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Banner</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="card shadow-sm border-0 my-4">
                    <div class="card-body">

                        <h4 class="font-weight-bold text-primary mb-4">
                            Kelola Banner Pengumuman
                        </h4>

                        <!-- SLIDE -->
                        <div class="card border-0 shadow-sm mb-4">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Judul Banner</label>

                                        <input type="text"
                                            name="title"
                                            class="form-control"
                                            placeholder="Masukkan judul banner" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Sub Judul Banner</label>

                                        <input type="text"
                                            name="subtitle"
                                            class="form-control"
                                            placeholder="Masukkan sub judul banner" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi Banner</label>

                                        <input type="text"
                                            name="desc"
                                            class="form-control"
                                            placeholder="Masukkan deskripsi banner" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Link Banner</label>

                                        <input type="text"
                                            name="link"
                                            class="form-control"
                                            placeholder="Masukkan link banner" required>
                                    </div>

                                    <div class="row">

                                        <!-- LEFT -->
                                        <div class="col-lg-5">

                                            <div class="form-group">
                                                <label>Upload Banner</label>

                                                <!-- INPUT FILE -->
                                                <div class="custom-file">
                                                    <input type="file"
                                                        name="image"
                                                        class="custom-file-input banner-input"
                                                        accept="image/*">

                                                    <label class="custom-file-label">
                                                        Pilih gambar...
                                                    </label>
                                                </div>

                                                <small class="text-muted">
                                                    Ukuran disarankan 1920x1080 px
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label>Mode Update</label>

                                                <select class="form-control"
                                                    id="modeUpdate"
                                                    name="mode_update" required>
                                                    <option value="">--Pilih Mode Update--</option>
                                                    <option value="now">Update Sekarang</option>
                                                    <option value="schedule">Update Dengan Jadwal</option>
                                                </select>
                                            </div>

                                            <!-- WRAPPER JADWAL -->
                                            <div id="scheduleFields" style="display:none;">

                                                <div class="form-group">
                                                    <label>Tanggal Update</label>

                                                    <input type="date"
                                                        name="schedule_date"
                                                        class="form-control"
                                                        id="tanggalUpdate">
                                                </div>

                                                <div class="form-group">
                                                    <label>Jam Update</label>

                                                    <input type="time"
                                                        name="schedule_time"
                                                        class="form-control"
                                                        id="jamUpdate">
                                                </div>

                                            </div>

                                        </div>

                                        <!-- RIGHT -->
                                        <div class="col-lg-7">

                                            <div class="row">

                                                <div class="col-md-6 mb-3">
                                                    <label class="font-weight-bold text-muted">
                                                        Preview Gambar
                                                    </label>

                                                    <div class="border rounded overflow-hidden shadow-sm">
                                                        <img src="../assets/images/default-banner.jpg"
                                                            class="img-fluid w-100 banner-active-preview"
                                                            style="height:180px; object-fit:cover;">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="d-flex flex-wrap align-items-center mt-3">

                                                <!-- TOMBOL UPDATE SEKARANG -->
                                                <button type="submit"
                                                    name="save_banner"
                                                    value="now"
                                                    class="btn btn-success px-4 mr-2 mb-2"
                                                    id="btnUpdateNow"
                                                    style="display:none;">
                                                    Update Sekarang
                                                </button>

                                                <!-- TOMBOL UPDATE JADWAL -->
                                                <button type="submit"
                                                    name="save_banner"
                                                    value="schedule"
                                                    class="btn btn-primary px-4 mr-2 mb-2"
                                                    id="btnUpdateSchedule"
                                                    style="display:none;">
                                                    Update Dengan Jadwal
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <!-- TABLE BANNER -->
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <!-- TOP -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                            <!-- SHOW ENTRIES -->
                            <div class="d-flex align-items-center mb-2">

                                <label class="mb-0 mr-2">
                                    Show
                                </label>

                                <select class="form-control form-control-sm"
                                    id="showEntries"
                                    style="width:80px;">

                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="15">15</option>

                                </select>

                                <label class="mb-0 ml-2">
                                    entries
                                </label>

                            </div>

                            <!-- SEARCH -->
                            <div class="mb-2">

                                <input type="text"
                                    class="form-control"
                                    id="searchBanner"
                                    placeholder="Cari banner...">

                            </div>

                        </div>

                        <!-- TABLE -->
                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="bg-light">

                                    <tr>

                                        <th width="60">No</th>
                                        <th>Judul</th>
                                        <th>Link</th>
                                        <th width="120">Gambar</th>
                                        <th width="150">Tanggal Update</th>
                                        <th width="150">Tanggal Dibuat</th>
                                        <th width="100">Aksi</th>

                                    </tr>

                                </thead>

                                <tbody id="bannerTableBody">

                                    <?php
                                    $no = 1;

                                    while ($banner = mysqli_fetch_assoc($getBanners)) :

                                        $countdown = '-';

                                        if (
                                            $banner['status'] == 'pending' &&
                                            !empty($banner['schedule_datetime'])
                                        ) {

                                            $now = time();

                                            $future = strtotime($banner['schedule_datetime']);

                                            $diff = $future - $now;

                                            if ($diff > 0) {

                                                $days = floor($diff / 86400);

                                                $hours = floor(($diff % 86400) / 3600);

                                                $minutes = floor(($diff % 3600) / 60);

                                                $seconds = floor($diff % 60);

                                                if ($days > 0) {

                                                    $countdown =
                                                        $days . ' Hari ' .
                                                        $hours . ' Jam';
                                                } else {

                                                    $countdown =
                                                        sprintf(
                                                            '%02d:%02d:%02d',
                                                            $hours,
                                                            $minutes,
                                                            $seconds
                                                        );
                                                }
                                            }
                                        }
                                    ?>

                                        <tr class="banner-row">

                                            <td>
                                                <?= $no++; ?>
                                            </td>

                                            <!-- JUDUL -->
                                            <td>

                                                <div class="font-weight-bold">
                                                    <?= htmlspecialchars($banner['title']); ?>
                                                </div>

                                                <small class="text-muted">
                                                    <?= htmlspecialchars($banner['subtitle']); ?>
                                                </small>

                                            </td>

                                            <!-- LINK -->
                                            <td>

                                                <a href="<?= htmlspecialchars($banner['link']); ?>"
                                                    target="_blank">

                                                    Link Banner

                                                </a>

                                            </td>

                                            <!-- IMAGE -->
                                            <td>

                                                <img src="../assets/images/uploads/banner/<?= htmlspecialchars($banner['image']); ?>"
                                                    class="img-fluid rounded"
                                                    style="height:60px; width:100%; object-fit:cover;">

                                            </td>

                                            <!-- TANGGAL UPDATE -->
                                            <td>

                                                <?php if (!empty($banner['schedule_datetime'])) : ?>

                                                    <?= date('d/m/Y H:i', strtotime($banner['schedule_datetime'])); ?>
                                                    WIB

                                                <?php else : ?>

                                                    -

                                                <?php endif; ?>

                                            </td>

                                            <!-- CREATED -->
                                            <td>

                                                <?= date('d/m/Y H:i', strtotime($banner['created_at'])); ?>

                                            </td>

                                            <!-- BUTTON -->
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <button type="button"
                                                        class="btn btn-info btn-sm btn-view-detail mr-2">
                                                        View
                                                    </button>

                                                    <a href="?delete=<?= $banner['id']; ?>"
                                                        onclick="return confirm('Hapus banner ini?')"
                                                        class="btn btn-danger btn-sm">
                                                        Hapus
                                                    </a>

                                                </div>
                                            </td>

                                        </tr>

                                        <!-- DETAIL ROW -->
                                        <tr class="detail-row bg-light" style="display:none;">

                                            <td colspan="9">

                                                <div class="p-3">

                                                    <div class="row">

                                                        <!-- IMAGE -->
                                                        <div class="col-lg-4 mb-3">

                                                            <img src="../assets/images/uploads/banner/<?= htmlspecialchars($banner['image']); ?>"
                                                                class="img-fluid rounded shadow-sm w-100"
                                                                style="height:220px; object-fit:cover;">

                                                        </div>

                                                        <!-- DETAIL -->
                                                        <div class="col-lg-8">

                                                            <div class="row">

                                                                <div class="col-md-6 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Judul Banner
                                                                    </small>

                                                                    <strong>
                                                                        <?= htmlspecialchars($banner['title']); ?>
                                                                    </strong>

                                                                </div>

                                                                <div class="col-md-6 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Sub Judul
                                                                    </small>

                                                                    <strong>
                                                                        <?= htmlspecialchars($banner['subtitle']); ?>
                                                                    </strong>

                                                                </div>

                                                                <div class="col-md-6 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Link Banner
                                                                    </small>

                                                                    <a href="<?= htmlspecialchars($banner['link']); ?>"
                                                                        target="_blank">

                                                                        <?= htmlspecialchars($banner['link']); ?>

                                                                    </a>

                                                                </div>

                                                                <div class="col-md-3 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Status
                                                                    </small>

                                                                    <?php if ($banner['status'] == 'pending') : ?>

                                                                        <span class="badge badge-warning">
                                                                            Pending
                                                                        </span>

                                                                    <?php else : ?>

                                                                        <span class="badge badge-success">
                                                                            Publish
                                                                        </span>

                                                                    <?php endif; ?>

                                                                </div>

                                                                <div class="col-md-3 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Countdown
                                                                    </small>

                                                                    <strong class="text-danger live-countdown"
                                                                        data-datetime="<?= $banner['schedule_datetime']; ?>"
                                                                        data-status="<?= $banner['status']; ?>">

                                                                        <?= $countdown; ?>

                                                                    </strong>

                                                                </div>

                                                                <div class="col-md-6 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Tanggal Update
                                                                    </small>

                                                                    <strong>

                                                                        <?php if (!empty($banner['schedule_datetime'])) : ?>

                                                                            <?= date('d/m/Y H:i', strtotime($banner['schedule_datetime'])); ?> WIB

                                                                        <?php else : ?>

                                                                            -

                                                                        <?php endif; ?>

                                                                    </strong>

                                                                </div>

                                                                <div class="col-md-6 mb-3">

                                                                    <small class="text-muted d-block">
                                                                        Tanggal Dibuat
                                                                    </small>

                                                                    <strong>
                                                                        <?= date('d/m/Y H:i', strtotime($banner['created_at'])); ?>
                                                                    </strong>

                                                                </div>

                                                                <div class="col-12">

                                                                    <small class="text-muted d-block mb-1">
                                                                        Deskripsi Banner
                                                                    </small>

                                                                    <div class="border rounded p-3 bg-white">

                                                                        <?= nl2br(htmlspecialchars($banner['desc'])); ?>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                </tbody>

                            </table>

                        </div>

                        <!-- PAGINATION -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4">

                            <div id="tableInfo" class="text-muted mb-2">
                                Menampilkan 1 sampai 10 data
                            </div>

                            <ul class="pagination pagination-sm mb-0" id="pagination">
                            </ul>

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
    <!-- SELECT2 -->
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

    <!-- Global Settings -->
    <script src="../assets/js/settings.js"></script>

    <script>
        $(document).ready(function() {

            $('#modeUpdate').on('change', function() {

                let mode = $(this).val();

                // SEMBUNYIKAN SEMUA DULU
                $('#scheduleFields').hide();
                $('#btnUpdateNow').hide();
                $('#btnUpdateSchedule').hide();

                // UPDATE SEKARANG
                if (mode === 'now') {

                    $('#btnUpdateNow').show();

                }

                // UPDATE DENGAN JADWAL
                else if (mode === 'schedule') {

                    $('#scheduleFields').slideDown(200);
                    $('#btnUpdateSchedule').show();

                }

            });

        });
    </script>

    <script>
        $(document).ready(function() {

            /* =========================================
               VIEW DETAIL ROW
            ========================================= */

            $('.btn-view-detail').on('click', function() {

                let detailRow = $(this)
                    .closest('tr')
                    .next('.detail-row');

                $('.detail-row')
                    .not(detailRow)
                    .hide();

                detailRow.toggle();

            });


            /* =========================================
               SHOW ENTRIES
            ========================================= */

            let currentPage = 1;

            function showTableRows() {

                let limit = parseInt($('#showEntries').val());

                let rows = $('.banner-row');

                let detailRows = $('.detail-row');

                rows.hide();
                detailRows.hide();

                let start = (currentPage - 1) * limit;

                let end = start + limit;

                rows.slice(start, end).show();

                renderPagination();

            }

            /* =========================================
               PAGINATION
            ========================================= */

            function renderPagination() {

                let limit = parseInt($('#showEntries').val());

                let totalRows = $('.banner-row').length;

                let totalPages = Math.ceil(totalRows / limit);

                let html = '';

                html += `
                <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                    <a href="javascript:void(0)"
                        class="page-link page-btn"
                        data-page="${currentPage - 1}">

                        Prev

                    </a>
                </li>
            `;

                for (let i = 1; i <= totalPages; i++) {

                    html += `
                    <li class="page-item ${currentPage == i ? 'active' : ''}">

                        <a href="javascript:void(0)"
                            class="page-link page-btn"
                            data-page="${i}">

                            ${i}

                        </a>

                    </li>
                `;

                }

                html += `
                <li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                    <a href="javascript:void(0)"
                        class="page-link page-btn"
                        data-page="${currentPage + 1}">

                        Next

                    </a>
                </li>
            `;

                $('#pagination').html(html);

            }

            /* =========================================
               CLICK PAGINATION
            ========================================= */

            $(document).on('click', '.page-btn', function() {

                let page = $(this).data('page');

                let limit = parseInt($('#showEntries').val());

                let totalRows = $('.banner-row').length;

                let totalPages = Math.ceil(totalRows / limit);

                if (page < 1 || page > totalPages) {
                    return;
                }

                currentPage = page;

                showTableRows();

            });

            /* =========================================
               SHOW ENTRIES CHANGE
            ========================================= */

            $('#showEntries').on('change', function() {

                currentPage = 1;

                showTableRows();

            });

            /* =========================================
               SEARCH
            ========================================= */

            $('#searchBanner').on('keyup', function() {

                let value = $(this).val().toLowerCase();

                $('.banner-row').each(function() {

                    let row = $(this);

                    let detail = row.next('.detail-row');

                    if (row.text().toLowerCase().indexOf(value) > -1) {

                        row.show();

                    } else {

                        row.hide();
                        detail.hide();

                    }

                });

            });

            showTableRows();

        });
    </script>

    <script>
        $(document).ready(function() {

            /* =========================================
               PREVIEW IMAGE + FILE NAME
            ========================================= */

            $('.banner-input').on('change', function(e) {

                let file = e.target.files[0];

                if (!file) {
                    return;
                }

                /* =========================================
                   SHOW FILE NAME
                ========================================= */
                $(this)
                    .next('.custom-file-label')
                    .html(file.name);

                /* =========================================
                   IMAGE PREVIEW
                ========================================= */
                let reader = new FileReader();

                reader.onload = function(event) {

                    $('.banner-active-preview')
                        .attr('src', event.target.result);

                };

                reader.readAsDataURL(file);

            });

        });
    </script>

    <script>
        $(document).ready(function() {

            /* =========================================
               LIVE COUNTDOWN
            ========================================= */

            function updateCountdown() {

                $('.live-countdown').each(function() {

                    let element = $(this);

                    let status = element.data('status');

                    let datetime = element.data('datetime');

                    /* =========================================
                       JIKA PUBLISH
                    ========================================= */
                    if (status !== 'pending') {

                        element
                            .removeClass('text-danger')
                            .addClass('text-success');

                        element.html('Sudah Publish');

                        return;
                    }

                    /* =========================================
                       JIKA TIDAK ADA DATETIME
                    ========================================= */
                    if (!datetime || datetime === '') {

                        element.html('-');

                        return;
                    }

                    /* =========================================
                       FORMAT DATE
                    ========================================= */
                    let targetDate = new Date(
                        datetime.replace(' ', 'T')
                    );

                    let now = new Date();

                    let diff = targetDate - now;

                    /* =========================================
                       SUDAH SELESAI
                    ========================================= */
                    if (diff <= 0) {

                        element
                            .removeClass('text-danger')
                            .addClass('text-success');

                        element.html('Sudah Publish');

                        return;
                    }

                    /* =========================================
                       HITUNG
                    ========================================= */
                    let days = Math.floor(diff / (1000 * 60 * 60 * 24));

                    let hours = Math.floor(
                        (diff % (1000 * 60 * 60 * 24)) /
                        (1000 * 60 * 60)
                    );

                    let minutes = Math.floor(
                        (diff % (1000 * 60 * 60)) /
                        (1000 * 60)
                    );

                    let seconds = Math.floor(
                        (diff % (1000 * 60)) / 1000
                    );

                    /* =========================================
                       FORMAT TAMPILAN
                    ========================================= */
                    let result = '';

                    if (days > 0) {

                        result =
                            days + ' Hari ' +
                            hours + ' Jam';

                    } else {

                        result =
                            String(hours).padStart(2, '0') + ':' +
                            String(minutes).padStart(2, '0') + ':' +
                            String(seconds).padStart(2, '0');

                    }

                    element.html(result);

                });

            }

            /* =========================================
               JALANKAN
            ========================================= */

            updateCountdown();

            setInterval(updateCountdown, 1000);

        });

        const heroSlider = document.querySelector(".hero-slider");

        let navTimeout;

        // tampilkan tombol
        function showSliderNav() {

            heroSlider.classList.add("show-nav");

            clearTimeout(navTimeout);

            navTimeout = setTimeout(() => {
                heroSlider.classList.remove("show-nav");
            }, 2000); // hilang setelah 2 detik diam
        }

        // mouse gerak
        heroSlider.addEventListener("mousemove", showSliderNav);

        // touch screen
        heroSlider.addEventListener("touchstart", showSliderNav);

        // pertama kali load sebentar muncul lalu hilang
        showSliderNav();
    </script>

</body>

</html>