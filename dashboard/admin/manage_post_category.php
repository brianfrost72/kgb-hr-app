<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/*
|--------------------------------------------------------------------------
| TAMBAH DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah_category'])) {

    $inputName = trim($_POST['name_category']);

    $safeName = mysqli_real_escape_string(
        $conn,
        $inputName
    );

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI TEXT
    |--------------------------------------------------------------------------
    */
    $normalize = strtolower(trim($inputName));

    /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT UNIVERSAL
    |--------------------------------------------------------------------------
    */
    $cekData = mysqli_query($conn, "
        SELECT *
        FROM post_category
        WHERE LOWER(TRIM(name_category)) = '$normalize'
    ");

    if (mysqli_num_rows($cekData) > 0) {

        echo "
        <script>
            alert('Data sudah ada!');
            window.location='manage_post_category.php';
        </script>
        ";
    } else {

        $queryTambah = mysqli_query($conn, "
            INSERT INTO post_category (
                name_category,
                created_at
            ) VALUES (
                '$safeName',
                NOW()
            )
        ");

        if ($queryTambah) {

            echo "
            <script>
                alert('Data berhasil ditambahkan');
                window.location='manage_post_category.php';
            </script>
            ";
        } else {

            echo "
            <script>
                alert('Gagal tambah data');
            </script>
            ";
        }
    }
}

/*
|--------------------------------------------------------------------------
| EDIT CATEGORY
|--------------------------------------------------------------------------
*/
if (isset($_POST['edit_category'])) {

    $id = (int) $_POST['id'];

    $inputName = trim($_POST['name_category']);

    $safeName = mysqli_real_escape_string(
        $conn,
        $inputName
    );

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI TEXT
    |--------------------------------------------------------------------------
    */
    $normalize = strtolower(trim($inputName));

    /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT SELAIN ID SENDIRI
    |--------------------------------------------------------------------------
    */
    $cekData = mysqli_query($conn, "
        SELECT *
        FROM post_category
        WHERE LOWER(TRIM(name_category)) = '$normalize'
        AND id != '$id'
    ");

    if (mysqli_num_rows($cekData) > 0) {

        echo "
        <script>
            alert('Data sudah ada!');
            window.location='manage_post_category.php';
        </script>
        ";
    } else {

        $queryEdit = mysqli_query($conn, "
            UPDATE post_category
            SET
                name_category = '$safeName'
            WHERE id = '$id'
        ");

        if ($queryEdit) {

            echo "
            <script>
                alert('Data berhasil diupdate');
                window.location='manage_post_category.php';
            </script>
            ";
        } else {

            echo "
            <script>
                alert('Gagal update data');
            </script>
            ";
        }
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS DATA
|--------------------------------------------------------------------------
*/
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM post_category
        WHERE id = '$id'
    ");

    echo "
    <script>
        alert('Data berhasil dihapus');
        window.location='manage_post_category.php';
    </script>
    ";
}

// HAPUS TERPILIH
if (isset($_POST['hapus_terpilih'])) {

    if (!empty($_POST['selected_ids'])) {

        $ids = $_POST['selected_ids'];

        $ids = array_map('intval', $ids);

        $idsString = implode(',', $ids);

        mysqli_query($conn, "
            DELETE FROM post_category
            WHERE id IN ($idsString)
        ");

        echo "
        <script>
            alert('Data terpilih berhasil dihapus');
            window.location='manage_post_category.php';
        </script>
        ";
    } else {

        echo "
        <script>
            alert('Pilih data terlebih dahulu');
        </script>
        ";
    }
}

/*
|--------------------------------------------------------------------------
| EDIT DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['edit_category'])) {

    $id = (int) $_POST['id'];
    $name_category = mysqli_real_escape_string($conn, $_POST['name_category']);

    mysqli_query($conn, "
        UPDATE post_category
        SET
            name_category = '$name_category'
        WHERE id = '$id'
    ");

    echo "<script>
        alert('Kategori berhasil diupdate');
        window.location='manage_post_category.php';
    </script>";
}

$search = isset($_GET['search'])
    ? trim($_GET['search'])
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

/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/
$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM post_category
    WHERE name_category LIKE '%$search%'
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $show);

/*
|--------------------------------------------------------------------------
| GET DATA
|--------------------------------------------------------------------------
*/
$dataKategori = mysqli_query($conn, "
    SELECT *
    FROM post_category
    WHERE name_category LIKE '%$search%'
    ORDER BY id DESC
    LIMIT $offset, $show
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
    <title>Manage Kategori Posting - Dashboard | Konig Guard Bureau</title>


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
                                        Manage Kategori Postingan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Kategori Postingan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Manage Kategori Postingan</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
                        </div>

                        <div class="card-body">
                            <!-- FILTER -->
                            <form method="GET" class="mb-3">

                                <div class="d-flex justify-content-between align-items-center flex-wrap">

                                    <div class="d-flex align-items-center mb-2">

                                        <label class="mr-2 mb-0">
                                            Show
                                        </label>

                                        <select
                                            name="show"
                                            class="form-control mr-2"
                                            onchange="this.form.submit()"
                                            style="width:80px;">

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

                                        <span>entries</span>

                                    </div>

                                    <div class="d-flex align-items-center mb-2">

                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control"
                                            placeholder="Search..."
                                            value="<?= htmlspecialchars($search); ?>">

                                        <button
                                            type="submit"
                                            class="btn btn-primary ml-2">

                                            Search

                                        </button>

                                    </div>

                                </div>

                            </form>

                            <!-- TABLE -->
                            <div class="table-responsive">

                                <form method="POST" id="formHapusTerpilih">
                                    <table class="table table-bordered table-hover" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>No</th>
                                                <th>Nama Kategori</th>
                                                <th>Dibuat Tgl.</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($dataKategori)) :
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            class="rowCheck"
                                                            name="selected_ids[]"
                                                            value="<?= $row['id']; ?>">
                                                    </td>

                                                    <td><?= $no++; ?></td>

                                                    <td><?= htmlspecialchars($row['name_category']); ?></td>

                                                    <td>
                                                        <?= date('d M Y H:i', strtotime($row['created_at'])); ?>
                                                    </td>

                                                    <td>
                                                        <button
                                                            class="btn btn-warning btn-sm btnEdit"

                                                            data-id="<?= $row['id']; ?>"
                                                            data-name="<?= htmlspecialchars($row['name_category']); ?>"

                                                            data-toggle="modal"
                                                            data-target="#modalEdit">

                                                            Edit
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-danger btn-sm btnHapus"
                                                            data-id="<?= $row['id']; ?>"
                                                            data-toggle="modal"
                                                            data-target="#modalHapus">

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
                                <button
                                    type="button"
                                    id="btnHapusTerpilih"
                                    class="btn btn-danger mt-3">

                                    Hapus Terpilih

                                </button>
                                </form>
                                <nav class="mt-4">

                                    <ul class="pagination">

                                        <?php if ($page > 1): ?>

                                            <li class="page-item">

                                                <a class="page-link"
                                                    href="?page=<?= $page - 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                    Previous

                                                </a>

                                            </li>

                                        <?php endif; ?>

                                        <?php

                                        $start = max(1, $page - 2);
                                        $end = min($totalPages, $page + 2);

                                        if ($start > 1) {

                                            echo '
            <li class="page-item">
                <a class="page-link"
                href="?page=1&show=' . $show . '&search=' . urlencode($search) . '">
                1
                </a>
            </li>';

                                            if ($start > 2) {
                                                echo '
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>';
                                            }
                                        }

                                        for ($i = $start; $i <= $end; $i++) :
                                        ?>

                                            <li class="page-item <?= $i == $page ? 'active' : ''; ?>">

                                                <a class="page-link"
                                                    href="?page=<?= $i; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                    <?= $i; ?>

                                                </a>

                                            </li>

                                        <?php endfor; ?>

                                        <?php

                                        if ($end < $totalPages) {

                                            if ($end < $totalPages - 1) {

                                                echo '
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>';
                                            }

                                            echo '
            <li class="page-item">
                <a class="page-link"
                href="?page=' . $totalPages . '&show=' . $show . '&search=' . urlencode($search) . '">
                ' . $totalPages . '
                </a>
            </li>';
                                        }

                                        ?>

                                        <?php if ($page < $totalPages): ?>

                                            <li class="page-item">

                                                <a class="page-link"
                                                    href="?page=<?= $page + 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>">

                                                    Next

                                                </a>

                                            </li>

                                        <?php endif; ?>

                                    </ul>

                                </nav>
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
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">

            <form method="POST">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Tambah Kategori</h5>
                    </div>

                    <div class="modal-body">

                        <label>Nama Kategori</label>

                        <input
                            type="text"
                            name="name_category"
                            class="form-control"
                            placeholder="Masukkan nama kategori"
                            required>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            name="tambah_category"
                            class="btn btn-primary">
                            Simpan
                        </button>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit" tabindex="-1">

        <div class="modal-dialog">

            <form method="POST">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Edit Kategori
                        </h5>

                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal">

                            <span>&times;</span>
                        </button>

                    </div>

                    <div class="modal-body">

                        <input
                            type="hidden"
                            name="id"
                            id="edit_id">

                        <div class="form-group">

                            <label>Nama Kategori</label>

                            <input
                                type="text"
                                name="name_category"
                                id="edit_name_category"
                                class="form-control"
                                required>

                        </div>

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
                            name="edit_category"
                            class="btn btn-primary">
                            Update
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- MODAL HAPUS -->
    <div class="modal fade" id="modalHapus">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Hapus Data
                    </h5>

                </div>

                <div class="modal-body">

                    Yakin ingin menghapus data ini?

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                        Batal

                    </button>

                    <a
                        href="#"
                        id="btnConfirmHapus"
                        class="btn btn-danger">

                        Hapus

                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- MODAL HAPUS TERPILIH -->
    <div class="modal fade" id="modalHapusTerpilih">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Hapus Data Terpilih
                    </h5>

                </div>

                <div class="modal-body">

                    Yakin ingin menghapus data terpilih?

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
                        name="hapus_terpilih"
                        form="formHapusTerpilih"
                        class="btn btn-danger">

                        Hapus

                    </button>

                </div>

            </div>

        </div>

    </div>
    <!-- ********************************** // MODAL END ********************************** -->

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
        document.querySelectorAll('.btnEdit').forEach(button => {

            button.addEventListener('click', function() {

                let id = this.getAttribute('data-id');
                let name = this.getAttribute('data-name');

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_name_category').value = name;

            });

        });
    </script>

    <script>
        /*
|--------------------------------------------------------------------------
| CHECK ALL
|--------------------------------------------------------------------------
*/
        document.getElementById('checkAll').addEventListener('change', function() {

            let checked = this.checked;

            document.querySelectorAll('.rowCheck').forEach(item => {
                item.checked = checked;
            });

        });

        /*
        |--------------------------------------------------------------------------
        | HAPUS SATU DATA
        |--------------------------------------------------------------------------
        */
        document.querySelectorAll('.btnHapus').forEach(button => {

            button.addEventListener('click', function() {

                let id = this.getAttribute('data-id');

                document.getElementById('btnConfirmHapus').href =
                    '?hapus=' + id;

            });

        });
    </script>

    <script>
        /*
|--------------------------------------------------------------------------
| VALIDASI HAPUS TERPILIH
|--------------------------------------------------------------------------
*/
        document.getElementById('btnHapusTerpilih')
            .addEventListener('click', function() {

                let checked = document.querySelectorAll('.rowCheck:checked');

                /*
                |--------------------------------------------------------------------------
                | BELUM PILIH DATA
                |--------------------------------------------------------------------------
                */
                if (checked.length === 0) {

                    alert('Pilih dahulu data yang ingin dihapus');

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN MODAL
                |--------------------------------------------------------------------------
                */
                $('#modalHapusTerpilih').modal('show');

            });
    </script>
</body>

</html>