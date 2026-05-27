<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

/* =========================================
   TAMBAH PARTNER
========================================= */
if (isset($_POST['add_partner'])) {

    $name_partner = mysqli_real_escape_string($conn, $_POST['name_partner']);

    $insert = mysqli_query($conn, "
        INSERT INTO list_partners (
            name_partner,
            created_at
        ) VALUES (
            '$name_partner',
            NOW()
        )
    ");

    if ($insert) {
        $_SESSION['success'] = "Partner berhasil ditambahkan.";
    } else {
        $_SESSION['error'] = "Partner gagal ditambahkan.";
    }

    header("Location: manage_partners");
    exit;
}

/* =========================================
   EDIT PARTNER
========================================= */
if (isset($_POST['edit_partner'])) {

    $id           = intval($_POST['id']);
    $name_partner = mysqli_real_escape_string($conn, $_POST['name_partner']);

    $update = mysqli_query($conn, "
        UPDATE list_partners 
        SET 
            name_partner = '$name_partner'
        WHERE id = '$id'
    ");

    if ($update) {
        $_SESSION['success'] = "Partner berhasil diupdate.";
    } else {
        $_SESSION['error'] = "Partner gagal diupdate.";
    }

    header("Location: manage_partners");
    exit;
}

/* =========================================
   HAPUS PARTNER
========================================= */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete = mysqli_query($conn, "
        DELETE FROM list_partners 
        WHERE id = '$id'
    ");

    if ($delete) {
        $_SESSION['success'] = "Partner berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Partner gagal dihapus.";
    }

    header("Location: manage_partners");
    exit;
}

/* =========================================
   SEARCH + SHOW ENTRIES + PAGINATION
========================================= */

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$show = isset($_GET['show'])
    ? (int) $_GET['show']
    : 10;

$page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $show;

/* =========================================
   TOTAL DATA
========================================= */

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM list_partners
    WHERE name_partner LIKE '%$search%'
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $show);


/* =========================================
   AMBIL DATA PARTNER
========================================= */

$partners = mysqli_query($conn, "
    SELECT 
        id,
        name_partner,
        created_at
    FROM list_partners

    WHERE name_partner LIKE '%$search%'

    ORDER BY id ASC

    LIMIT $offset, $show
");
?>


?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Mitra Kami - Dashboard | Konig Guard Bureau</title>
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
                                        Manage Mitra Kami
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Mitra Kami</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- TITLE -->
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                    <div>
                        <h4 class="mb-1">List Mitra Konig Guard Bureau</h4>
                        <small class="text-muted">
                            Kelola seluruh mitra perusahaan yang bekerja sama dengan Konig Guard Bureau
                        </small>
                    </div>

                    <button
                        type="button"
                        id="btnToggleForm"
                        class="btn btn-warning d-flex align-items-center">

                        <span class="material-icons mr-2" style="font-size:20px;">
                            add
                        </span>

                        Tambah Mitra

                    </button>
                </div>

                <div class="row">

                    <!-- TABLE -->
                    <div class="col-lg-8 mb-4">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <!-- TOP -->
                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <h5 class="mb-0">
                                        Daftar Mitra
                                    </h5>

                                    <form method="GET" class="d-flex align-items-center">

                                        <!-- SHOW ENTRIES -->
                                        <select
                                            name="show"
                                            class="form-control mr-2"
                                            onchange="this.form.submit()"
                                            style="width:90px;">

                                            <option value="10" <?= $show == 10 ? 'selected' : ''; ?>>
                                                10
                                            </option>

                                            <option value="25" <?= $show == 25 ? 'selected' : ''; ?>>
                                                25
                                            </option>

                                            <option value="50" <?= $show == 50 ? 'selected' : ''; ?>>
                                                50
                                            </option>

                                            <option value="100" <?= $show == 100 ? 'selected' : ''; ?>>
                                                100
                                            </option>

                                        </select>

                                        <!-- SEARCH -->
                                        <div class="position-relative">

                                            <span class="material-icons"
                                                style="
                position:absolute;
                top:9px;
                left:10px;
                font-size:18px;
                color:#999;
            ">
                                                search
                                            </span>

                                            <input
                                                type="text"
                                                name="search"
                                                value="<?= htmlspecialchars($search); ?>"
                                                class="form-control"
                                                placeholder="Cari Perusahaan..."
                                                style="padding-left:38px; width:250px;">

                                        </div>

                                    </form>

                                </div>

                                <!-- TABLE -->
                                <div class="table-responsive">

                                    <table class="table table-hover align-middle">

                                        <thead
                                            style="
                                background:#c9a461;
                                color:white;
                            ">
                                            <tr>
                                                <th width="70">NO</th>
                                                <th>NAMA PERUSAHAAN</th>
                                                <th width="130">AKSI</th>
                                            </tr>
                                        </thead>

                                        <tbody id="tableBody">

                                            <?php if (mysqli_num_rows($partners) > 0): ?>

                                                <?php $no = 1; ?>
                                                <?php while ($row = mysqli_fetch_assoc($partners)): ?>

                                                    <tr>

                                                        <td>
                                                            <?= str_pad($no++, 2, '0', STR_PAD_LEFT); ?>
                                                        </td>

                                                        <td>
                                                            <?= htmlspecialchars($row['name_partner']); ?>
                                                        </td>

                                                        <td>

                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-light btnEdit"

                                                                data-id="<?= $row['id']; ?>"
                                                                data-name="<?= htmlspecialchars($row['name_partner']); ?>">

                                                                <span class="material-icons"
                                                                    style="font-size:18px;">
                                                                    edit
                                                                </span>

                                                            </button>

                                                            <a href="?delete=<?= $row['id']; ?>"
                                                                class="btn btn-sm btn-light text-danger"
                                                                onclick="return confirm('Yakin ingin menghapus partner ini?')">

                                                                <span class="material-icons"
                                                                    style="font-size:18px;">
                                                                    delete
                                                                </span>

                                                            </a>

                                                        </td>

                                                    </tr>

                                                <?php endwhile; ?>

                                            <?php else: ?>

                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-4">
                                                        Data partner belum tersedia
                                                    </td>
                                                </tr>

                                            <?php endif; ?>

                                        </tbody>

                                    </table>

                                    <!-- PAGINATION -->
                                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">

                                        <!-- INFO -->
                                        <small class="text-muted mb-2">

                                            Menampilkan
                                            <?= $offset + 1; ?>

                                            sampai

                                            <?= min($offset + $show, $totalData); ?>

                                            dari

                                            <?= $totalData; ?> data

                                        </small>

                                        <!-- PAGINATION -->
                                        <nav>

                                            <ul class="pagination pagination-sm mb-0">

                                                <!-- PREV -->
                                                <?php if ($page > 1): ?>

                                                    <li class="page-item">

                                                        <a class="page-link"
                                                            href="?page=<?= $page - 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                            &laquo;

                                                        </a>

                                                    </li>

                                                <?php endif; ?>

                                                <?php

                                                $start = max(1, $page - 2);
                                                $end   = min($totalPages, $page + 2);

                                                ?>

                                                <!-- FIRST -->
                                                <?php if ($start > 1): ?>

                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="?page=1&show=<?= $show; ?>&search=<?= urlencode($search); ?>">
                                                            1
                                                        </a>
                                                    </li>

                                                    <?php if ($start > 2): ?>

                                                        <li class="page-item disabled">
                                                            <span class="page-link">...</span>
                                                        </li>

                                                    <?php endif; ?>

                                                <?php endif; ?>

                                                <!-- NUMBER -->
                                                <?php for ($i = $start; $i <= $end; $i++): ?>

                                                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">

                                                        <a class="page-link"
                                                            href="?page=<?= $i; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                            <?= $i; ?>

                                                        </a>

                                                    </li>

                                                <?php endfor; ?>

                                                <!-- LAST -->
                                                <?php if ($end < $totalPages): ?>

                                                    <?php if ($end < $totalPages - 1): ?>

                                                        <li class="page-item disabled">
                                                            <span class="page-link">...</span>
                                                        </li>

                                                    <?php endif; ?>

                                                    <li class="page-item">

                                                        <a class="page-link"
                                                            href="?page=<?= $totalPages; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                            <?= $totalPages; ?>

                                                        </a>

                                                    </li>

                                                <?php endif; ?>

                                                <!-- NEXT -->
                                                <?php if ($page < $totalPages): ?>

                                                    <li class="page-item">

                                                        <a class="page-link"
                                                            href="?page=<?= $page + 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                            &raquo;

                                                        </a>

                                                    </li>

                                                <?php endif; ?>

                                            </ul>

                                        </nav>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- FORM -->
                    <div class="col-lg-4 mb-4" id="formMitra">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <h5 class="mb-1" id="formTitle">
                                    Tambah Mitra
                                </h5>

                                <small class="text-muted">
                                    Lengkapi Nama Mitra Berikut
                                </small>

                                <hr>

                                <!-- FORM -->
                                <form method="POST">

                                    <input type="hidden"
                                        name="id"
                                        id="partnerId">

                                    <div class="form-group">
                                        <label>Nama Perusahaan</label>

                                        <input
                                            id="partnerName"
                                            name="name_partner"
                                            type="text"
                                            class="form-control"
                                            placeholder="Masukkan Nama Perusahaan"
                                            required>
                                    </div>

                                    <!-- BUTTON -->
                                    <div class="d-flex justify-content-end mt-4">

                                        <button type="button"
                                            id="btnCloseForm"
                                            class="btn btn-light border mr-2">
                                            Tutup
                                        </button>

                                        <button type="submit"
                                            id="submitButton"
                                            name="add_partner"
                                            class="btn btn-warning">

                                            Simpan

                                        </button>

                                    </div>

                                </form>

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
        <app-settings
            layout-active="fluid"
            :layout-location="{
      'default': 'index.html',
      'fixed': 'fixed-dashboard.html',
      'fluid': 'fluid-dashboard.html',
      'mini': 'mini-dashboard.html'
    }"></app-settings>
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
        const formTitle = document.getElementById('formTitle');
        const formMitra = document.getElementById('formMitra');

        const partnerId = document.getElementById('partnerId');
        const partnerName = document.getElementById('partnerName');

        const submitButton = document.getElementById('submitButton');

        const btnToggleForm = document.getElementById('btnToggleForm');
        const btnCloseForm = document.getElementById('btnCloseForm');

        // =========================
        // DEFAULT
        // =========================

        formMitra.style.display = 'none';

        // =========================
        // MODE TAMBAH
        // =========================

        btnToggleForm.addEventListener('click', function() {

            formMitra.style.display = 'block';

            formTitle.innerText = 'Tambah Mitra';

            partnerId.value = '';
            partnerName.value = '';

            submitButton.innerText = 'Simpan';
            submitButton.name = 'add_partner';

        });

        // =========================
        // MODE EDIT
        // =========================

        document.querySelectorAll('.btnEdit').forEach(button => {

            button.addEventListener('click', function() {

                formMitra.style.display = 'block';

                const id = this.dataset.id;
                const name = this.dataset.name;

                formTitle.innerText = 'Edit Mitra';

                partnerId.value = id;
                partnerName.value = name;

                submitButton.innerText = 'Update';
                submitButton.name = 'edit_partner';

            });

        });

        // =========================
        // TUTUP FORM
        // =========================

        btnCloseForm.addEventListener('click', function() {

            formMitra.style.display = 'none';

        });
    </script>
</body>

</html>