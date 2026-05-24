<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/* =========================================
   FILTER
========================================= */

$search         = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$subFilter      = $_GET['subcategory'] ?? '';

/* =========================================
   PAGINATION
========================================= */

$show = isset($_GET['show']) ? (int)$_GET['show'] : 10;

if ($show <= 0) {
    $show = 10;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page <= 0) {
    $page = 1;
}

$start = ($page - 1) * $show;

/* =========================================
   QUERY TOTAL
========================================= */

$where = " WHERE 1=1 ";

if (!empty($search)) {
    $searchEsc = mysqli_real_escape_string($conn, $search);

    $where .= " AND (
        p.title_post LIKE '%$searchEsc%'
        OR pc.name_category LIKE '%$searchEsc%'
        OR ps.name_subcategory LIKE '%$searchEsc%'
    )";
}

if (!empty($categoryFilter)) {

    $categoryFilter = (int)$categoryFilter;

    $where .= " AND p.id_post_category = '$categoryFilter'";
}

if (!empty($subFilter)) {

    $subFilter = (int)$subFilter;

    $where .= " AND p.id_post_subcategory = '$subFilter'";
}

/* =========================================
   TOTAL DATA
========================================= */

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM post p
    LEFT JOIN post_category pc
        ON p.id_post_category = pc.id
    LEFT JOIN post_subcategory ps
        ON p.id_post_subcategory = ps.id
    $where
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $show);

/* =========================================
   QUERY DATA POST
========================================= */

$queryPost = mysqli_query($conn, "
    SELECT
    p.id,
    p.title_post,
    p.id_post_category,
    p.id_post_subcategory,
    p.post_desc,
    p.post_img,
    p.created_at,
    p.update_at,

    pc.name_category,

    ps.name_subcategory,

    COUNT(pcmt.id) as total_comment

FROM post p

LEFT JOIN post_category pc
    ON p.id_post_category = pc.id

LEFT JOIN post_subcategory ps
    ON p.id_post_subcategory = ps.id

LEFT JOIN post_commenters pcmt
    ON p.id = pcmt.id_post

    $where

GROUP BY p.id

ORDER BY p.id DESC
");

/* =========================================
   STATISTIK
========================================= */

$totalPost = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM post
    ")
)['total'];

$totalCategory = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM post_category
    ")
)['total'];

$totalSubCategory = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM post_subcategory
    ")
)['total'];

/* =========================================
   DATA CATEGORY
========================================= */

$queryCategory = mysqli_query($conn, "
    SELECT *
    FROM post_category
    ORDER BY name_category ASC
");

/* =========================================
   DATA SUB CATEGORY
========================================= */

$querySubCategory = mysqli_query($conn, "
    SELECT *
    FROM post_subcategory
    ORDER BY name_subcategory ASC
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
    <title>Daftar Postingan - Dashboard | Konig Guard Bureau</title>


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
                                        Daftar Postingan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Daftar Postingan</h1>
                        </div>
                        <a href="add_post.php"
                            class="btn btn-primary ml-1">Tambah Postingan?</a>
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
                                    add_box
                                </span>
                            </div>

                            <div>
                                <h3 class="mb-1">Daftar Postingan</h3>
                                <small class="text-muted">
                                    Kelola semua postingan.
                                </small>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- STATISTIC -->
                <div class="row mb-4">

                    <div class="col-md-4">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#eef2ff;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:var(--success);font-size:30px;">
                                        cast_connected
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalPost; ?></h3>
                                    <small class="text-muted">Total Postingan</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#eafaf1;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:var(--primary);font-size:30px;">
                                        layers
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalCategory; ?></h3>
                                    <small class="text-muted">Kategori</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stats-card" style="border-radius:12px;">
                            <div class="card-body d-flex align-items-center">

                                <div style="width:60px;height:60px;border-radius:12px;
                    background:#f3f4f6;display:flex;align-items:center;
                    justify-content:center;margin-right:15px;">
                                    <span class="material-icons" style="color:var(--primary);font-size:30px;">
                                        layers
                                    </span>
                                </div>

                                <div>
                                    <h3 class="mb-0"><?= $totalSubCategory; ?></h3>
                                    <small class="text-muted">Total Sub Kategori</small>
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

                                    <select
                                        id="showEntries"
                                        class="form-control"
                                        style="width:80px;">
                                        <option value="10" <?= ($show == 10) ? 'selected' : ''; ?>>
                                            10
                                        </option>

                                        <option value="25" <?= ($show == 25) ? 'selected' : ''; ?>>
                                            25
                                        </option>

                                        <option value="50" <?= ($show == 50) ? 'selected' : ''; ?>>
                                            50
                                        </option>

                                        <option value="100" <?= ($show == 100) ? 'selected' : ''; ?>>
                                            100
                                        </option>
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

                                    <input
                                        id="searchInput"
                                        type="text"
                                        class="form-control"
                                        value="<?= htmlspecialchars($search); ?>">
                                </div>

                            </div>

                            <!-- RIGHT FILTER -->
                            <div class="d-flex align-items-center flex-wrap"
                                style="gap:15px;">

                                <select
                                    id="categoryFilter"
                                    class="form-control"
                                    style="width:190px;"
                                    onchange="filterData()">
                                    <option value="">--Pilih Kategori--</option>

                                    <?php while ($cat = mysqli_fetch_assoc($queryCategory)) : ?>

                                        <option
                                            value="<?= $cat['id']; ?>"
                                            <?= ($categoryFilter == $cat['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($cat['name_category']); ?>
                                        </option>

                                    <?php endwhile; ?>
                                </select>

                                <select
                                    id="subCategoryFilter"
                                    class="form-control"
                                    style="width:190px;"
                                    onchange="filterData()">
                                    <option value="">--Pilih Sub Kategori--</option>

                                    <?php while ($sub = mysqli_fetch_assoc($querySubCategory)) : ?>

                                        <option
                                            value="<?= $sub['id']; ?>"
                                            <?= ($subFilter == $sub['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($sub['name_subcategory']); ?>
                                        </option>

                                    <?php endwhile; ?>
                                </select>

                            </div>

                        </div>

                        <!-- TABLE CONTAIN -->
                        <div class="table-responsive">

                            <table class="table table-hover" style="white-space:normal;">

                                <thead style="background:#f8f9fc;">
                                    <tr>
                                        <th>Judul Postingan</th>
                                        <th>Kategori</th>
                                        <th>Sub Kategori</th>
                                        <th>Gambar</th>
                                        <th>Deskripsi</th>
                                        <th>Komentar</th>
                                        <th style="white-space:nowrap;">Terakhir Diperbarui</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="jobTable">

                                    <?php if (mysqli_num_rows($queryPost) > 0) : ?>

                                        <?php while ($row = mysqli_fetch_assoc($queryPost)) : ?>

                                            <tr class="post-row">

                                                <!-- JUDUL -->
                                                <td style="min-width:220px; white-space:nowrap;">
                                                    <strong>
                                                        <?= htmlspecialchars($row['title_post']); ?>
                                                    </strong>

                                                    <br>

                                                    <small class="text-muted" style="white-space:nowrap;">
                                                        <?= date('d M Y H:i', strtotime($row['created_at'])); ?>
                                                    </small>
                                                </td>

                                                <!-- KATEGORI -->
                                                <td style="white-space:nowrap;">
                                                    <?= htmlspecialchars($row['name_category']); ?>
                                                </td>

                                                <!-- SUB KATEGORI -->
                                                <td style="white-space:nowrap;">
                                                    <?= htmlspecialchars($row['name_subcategory']); ?>
                                                </td>

                                                <!-- IMAGE -->
                                                <td>
                                                    <?php if (!empty($row['post_img'])) : ?>

                                                        <img
                                                            src="../assets/images/uploads/posts/<?= htmlspecialchars($row['post_img']); ?>"
                                                            alt="Post Image"
                                                            style="
                            width:80px;
                            height:60px;
                            object-fit:cover;
                            border-radius:10px;
                            border:1px solid #e5e7eb;
                        ">

                                                    <?php else : ?>

                                                        <div
                                                            style="
                            width:80px;
                            height:60px;
                            background:#f3f4f6;
                            border-radius:10px;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                        ">
                                                            <span class="material-icons text-muted">
                                                                image
                                                            </span>
                                                        </div>

                                                    <?php endif; ?>
                                                </td>

                                                <!-- DESC -->
                                                <td style="min-width:260px; max-width:260px;">

                                                    <div
                                                        style="
            display:-webkit-box;
            -webkit-line-clamp:2;
            -webkit-box-orient:vertical;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:normal;
            line-height:1.5em;
            max-height:3em;
        ">

                                                        <?= strip_tags($row['post_desc']); ?>

                                                    </div>

                                                </td>

                                                <!-- KOMENTAR -->
                                                <td>

                                                    <span class="badge badge-primary px-3 py-2">

                                                        <?= $row['total_comment']; ?>

                                                        Komentar

                                                    </span>

                                                </td>

                                                <!-- UPDATED -->
                                                <td>
                                                    <small class="text-muted" style="white-space:nowrap;">
                                                        <?= date('d M Y H:i', strtotime($row['update_at'])); ?>
                                                    </small>
                                                </td>

                                                <!-- AKSI -->
                                                <td>

                                                    <div class="d-flex justify-content-center">

                                                        <a
                                                            href="edit_post.php?id=<?= $row['id']; ?>"
                                                            class="mr-2">
                                                            <button
                                                                class="btn btn-outline-primary btn-sm"
                                                                title="Edit">
                                                                <span class="material-icons"
                                                                    style="font-size:16px;">
                                                                    edit
                                                                </span>
                                                            </button>
                                                        </a>

                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Hapus"
                                                            onclick="openDeleteModal(
        <?= $row['id']; ?>,
        '<?= htmlspecialchars(addslashes($row['title_post'])); ?>'
    )">
                                                            <span class="material-icons"
                                                                style="font-size:16px;">
                                                                delete
                                                            </span>
                                                        </button>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <tr>
                                            <td colspan="7" class="text-center py-5">

                                                <div class="d-flex flex-column align-items-center">

                                                    <span
                                                        class="material-icons mb-2"
                                                        style="
                        font-size:60px;
                        color:#d1d5db;
                    ">
                                                        article
                                                    </span>

                                                    <h5 class="mb-1">
                                                        Data postingan kosong
                                                    </h5>

                                                    <small class="text-muted">
                                                        Belum ada data postingan tersedia.
                                                    </small>

                                                </div>

                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                        <!-- PAGINATION -->
                        <div id="pagination"
                            class="d-flex align-items-center flex-wrap"
                            style="gap:6px; max-width:100%; overflow-x:auto; padding-bottom:4px;">

                            <?php if ($totalPages > 1) : ?>

                                <!-- PREV -->
                                <a
                                    href="?page=<?= max(1, $page - 1); ?>&search=<?= urlencode($search); ?>&category=<?= $categoryFilter; ?>&subcategory=<?= $subFilter; ?>&show=<?= $show; ?>"
                                    class="btn btn-sm btn-light">
                                    «
                                </a>

                                <?php

                                $visiblePages = [];

                                if ($totalPages <= 10) {

                                    for ($i = 1; $i <= $totalPages; $i++) {
                                        $visiblePages[] = $i;
                                    }
                                } else {

                                    $visiblePages = [1, 2, 3, 4, 5];

                                    if ($page > 6 && $page < $totalPages - 4) {

                                        $visiblePages[] = '...';

                                        for ($i = $page - 1; $i <= $page + 1; $i++) {
                                            $visiblePages[] = $i;
                                        }
                                    }

                                    $visiblePages[] = '...';

                                    for ($i = $totalPages - 3; $i <= $totalPages; $i++) {
                                        $visiblePages[] = $i;
                                    }

                                    $visiblePages = array_unique($visiblePages);
                                }

                                ?>

                                <?php foreach ($visiblePages as $p) : ?>

                                    <?php if ($p === '...') : ?>

                                        <span
                                            class="btn btn-sm btn-light disabled"
                                            style="pointer-events:none;">
                                            ...
                                        </span>

                                    <?php else : ?>

                                        <a
                                            href="?page=<?= $p; ?>&search=<?= urlencode($search); ?>&category=<?= $categoryFilter; ?>&subcategory=<?= $subFilter; ?>&show=<?= $show; ?>"
                                            class="btn btn-sm <?= ($p == $page) ? 'btn-primary' : 'btn-light'; ?>">
                                            <?= $p; ?>
                                        </a>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                                <!-- NEXT -->
                                <a
                                    href="?page=<?= min($totalPages, $page + 1); ?>&search=<?= urlencode($search); ?>&category=<?= $categoryFilter; ?>&subcategory=<?= $subFilter; ?>&show=<?= $show; ?>"
                                    class="btn btn-sm btn-light">
                                    »
                                </a>

                            <?php endif; ?>

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
    MODAL KONFIRMASI HAPUS PAKAI INI
========================= -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0"
                style="border-radius:20px;overflow:hidden;">

                <div class="modal-body text-center p-5">

                    <!-- ICON -->
                    <div class="mx-auto mb-4"
                        style="
                        width:90px;
                        height:90px;
                        border-radius:50%;
                        background:#fff1f2;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">
                        <span class="material-icons"
                            style="
                            font-size:50px;
                            color:#dc2626;
                        ">
                            delete
                        </span>
                    </div>

                    <h3 class="mb-2">
                        Hapus Postingan?
                    </h3>

                    <p class="text-muted mb-4">
                        Apakah postingan
                        <strong id="deletePostTitle"></strong>
                        ingin dihapus?
                    </p>

                    <div class="d-flex justify-content-center"
                        style="gap:12px;">

                        <button type="button"
                            class="btn btn-light"
                            data-dismiss="modal"
                            style="
                            height:45px;
                            border-radius:10px;
                            min-width:110px;
                            font-weight:600;
                        ">
                            Batal
                        </button>

                        <button type="button"
                            class="btn btn-danger"
                            id="btnConfirmDelete"
                            style="
                            height:45px;
                            border-radius:10px;
                            min-width:110px;
                            font-weight:600;
                        ">
                            Ya, Hapus
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- =========================
    MODAL SUKSES HAPUS
========================= -->
    <div class="modal fade" id="modalDeleteSuccess" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0"
                style="border-radius:20px;overflow:hidden;">

                <div class="modal-body text-center p-5">

                    <div class="mx-auto mb-4"
                        style="
                        width:90px;
                        height:90px;
                        border-radius:50%;
                        background:#fff1f2;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">
                        <span class="material-icons"
                            style="
                            font-size:50px;
                            color:#dc2626;
                        ">
                            delete_forever
                        </span>
                    </div>

                    <h3 class="mb-2">
                        Postingan Berhasil Dihapus
                    </h3>

                    <p class="text-muted mb-4">
                        Postingan
                        <strong id="deletedPostTitle"></strong>
                        telah dihapus.
                    </p>

                    <button type="button"
                        class="btn btn-danger px-4"
                        data-dismiss="modal"
                        style="
                        border-radius:10px;
                        height:45px;
                        font-weight:600;
                    ">
                        Oke
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
        function filterData() {

            const search =
                document.getElementById('searchInput').value;

            const category =
                document.getElementById('categoryFilter').value;

            const subcategory =
                document.getElementById('subCategoryFilter').value;

            const show =
                document.getElementById('showEntries').value;

            let url =
                'manage_post.php?';

            url += 'search=' + encodeURIComponent(search);
            url += '&category=' + category;
            url += '&subcategory=' + subcategory;
            url += '&show=' + show;

            window.location.href = url;
        }

        /* SEARCH ENTER */
        document.getElementById('searchInput')
            .addEventListener('keyup', function(e) {

                if (e.key === 'Enter') {
                    filterData();
                }

            });

        /* SHOW ENTRIES */
        document.getElementById('showEntries')
            .addEventListener('change', function() {

                filterData();

            });
    </script>

    <script>
        let deleteId = null;

        function openDeleteModal(id, title) {

            deleteId = id;

            document.getElementById('deletePostTitle')
                .innerText = '"' + title + '"';

            $('#confirmDeleteModal').modal('show');
        }

        document.getElementById('btnConfirmDelete')
            .addEventListener('click', function() {

                if (deleteId) {

                    window.location.href =
                        'logic/delete_post.php?id=' + deleteId;
                }

            });
    </script>
</body>

</html>