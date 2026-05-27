<?php
session_start();
require_once __DIR__ . "/../koneksi.php";

/*
|--------------------------------------------------------------------------
| PROSES TAMBAH
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah_region'])) {

    $region_name    = trim($_POST['region_name']);
    $region_address = trim($_POST['region_address']);

    if ($region_name != '' && $region_address != '') {

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */
        $check = mysqli_prepare($conn, "
            SELECT id
            FROM regions
            WHERE LOWER(TRIM(region_name)) = LOWER(TRIM(?))
        ");

        mysqli_stmt_bind_param(
            $check,
            "s",
            $region_name
        );

        mysqli_stmt_execute($check);

        $result_check = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result_check) > 0) {

            $_SESSION['error'] = "Nama cabang sudah tersedia.";
            header("Location:add_regions");
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT DATA
        |--------------------------------------------------------------------------
        */
        $stmt = mysqli_prepare($conn, "
            INSERT INTO regions (
                region_name,
                region_address,
                created_at,
                update_at
            ) VALUES (?, ?, NOW(), NOW())
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $region_name,
            $region_address
        );

        if (mysqli_stmt_execute($stmt)) {

            $_SESSION['success'] = "Data cabang berhasil ditambahkan.";
        } else {

            $_SESSION['error'] = "Data cabang gagal ditambahkan. " . mysqli_stmt_error($stmt);
        }

        header("Location:add_regions");
        exit;
    } else {

        header("Location:add_regions?error=kosong");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| PROSES UPDATE
|--------------------------------------------------------------------------
*/
if (isset($_POST['update_region'])) {

    $id             = (int) $_POST['id'];
    $region_name    = trim($_POST['region_name']);
    $region_address = trim($_POST['region_address']);

    if ($id > 0 && $region_name != '' && $region_address != '') {

        $stmt = mysqli_prepare($conn, "
            UPDATE regions
            SET
                region_name = ?,
                region_address = ?,
                update_at = NOW()
            WHERE id = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $region_name,
            $region_address,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            $_SESSION['success'] = "Data cabang berhasil diupdate.";
            header("Location:add_regions");
            exit;
        } else {

            $_SESSION['error'] = "Data cabang gagal diupdate.";
            header("Location:add_regions");
            exit;
        }
    } else {

        header("Location:add_regions?error=kosong");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS SINGLE
|--------------------------------------------------------------------------
*/
if (isset($_POST['delete_single'])) {

    $id = (int) $_POST['id'];

    if ($id > 0) {

        $delete = mysqli_query($conn, "
            DELETE FROM regions
            WHERE id = '$id'
        ");

        if ($delete) {

            $_SESSION['success'] = "Data cabang berhasil dihapus.";
            header("Location:add_regions");
            exit;
        } else {

            $_SESSION['error'] = "Data cabang gagal dihapus.";
            header("Location:add_regions");
            exit;
        }
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS TERPILIH
|--------------------------------------------------------------------------
*/
if (isset($_POST['delete_selected'])) {

    if (!empty($_POST['selected_id'])) {

        $ids = $_POST['selected_id'];

        $filtered = [];

        foreach ($ids as $id) {

            $filtered[] = (int) $id;
        }

        $implode = implode(",", $filtered);

        $delete = mysqli_query($conn, "
            DELETE FROM regions
            WHERE id IN ($implode)
        ");

        if ($delete) {

            $_SESSION['success'] = "Data terpilih berhasil dihapus.";
            header("Location:add_regions");
            exit;
        } else {

            $_SESSION['error'] = "Data terpilih gagal dihapus.";
            header("Location:add_regions");
            exit;
        }
    } else {

        $_SESSION['error'] = "Pilih data terlebih dahulu.";
        header("Location:add_regions");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA REGIONS
|--------------------------------------------------------------------------
*/
$regions = mysqli_query($conn, "
    SELECT *
    FROM regions
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
    <title>Tambah Cabang Lokasi - Dashboard | Konig Guard Bureau</title>
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
                                        Tambah Cabang Lokasi
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Tambah Cabang Lokasi</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">
                    <?php if (isset($_SESSION['success'])) : ?>

                        <div
                            class="alert alert-success alert-dismissible fade show shadow-sm border-0 auto-close-alert"
                            role="alert">

                            <i class="fa fa-check-circle mr-2"></i>

                            <?= $_SESSION['success']; ?>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert"
                                aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>

                    <?php unset($_SESSION['success']);
                    endif; ?>


                    <?php if (isset($_SESSION['error'])) : ?>

                        <div
                            class="alert alert-danger alert-dismissible fade show shadow-sm border-0 auto-close-alert"
                            role="alert">

                            <i class="fa fa-times-circle mr-2"></i>

                            <?= $_SESSION['error']; ?>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="alert"
                                aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>

                    <?php unset($_SESSION['error']);
                    endif; ?>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Tambah Cabang</h4>
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
                            <form method="POST">
                                <!-- TABLE -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>No</th>
                                                <th>Cabang</th>
                                                <th>Alamat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (mysqli_num_rows($regions) > 0): ?>

                                                <?php $no = 1; ?>
                                                <?php while ($row = mysqli_fetch_assoc($regions)): ?>

                                                    <tr>

                                                        <td>
                                                            <input
                                                                type="checkbox"
                                                                name="selected_id[]"
                                                                value="<?= $row['id']; ?>"
                                                                class="rowCheck">
                                                        </td>

                                                        <td><?= $no++; ?></td>

                                                        <td><?= htmlspecialchars($row['region_name']); ?></td>

                                                        <td><?= htmlspecialchars($row['region_address']); ?></td>

                                                        <td>

                                                            <button type="button"
                                                                class="btn btn-warning btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#modalEdit<?= $row['id']; ?>">
                                                                Edit
                                                            </button>

                                                            <button type="button"
                                                                class="btn btn-danger btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#modalDelete<?= $row['id']; ?>">
                                                                Hapus
                                                            </button>

                                                        </td>

                                                    </tr>

                                                <?php endwhile; ?>

                                            <?php else: ?>

                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        Tidak ada data
                                                    </td>
                                                </tr>

                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>

                                <!-- PAGINATION -->
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit"
                                        name="delete_selected"
                                        class="btn btn-danger">Hapus Terpilih</button>
                                    <ul class="pagination" id="pagination"></ul>
                                </div>
                            </form>
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
    <form method="POST">

        <div class="modal fade" id="modalTambah">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Tambah Data</h5>
                    </div>

                    <div class="modal-body">

                        <div class="form-group">
                            <label>Cabang</label>

                            <input
                                type="text"
                                name="region_name"
                                class="form-control"
                                placeholder="Cabang Lokasi"
                                required>
                        </div>

                        <div class="form-group mb-0">
                            <label>Alamat Lokasi</label>

                            <textarea
                                class="form-control"
                                name="region_address"
                                placeholder="Alamat Cabang"
                                rows="4"
                                required></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            name="tambah_region"
                            class="btn btn-primary">
                            Simpan
                        </button>

                    </div>

                </div>
            </div>
        </div>

    </form>

    <?php
    mysqli_data_seek($regions, 0);
    while ($row = mysqli_fetch_assoc($regions)):
    ?>

        <form method="POST">

            <div class="modal fade" id="modalEdit<?= $row['id']; ?>">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Edit Data</h5>
                        </div>

                        <div class="modal-body">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $row['id']; ?>">

                            <div class="form-group">

                                <label>Cabang</label>

                                <input
                                    type="text"
                                    name="region_name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($row['region_name']); ?>"
                                    required>

                            </div>

                            <div class="form-group mb-0">

                                <label>Alamat Lokasi</label>

                                <textarea
                                    class="form-control"
                                    name="region_address"
                                    rows="4"
                                    required><?= htmlspecialchars($row['region_address']); ?></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="submit"
                                name="update_region"
                                class="btn btn-success">
                                Update
                            </button>

                        </div>

                    </div>
                </div>
            </div>

        </form>

    <?php endwhile; ?>

    <!-- MODAL HAPUS DISINI -->
    <?php
    mysqli_data_seek($regions, 0);
    while ($row = mysqli_fetch_assoc($regions)):
    ?>

        <form method="POST">

            <div class="modal fade" id="modalDelete<?= $row['id']; ?>">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Hapus Data</h5>
                        </div>

                        <div class="modal-body text-center">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $row['id']; ?>">

                            <p>
                                Yakin ingin menghapus
                                <br>
                                <strong><?= htmlspecialchars($row['region_name']); ?></strong> ?
                            </p>

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
                                name="delete_single"
                                class="btn btn-danger">
                                Hapus
                            </button>

                        </div>

                    </div>
                </div>
            </div>

        </form>

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
    <script src="../assets/js/pagination.js"></script>

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

            /*
            |--------------------------------------------------------------------------
            | CHECK ALL
            |--------------------------------------------------------------------------
            */

            $('#checkAll').on('click', function() {

                $('.rowCheck').prop(
                    'checked',
                    $(this).prop('checked')
                );

            });

            /*
            |--------------------------------------------------------------------------
            | JIKA SEMUA TERCENTANG
            |--------------------------------------------------------------------------
            */

            $('.rowCheck').on('click', function() {

                if (
                    $('.rowCheck:checked').length ==
                    $('.rowCheck').length
                ) {

                    $('#checkAll').prop('checked', true);

                } else {

                    $('#checkAll').prop('checked', false);

                }

            });

        });
    </script>

    <script>
        $(document).ready(function() {

            setTimeout(function() {

                $('.auto-close-alert').alert('close');

            }, 3000);

        });
    </script>

    <script>
        // ==================================== PAGINATION ====================================
        function renderPagination(total) {
            let pageCount = Math.ceil(total / rowsPerPage);
            let pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            if (pageCount <= 1) return;

            // PREV BUTTON
            pagination.innerHTML += `
        <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
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
            <li class="page-item ${i === currentPage ? "active" : ""}">
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
        <li class="page-item ${currentPage === pageCount ? "disabled" : ""}">
            <a class="page-link" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;
        }

        // ===================================== PERGANTIAN HALAMAN PAGINATION =================================
        function changePage(page) {
            currentPage = page;
            renderTable();
        }
    </script>
</body>

</html>