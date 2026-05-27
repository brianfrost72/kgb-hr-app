<?php
session_start();
require_once __DIR__ . "/../koneksi.php";

/*
|--------------------------------------------------------------------------
| TAMBAH ROLE
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah_role'])) {

    $role_name = trim($_POST['role_name']);

    // VALIDASI KOSONG
    if (empty($role_name)) {

        $_SESSION['error'] = "Nama role wajib diisi!";
    } else {

        // CEK DUPLIKAT
        $check = mysqli_query($conn, "
            SELECT id 
            FROM roles 
            WHERE role_name = '" . mysqli_real_escape_string($conn, $role_name) . "'
        ");

        if (mysqli_num_rows($check) > 0) {

            $_SESSION['error'] = "Role sudah tersedia!";
        } else {

            // INSERT
            mysqli_query($conn, "
                INSERT INTO roles (
                    role_name
                ) VALUES (
                    '" . mysqli_real_escape_string($conn, $role_name) . "'
                )
            ");

            $_SESSION['success'] = "Role berhasil ditambahkan!";
        }
    }

    header("Location: add_roles");
    exit;
}

/*
|--------------------------------------------------------------------------
| UPDATE ROLE
|--------------------------------------------------------------------------
*/
if (isset($_POST['update_role'])) {

    $id        = (int) $_POST['id'];
    $role_name = trim($_POST['role_name']);

    // VALIDASI
    if (empty($role_name)) {

        $_SESSION['error'] = "Nama role wajib diisi!";
    } else {

        // CEK DUPLIKAT
        $check = mysqli_query($conn, "
            SELECT id 
            FROM roles 
            WHERE role_name = '" . mysqli_real_escape_string($conn, $role_name) . "'
            AND id != $id
        ");

        if (mysqli_num_rows($check) > 0) {

            $_SESSION['error'] = "Role sudah tersedia!";
        } else {

            // UPDATE
            mysqli_query($conn, "
                UPDATE roles 
                SET role_name = '" . mysqli_real_escape_string($conn, $role_name) . "'
                WHERE id = $id
            ");

            $_SESSION['success'] = "Role berhasil diupdate!";
        }
    }

    header("Location: add_roles");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS SINGLE
|--------------------------------------------------------------------------
*/
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM roles
        WHERE id = $id
    ");

    $_SESSION['success'] = "Role berhasil dihapus!";

    header("Location: add_roles");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS SELECTED
|--------------------------------------------------------------------------
*/
if (isset($_POST['hapus_terpilih'])) {

    if (!empty($_POST['selected'])) {

        $ids = $_POST['selected'];

        $filtered = [];

        foreach ($ids as $id) {
            $filtered[] = (int) $id;
        }

        $implode = implode(",", $filtered);

        mysqli_query($conn, "
            DELETE FROM roles
            WHERE id IN ($implode)
        ");

        $_SESSION['success'] = "Data terpilih berhasil dihapus!";
    }

    header("Location: add_roles");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA
|--------------------------------------------------------------------------
*/
$roles = mysqli_query($conn, "
    SELECT *
    FROM roles
    ORDER BY id ASC
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
    <title>Tambah Role - Dashboard | Konig Guard Bureau</title>
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
                                        Tambah Role
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Tambah Role</h1>
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
                            <h4 class="card-title">Tambah Roles</h4>
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
                                                <th>Nama Roles</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $no = 1;

                                            while ($row = mysqli_fetch_assoc($roles)) :
                                            ?>
                                                <tr>

                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            name="selected[]"
                                                            value="<?= $row['id']; ?>"
                                                            class="rowCheck">
                                                    </td>

                                                    <td><?= $no++; ?></td>

                                                    <td><?= htmlspecialchars($row['role_name']); ?></td>

                                                    <td>

                                                        <!-- BUTTON EDIT -->
                                                        <button
                                                            type="button"
                                                            class="btn btn-warning btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#modalEdit<?= $row['id']; ?>">

                                                            Edit

                                                        </button>

                                                        <!-- BUTTON DELETE -->
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

                                        </tbody>
                                    </table>
                                </div>
                            </form>

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
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5>Tambah Role</h5>
                    </div>

                    <div class="modal-body">

                        <input
                            type="text"
                            name="role_name"
                            class="form-control"
                            placeholder="Nama Role">

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            name="tambah_role"
                            class="btn btn-primary">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- AREA MODAL EDIT DAN DELETE -->
    <?php
    mysqli_data_seek($roles, 0);

    while ($row = mysqli_fetch_assoc($roles)) :
    ?>

        <!-- MODAL EDIT -->
        <div
            class="modal fade"
            id="modalEdit<?= $row['id']; ?>"
            tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form method="POST">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Edit Role
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
                                value="<?= $row['id']; ?>">

                            <div class="form-group">

                                <label class="font-weight-bold">
                                    Nama Role
                                </label>

                                <input
                                    type="text"
                                    name="role_name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($row['role_name']); ?>"
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
                                name="update_role"
                                class="btn btn-success">

                                Update

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- MODAL DELETE -->
        <div
            class="modal fade"
            id="modalDelete<?= $row['id']; ?>"
            tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">
                            Hapus Role
                        </h5>

                        <button
                            type="button"
                            class="close text-white"
                            data-dismiss="modal">

                            <span>&times;</span>

                        </button>

                    </div>

                    <div class="modal-body text-center">

                        <span
                            class="material-icons mb-3"
                            style="
                            font-size:70px;
                            color:#dc3545;
                        ">

                            delete_forever

                        </span>

                        <h5 class="mb-3">
                            Yakin ingin menghapus role ini?
                        </h5>

                        <div class="alert alert-danger">

                            <strong>
                                <?= htmlspecialchars($row['role_name']); ?>
                            </strong>

                        </div>

                        <p class="text-muted mb-0">
                            Data yang dihapus tidak dapat dikembalikan.
                        </p>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                            Batal

                        </button>

                        <a
                            href="add_roles?hapus=<?= $row['id']; ?>"
                            class="btn btn-danger">

                            Ya, Hapus

                        </a>

                    </div>

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

    <script>
        // ALERT AUTO CLOSE
        $(document).ready(function() {

            setTimeout(function() {

                $('.auto-close-alert').alert('close');

            }, 3000);

        });
    </script>

    
</body>

</html>