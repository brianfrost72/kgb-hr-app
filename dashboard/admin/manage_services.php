<?php
session_start();
require_once "../koneksi.php";


/*
|--------------------------------------------------------------------------
| TAMBAH DATA
|--------------------------------------------------------------------------
*/

if (isset($_POST['tambah_service'])) {

    $service_category = trim($_POST['service_category']);
    $service_name = trim($_POST['service_name']);
    $service_desc = trim($_POST['service_desc']);

    if (
        empty($service_category) ||
        empty($service_name) ||
        empty($service_desc) ||
        empty($_FILES['service_picture']['name'])
    ) {

        $_SESSION['error'] = "Semua field wajib diisi!";

        header("Location: manage_services.php");
        exit;
    } else {

        // VALIDASI DUPLICATE
        $cek = mysqli_query($conn, "
            SELECT id 
            FROM services 
            WHERE LOWER(service_name) = LOWER('$service_name')
        ");

        if (mysqli_num_rows($cek) > 0) {

            $_SESSION['error'] = "Nama layanan sudah ada!";

            header("Location: manage_services.php");
            exit;
        } else {

            $folder = "../assets/images/services/";

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $gambar = time() . "_" . $_FILES['service_picture']['name'];

            $tmp = $_FILES['service_picture']['tmp_name'];

            move_uploaded_file($tmp, $folder . $gambar);

            mysqli_query($conn, "
                INSERT INTO services
                (
                    service_category,
                    service_name,
                    service_picture,
                    service_desc,
                    created_at,
                    update_at
                )
                VALUES
                (
                    '$service_category',
                    '$service_name',
                    '$gambar',
                    '$service_desc',
                    NOW(),
                    NOW()
                )
            ");

            $_SESSION['success'] = "Data berhasil ditambahkan!";

            header("Location: manage_services.php");
            exit;
        }
    }
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_service'])) {

    $id = (int) $_POST['id'];

    $service_category = trim($_POST['service_category']);

    $service_name = trim($_POST['service_name']);

    $service_desc = trim($_POST['service_desc']);

    if (
        empty($service_category) ||
        empty($service_name) ||
        empty($service_desc)
    ) {

        $_SESSION['error'] = "Semua field wajib diisi!";

        header("Location: manage_services.php");
        exit;
    } else {

        // VALIDASI DUPLICATE
        $cek = mysqli_query($conn, "
            SELECT id 
            FROM services 
            WHERE LOWER(service_name)=LOWER('$service_name')
            AND id != '$id'
        ");

        if (mysqli_num_rows($cek) > 0) {

            $_SESSION['error'] = "Nama layanan sudah digunakan!";

            header("Location: manage_services.php");
            exit;
        } else {

            $qOld = mysqli_query($conn, "
                SELECT service_picture
                FROM services
                WHERE id='$id'
            ");

            $old = mysqli_fetch_assoc($qOld);

            $gambar = $old['service_picture'];

            // JIKA ADA GAMBAR BARU
            if (!empty($_FILES['service_picture']['name'])) {

                $folder = "../assets/images/services/";

                if (
                    file_exists($folder . $gambar)
                ) {
                    unlink($folder . $gambar);
                }

                $gambar =
                    time() . "_" .
                    $_FILES['service_picture']['name'];

                move_uploaded_file(
                    $_FILES['service_picture']['tmp_name'],
                    $folder . $gambar
                );
            }

            mysqli_query($conn, "
                UPDATE services
                SET
                    service_category = '$service_category',
                    service_name = '$service_name',
                    service_picture = '$gambar',
                    service_desc = '$service_desc',
                    update_at = NOW()
                WHERE id='$id'
            ");

            $_SESSION['success'] = "Data berhasil diupdate!";

            header("Location: manage_services.php");
            exit;
        }
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS SINGLE
|--------------------------------------------------------------------------
*/

if (isset($_POST['hapus_service'])) {

    $id = (int) $_POST['hapus_id'];

    $q = mysqli_query($conn, "
        SELECT service_picture
        FROM services
        WHERE id='$id'
    ");

    $d = mysqli_fetch_assoc($q);

    if (
        file_exists(
            "../assets/images/services/" .
                $d['service_picture']
        )
    ) {

        unlink(
            "../assets/images/services/" .
                $d['service_picture']
        );
    }

    mysqli_query($conn, "
        DELETE FROM services
        WHERE id='$id'
    ");

    $_SESSION['success'] = "Data berhasil dihapus!";

    header("Location: manage_services.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS TERPILIH
|--------------------------------------------------------------------------
*/

if (isset($_POST['hapus_terpilih'])) {

    if (!empty($_POST['selected'])) {

        foreach ($_POST['selected'] as $id) {

            $id = (int) $id;

            $q = mysqli_query($conn, "
                SELECT service_picture
                FROM services
                WHERE id='$id'
            ");

            $d = mysqli_fetch_assoc($q);

            if (
                file_exists(
                    "../assets/images/services/" .
                        $d['service_picture']
                )
            ) {

                unlink(
                    "../assets/images/services/" .
                        $d['service_picture']
                );
            }

            mysqli_query($conn, "
                DELETE FROM services
                WHERE id='$id'
            ");
        }

        $_SESSION['success'] = "Data terpilih berhasil dihapus!";

        header("Location: manage_services.php");
        exit;
    }
}

$dataServices = mysqli_query($conn, "
    SELECT *
    FROM services
    ORDER BY id ASC
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
    <title>Tambah Layanan - Dashboard | Konig Guard Bureau</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex" />

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
                                        Tambah Layanan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Tambah Layanan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">
                    <!-- NOTIFIKASI DISINI -->
                    <?php if (isset($_SESSION['success'])) : ?>

                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 notif-auto">

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

                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 notif-auto">

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
                            <h4 class="card-title">Tambah Layanan</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
                        </div>

                        <div class="card-body">
                            <!-- FILTER -->
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    Show
                                    <select id="showEntries" class="form-control d-inline w-auto">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>20</option>
                                    </select>
                                    entries
                                </div>

                                <input type="text" id="searchInput" class="form-control w-25" placeholder="Search...">
                            </div>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>No</th>
                                            <th>Kategori Layanan</th>
                                            <th>Nama Layanan</th>
                                            <th>Gambar</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $no = 1;

                                        while ($row = mysqli_fetch_assoc($dataServices)) :
                                        ?>

                                            <tr>

                                                <td>
                                                    <input type="checkbox"
                                                        class="rowCheck"
                                                        name="selected[]"
                                                        value="<?= $row['id'] ?>">
                                                </td>

                                                <td><?= $no++ ?></td>

                                                <td>

                                                    <?php if ($row['service_category'] == 'Layanan Keamanan') : ?>

                                                        <span class="badge badge-primary p-2">
                                                            <?= $row['service_category'] ?>
                                                        </span>

                                                    <?php else : ?>

                                                        <span class="badge badge-success p-2">
                                                            <?= $row['service_category'] ?>
                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <td><?= htmlspecialchars($row['service_name']) ?></td>

                                                <td>
                                                    <img
                                                        src="../assets/images/services/<?= $row['service_picture'] ?>"
                                                        width="80"
                                                        height="60"
                                                        style="object-fit:cover; border-radius:10px;">
                                                </td>

                                                <td>

                                                    <div style="max-width:250px; white-space:nowrap;
                                                    overflow:hidden; text-overflow:ellipsis;">
                                                        <?= htmlspecialchars($row['service_desc']) ?>
                                                    </div>

                                                </td>

                                                <td>

                                                    <button
                                                        class="btn btn-info btn-sm mb-1"
                                                        data-toggle="modal"
                                                        data-target="#modalView<?= $row['id'] ?>" title="Lihat Layanan">
                                                        View
                                                    </button>

                                                    <button
                                                        class="btn btn-warning btn-sm mb-1"
                                                        data-toggle="modal"
                                                        data-target="#modalEdit<?= $row['id'] ?>" title="Edit Layanan">
                                                        Edit
                                                    </button>

                                                    <button
                                                        class="btn btn-danger btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalHapus<?= $row['id'] ?>" title="Hapus Layanan">
                                                        Hapus
                                                    </button>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    </tbody>
                                </table>
                            </div>

                            <!-- PAGINATION -->
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-danger" id="deleteSelected">Hapus Terpilih</button>
                                <ul class="pagination" id="pagination"></ul>
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

    <!-- ********************************** // MODAL ********************************** -->
    <!-- =========================
    MODAL LOADING TOLONG BUATKAN AGAR KELIATAN KEREN
========================= -->

    <div class="modal fade"
        id="modalLoading"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0"
                style="
                border-radius:20px;
                overflow:hidden;
            ">

                <div class="modal-body text-center p-5">

                    <div class="spinner-border text-primary mb-4"
                        style="
                        width:4rem;
                        height:4rem;
                    ">
                    </div>

                    <h5 class="font-weight-bold mb-2">
                        Sedang Memproses...
                    </h5>

                    <p class="text-muted mb-0">
                        Mohon tunggu sebentar
                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST"
                    enctype="multipart/form-data">

                    <div class="modal-header">
                        <h5>Tambah Data</h5>

                        <button type="button"
                            class="close"
                            data-dismiss="modal">
                            &times;
                        </button>
                    </div>

                    <div class="modal-body">

                        <label>Kategori Layanan</label>

                        <select
                            name="service_category"
                            class="form-control mb-3"
                            required>

                            <option value="">
                                -- Pilih Kategori --
                            </option>

                            <option value="Layanan Keamanan">
                                Layanan Keamanan
                            </option>

                            <option value="Fasilitas & Operasional">
                                Fasilitas & Operasional
                            </option>

                        </select>

                        <label>Nama Layanan</label>

                        <input type="text"
                            name="service_name"
                            class="form-control mb-3"
                            placeholder="Nama Layanan"
                            required>

                        <label>Upload Gambar</label>

                        <input type="file"
                            name="service_picture"
                            id="gambarTambah"
                            class="form-control mb-3"
                            accept="image/*"
                            onchange="previewTambah(event)"
                            required>

                        <img id="previewTambah"
                            src=""
                            class="img-fluid mb-3 d-none"
                            style="
                        width:100%;
                        height:220px;
                        object-fit:cover;
                        border-radius:15px;
                    ">

                        <label>Deskripsi</label>

                        <textarea
                            name="service_desc"
                            class="form-control"
                            rows="5"
                            placeholder="Masukkan deskripsi layanan"
                            required></textarea>

                    </div>

                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit"
                            name="tambah_service"
                            class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <?php
    $dataModal = mysqli_query($conn, "
    SELECT *
    FROM services
    ORDER BY id DESC
");

    while ($m = mysqli_fetch_assoc($dataModal)) :
    ?>

        <!-- MODAL EDIT -->
        <div class="modal fade"
            id="modalEdit<?= $m['id'] ?>">

            <div class="modal-dialog modal-xl modal-dialog-scrollable">

                <div class="modal-content"
                    style="
            max-height:95vh;
            border-radius:18px;
            overflow:hidden;
        ">

                    <form method="POST"
                        enctype="multipart/form-data"
                        style="
                height:100%;
                display:flex;
                flex-direction:column;
            ">

                        <input type="hidden"
                            name="id"
                            value="<?= $m['id'] ?>">

                        <!-- HEADER -->
                        <div class="modal-header"
                            style="
                    position:sticky;
                    top:0;
                    z-index:20;
                    background:#fff;
                    border-bottom:1px solid #eee;
                ">

                            <h5 class="mb-0">
                                Edit Layanan
                            </h5>

                            <button type="button"
                                class="close"
                                data-dismiss="modal">

                                <span>&times;</span>

                            </button>

                        </div>

                        <!-- BODY -->
                        <div class="modal-body"
                            style="
                    overflow-y:auto;
                    padding:25px;
                    padding-bottom:140px;
                    flex:1;
                ">

                            <label class="font-weight-bold">
                                Kategori Layanan
                            </label>

                            <select
                                name="service_category"
                                class="form-control mb-4"
                                required>

                                <option value="Layanan Keamanan"
                                    <?= $m['service_category'] == 'Layanan Keamanan' ? 'selected' : '' ?>>

                                    Layanan Keamanan

                                </option>

                                <option value="Fasilitas & Operasional"
                                    <?= $m['service_category'] == 'Fasilitas & Operasional' ? 'selected' : '' ?>>

                                    Fasilitas & Operasional

                                </option>

                            </select>

                            <label class="font-weight-bold">
                                Nama Layanan
                            </label>

                            <input type="text"
                                name="service_name"
                                class="form-control mb-4"
                                value="<?= htmlspecialchars($m['service_name']) ?>"
                                required>

                            <label class="font-weight-bold">
                                Upload Gambar
                            </label>

                            <input type="file"
                                name="service_picture"
                                class="form-control mb-3"
                                accept="image/*">

                            <img
                                src="../assets/images/services/<?= $m['service_picture'] ?>"
                                class="img-fluid mb-4"
                                style="
                        width:100%;
                        max-height:300px;
                        object-fit:cover;
                        border-radius:15px;
                        box-shadow:0 5px 15px rgba(0,0,0,.1);
                    ">

                            <label class="font-weight-bold">
                                Deskripsi
                            </label>

                            <textarea
                                name="service_desc"
                                class="form-control"
                                required
                                style="
                        min-height:300px;
                        resize:vertical;
                        line-height:1.8;
                    "><?= htmlspecialchars($m['service_desc']) ?></textarea>

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer"
                            style="
                    position:sticky;
                    bottom:0;
                    z-index:20;
                    background:#fff;
                    border-top:1px solid #eee;
                    padding:15px 20px;
                ">

                            <button
                                type="button"
                                class="btn btn-secondary px-4"
                                data-dismiss="modal"
                                style="
                        border-radius:10px;
                        min-width:120px;
                    ">

                                Batal

                            </button>

                            <button
                                type="submit"
                                name="update_service"
                                class="btn btn-success px-4"
                                style="
                        border-radius:10px;
                        min-width:120px;
                    ">

                                Update

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- MODAL VIEW -->
        <div class="modal fade"
            id="modalView<?= $m['id'] ?>">

            <div class="modal-dialog modal-lg modal-dialog-scrollable">

                <div class="modal-content" style="max-height:90vh; border-radius:18px;">

                    <div class="modal-header"
                        style="position:sticky; top:0; background:#fff; z-index:10;">
                        <h5>Detail Layanan</h5>
                    </div>

                    <div class="modal-body">

                        <img
                            src="../assets/images/services/<?= $m['service_picture'] ?>"
                            class="img-fluid mb-4"
                            style="width:100%; max-height:400px; object-fit:cover; border-radius:20px;">

                        <div class="mb-4">

                            <?php if ($m['service_category'] == 'Layanan Keamanan') : ?>

                                <span class="badge badge-primary p-2 px-3">

                                    <?= $m['service_category'] ?>

                                </span>

                            <?php else : ?>

                                <span class="badge badge-success p-2 px-3">

                                    <?= $m['service_category'] ?>

                                </span>

                            <?php endif; ?>

                        </div>

                        <h4 class="mb-3">
                            <?= htmlspecialchars($m['service_name']) ?>
                        </h4>

                        <div style="
                    line-height:1.8;
                    text-align:justify;
                    white-space:pre-line;
                ">
                            <?= htmlspecialchars($m['service_desc']) ?>
                        </div>

                    </div>

                    <div class="modal-footer"
                        style="position:sticky; bottom:0; background:#fff; z-index:10;">

                        <button
                            class="btn btn-secondary"
                            data-dismiss="modal">
                            Tutup
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <!-- MODAL HAPUS -->
        <div class="modal fade"
            id="modalHapus<?= $m['id'] ?>">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form method="POST">

                        <input type="hidden"
                            name="hapus_id"
                            value="<?= $m['id'] ?>">

                        <div class="modal-body text-center p-5">

                            <h4 class="mb-3">
                                Hapus Data?
                            </h4>

                            <p>
                                <?= htmlspecialchars($m['service_name']) ?>
                            </p>

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">
                                Batal
                            </button>

                            <button
                                type="submit"
                                name="hapus_service"
                                class="btn btn-danger">
                                Hapus
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

    <!-- Moment.js -->
    <script src="../assets/vendor/moment.min.js"></script>
    <script src="../assets/vendor/moment-range.js"></script>

    <script>
        function renderPagination(total) {
            let pageCount = Math.ceil(total / rowsPerPage);
            let pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            if (pageCount <= 1) return;

            // PREV BUTTON
            pagination.innerHTML += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" onclick="changePage(${currentPage - 1})">Prev</a>
        </li>
    `;

            let maxVisible = 5;
            let start = Math.max(1, currentPage - 2);
            let end = Math.min(pageCount, currentPage + 2);

            // FIX kalau di awal
            if (currentPage <= 3) {
                start = 1;
                end = Math.min(pageCount, maxVisible);
            }

            // FIX kalau di akhir
            if (currentPage >= pageCount - 2) {
                start = Math.max(1, pageCount - (maxVisible - 1));
                end = pageCount;
            }

            // FIRST PAGE + DOTS
            if (start > 1) {
                pagination.innerHTML += `
            <li class="page-item"><a class="page-link" onclick="changePage(1)">1</a></li>
        `;
                if (start > 2) {
                    pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            // PAGE NUMBERS
            for (let i = start; i <= end; i++) {
                pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" onclick="changePage(${i})">${i}</a>
            </li>
        `;
            }

            // LAST PAGE + DOTS
            if (end < pageCount) {
                if (end < pageCount - 1) {
                    pagination.innerHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                pagination.innerHTML += `
            <li class="page-item"><a class="page-link" onclick="changePage(${pageCount})">${pageCount}</a></li>
        `;
            }

            // NEXT BUTTON
            pagination.innerHTML += `
        <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
            <a class="page-link" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;
        }

        function changePage(page) {
            currentPage = page;
            renderTable();
        }

        function previewEdit(event) {

            let reader = new FileReader();

            reader.onload = function() {

                document.getElementById('previewEdit').src =
                    reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        }

        // =========================
        // LOADING FUNCTION
        // =========================

        function showLoading(callback) {

            // SHOW LOADING
            $('#modalLoading').modal('show');

            // DELAY
            setTimeout(() => {

                // HIDE LOADING
                $('#modalLoading').modal('hide');

                // OPEN MODAL
                setTimeout(() => {

                    callback();

                }, 300);

            }, 1200);

        }

        function viewData(index) {

            showLoading(() => {

                document.getElementById("viewNama").innerText =
                    data[index].nama;

                document.getElementById("viewDeskripsi").innerText =
                    data[index].deskripsi;

                document.getElementById("viewGambar").src =
                    data[index].gambar;

                $('#modalView').modal('show');

            });

        }

        document.getElementById("checkAll").onclick = function() {
            document.querySelectorAll(".rowCheck").forEach(c => c.checked = this.checked);
        };

        document.getElementById("searchInput").onkeyup = renderTable;

        document.getElementById("showEntries").onchange = function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            renderTable();
        };

        renderTable();
    </script>

    <script>
        function previewTambah(event) {

            let reader = new FileReader();

            reader.onload = function() {

                let preview =
                    document.getElementById("previewTambah");

                preview.src = reader.result;

                preview.classList.remove("d-none");
            }

            reader.readAsDataURL(
                event.target.files[0]
            );
        }
    </script>

    <script>
        let rowsPerPage = 5;
        let currentPage = 1;

        function renderTable() {

            let input =
                document.getElementById("searchInput")
                .value.toLowerCase();

            let rows =
                document.querySelectorAll("#dataTable tbody tr");

            let filtered = [];

            rows.forEach(row => {

                let text =
                    row.innerText.toLowerCase();

                if (text.includes(input)) {

                    filtered.push(row);

                    row.style.display = "";
                } else {

                    row.style.display = "none";
                }
            });

            rows.forEach(r => r.style.display = "none");

            let start =
                (currentPage - 1) * rowsPerPage;

            let end =
                start + rowsPerPage;

            filtered
                .slice(start, end)
                .forEach(r => r.style.display = "");

            renderPagination(filtered.length);
        }

        renderTable();
    </script>

    <script>
        setTimeout(() => {

            $('.notif-auto').alert('close');

        }, 4000);
    </script>
</body>

</html>