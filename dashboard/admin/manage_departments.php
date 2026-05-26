<?php
session_start();

require_once __DIR__ . "/../koneksi.php";

/*
|--------------------------------------------------------------------------
| TAMBAH DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah_department'])) {

    $department_name = trim($_POST['department_name']);

    if ($department_name != '') {

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $check = mysqli_prepare($conn, "
            SELECT id
            FROM departments
            WHERE LOWER(TRIM(department_name)) = LOWER(TRIM(?))
        ");

        mysqli_stmt_bind_param(
            $check,
            "s",
            $department_name
        );

        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $_SESSION['error'] = "Nama departemen sudah digunakan.";

            header("Location:manage_departments.php");
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT DATA
        |--------------------------------------------------------------------------
        */

        $stmt = mysqli_prepare($conn, "
            INSERT INTO departments (
                department_name,
                created_at
            ) VALUES (?, NOW())
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $department_name
        );

        if (mysqli_stmt_execute($stmt)) {

            $_SESSION['success'] = "Departemen berhasil ditambahkan.";
        } else {

            $_SESSION['error'] = "Departemen gagal ditambahkan.";
        }
    } else {

        $_SESSION['error'] = "Nama departemen wajib diisi.";
    }

    header("Location:manage_departments.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['update_department'])) {

    $id = (int) $_POST['id'];
    $department_name = trim($_POST['department_name']);

    if ($id > 0 && $department_name != '') {

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT SAAT EDIT
        |--------------------------------------------------------------------------
        */

        $check = mysqli_prepare($conn, "
            SELECT id
            FROM departments
            WHERE LOWER(TRIM(department_name)) = LOWER(TRIM(?))
            AND id != ?
        ");

        mysqli_stmt_bind_param(
            $check,
            "si",
            $department_name,
            $id
        );

        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $_SESSION['error'] = "Nama departemen sudah digunakan.";

            header("Location:manage_departments.php");
            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $stmt = mysqli_prepare($conn, "
            UPDATE departments
            SET department_name = ?
            WHERE id = ?
        ");

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $department_name,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            $_SESSION['success'] = "Departemen berhasil diupdate.";
        } else {

            $_SESSION['error'] = "Departemen gagal diupdate.";
        }
    } else {

        $_SESSION['error'] = "Data tidak valid.";
    }

    header("Location:manage_departments.php");
    exit;
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
            DELETE FROM departments
            WHERE id = '$id'
        ");

        if ($delete) {

            $_SESSION['success'] = "Departemen berhasil dihapus.";
        } else {

            $_SESSION['error'] = "Departemen gagal dihapus.";
        }
    }

    header("Location:manage_departments.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS TERPILIH
|--------------------------------------------------------------------------
*/
if (isset($_POST['delete_selected'])) {

    if (!empty($_POST['selected_id'])) {

        $ids = [];

        foreach ($_POST['selected_id'] as $id) {

            $ids[] = (int) $id;
        }

        $implode = implode(",", $ids);

        $delete = mysqli_query($conn, "
            DELETE FROM departments
            WHERE id IN ($implode)
        ");

        if ($delete) {

            $_SESSION['success'] = "Data terpilih berhasil dihapus.";
        } else {

            $_SESSION['error'] = "Data terpilih gagal dihapus.";
        }
    } else {

        $_SESSION['error'] = "Pilih data terlebih dahulu.";
    }

    header("Location:manage_departments.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA
|--------------------------------------------------------------------------
*/
$departments = mysqli_query($conn, "
    SELECT *
    FROM departments
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
    <title>Manage Departemen - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Departemen
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Departemen</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">
                    <?php if (isset($_SESSION['success'])) : ?>

                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">

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

                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">

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
                            <h4 class="card-title">Manage Departemen</h4>
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
                                                <th>Nama Departemen</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (mysqli_num_rows($departments) > 0): ?>

                                                <?php $no = 1; ?>
                                                <?php while ($row = mysqli_fetch_assoc($departments)): ?>

                                                    <tr>

                                                        <td>
                                                            <input
                                                                type="checkbox"
                                                                name="selected_id[]"
                                                                value="<?= $row['id']; ?>"
                                                                class="rowCheck">
                                                        </td>

                                                        <td><?= $no++; ?></td>

                                                        <td><?= htmlspecialchars($row['department_name']); ?></td>

                                                        <td>

                                                            <button
                                                                type="button"
                                                                class="btn btn-warning btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#modalEdit<?= $row['id']; ?>">

                                                                Edit

                                                            </button>

                                                            <button
                                                                type="button"
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
                                                    <td colspan="4" class="text-center">
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

                        <div class="form-group mb-0">

                            <label>Nama Departemen</label>

                            <input
                                type="text"
                                name="department_name"
                                class="form-control"
                                placeholder="Nama Departemen"
                                required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            name="tambah_department"
                            class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </div>
            </div>
        </div>

    </form>

    <?php
    mysqli_data_seek($departments, 0);
    while ($row = mysqli_fetch_assoc($departments)):
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

                            <div class="form-group mb-0">

                                <label>Nama Departemen</label>

                                <input
                                    type="text"
                                    name="department_name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($row['department_name']); ?>"
                                    required>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button
                                type="submit"
                                name="update_department"
                                class="btn btn-success">

                                Update

                            </button>

                        </div>

                    </div>
                </div>
            </div>

        </form>

    <?php endwhile; ?>

    <?php
    mysqli_data_seek($departments, 0);
    while ($row = mysqli_fetch_assoc($departments)):
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

                            <p class="mb-0">

                                Yakin ingin menghapus
                                <br>

                                <strong>
                                    <?= htmlspecialchars($row['department_name']); ?>
                                </strong>

                                ?

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

    <!-- Moment.js -->
    <script src="../assets/vendor/moment.min.js"></script>
    <script src="../assets/vendor/moment-range.js"></script>

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
</body>

</html>