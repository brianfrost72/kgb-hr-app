<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/*
|--------------------------------------------------------------------------
| QUERY POST CATEGORY (UNTUK SELECT OPTION)
|--------------------------------------------------------------------------
*/
$queryCategory = mysqli_query($conn, "
    SELECT 
        id,
        name_category
    FROM post_category
    ORDER BY id ASC
");

/*
|--------------------------------------------------------------------------
| FILTER & SEARCH
|--------------------------------------------------------------------------
*/
$show = isset($_GET['show']) ? (int)$_GET['show'] : 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$search = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, $_GET['search'])
    : '';

$filterCategory = isset($_GET['filter_category'])
    ? mysqli_real_escape_string($conn, $_GET['filter_category'])
    : '';

$start = ($page - 1) * $show;

/*
|--------------------------------------------------------------------------
| WHERE FILTER
|--------------------------------------------------------------------------
*/
$where = "WHERE 1=1";

if (!empty($search)) {

    $where .= "
        AND (
            pc.name_category LIKE '%$search%'
            OR ps.name_subcategory LIKE '%$search%'
            OR ps.desc_category LIKE '%$search%'
        )
    ";
}

if (!empty($filterCategory)) {

    $where .= "
        AND ps.id_postcategory = '$filterCategory'
    ";
}

/*
|--------------------------------------------------------------------------
| TOTAL DATA
|--------------------------------------------------------------------------
*/
$totalQuery = mysqli_query($conn, "
    SELECT COUNT(ps.id) as total
    FROM post_subcategory ps
    LEFT JOIN post_category pc
        ON ps.id_postcategory = pc.id
    $where
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];

$totalPages = ceil($totalData / $show);

/*
|--------------------------------------------------------------------------
| QUERY POST SUBCATEGORY
|--------------------------------------------------------------------------
*/
$querySubcategory = mysqli_query($conn, "
    SELECT 
        ps.id,
        ps.id_postcategory,
        pc.name_category,
        ps.name_subcategory,
        ps.desc_category
    FROM post_subcategory ps
    LEFT JOIN post_category pc 
        ON ps.id_postcategory = pc.id
    $where
    ORDER BY ps.id ASC
    LIMIT $start, $show
");

/*
|--------------------------------------------------------------------------
| TAMBAH DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['tambah_subcategory'])) {

    $id_postcategory = mysqli_real_escape_string($conn, $_POST['id_postcategory']);

    $name_subcategory = mysqli_real_escape_string($conn, $_POST['name_subcategory']);

    $desc_category = mysqli_real_escape_string($conn, $_POST['desc_category']);

    // VALIDASI
    if (
        empty($id_postcategory) ||
        empty($name_subcategory) ||
        empty($desc_category)
    ) {

        echo "
        <script>
            alert('Semua field wajib diisi!');
        </script>
        ";
    } else {


        /*
        |--------------------------------------------------------------------------
        | CEK DUPLICATE SUBCATEGORY
        |--------------------------------------------------------------------------
        */

        $checkSubcategory = mysqli_query($conn, "
            SELECT id
            FROM post_subcategory
            WHERE LOWER(name_subcategory) = LOWER('$name_subcategory')
            LIMIT 1
        ");

        if (mysqli_num_rows($checkSubcategory) > 0) {

            echo "
            <script>
                alert('Nama sub-kategori sudah digunakan!');
            </script>
            ";
        } else {

            $insert = mysqli_query($conn, "
                INSERT INTO post_subcategory (
                    id_postcategory,
                    name_subcategory,
                    desc_category
                ) VALUES (
                    '$id_postcategory',
                    '$name_subcategory',
                    '$desc_category'
                )
            ");

            if ($insert) {

                echo "
                <script>
                    alert('Data berhasil ditambahkan');
                    window.location='manage_post_subcategory.php';
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
}

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/
if (isset($_POST['update_subcategory'])) {

    $id = mysqli_real_escape_string($conn, $_POST['edit_id']);

    $id_postcategory = mysqli_real_escape_string($conn, $_POST['edit_id_postcategory']);

    $name_subcategory = mysqli_real_escape_string($conn, $_POST['edit_name_subcategory']);

    $desc_category = mysqli_real_escape_string($conn, $_POST['edit_desc_category']);

    // VALIDASI
    if (
        empty($id_postcategory) ||
        empty($name_subcategory) ||
        empty($desc_category)
    ) {

        echo "
        <script>
            alert('Semua field wajib diisi!');
        </script>
        ";
    } else {

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLICATE SAAT UPDATE
        |--------------------------------------------------------------------------
        */

        $checkDuplicate = mysqli_query($conn, "
            SELECT id
            FROM post_subcategory
            WHERE LOWER(name_subcategory) = LOWER('$name_subcategory')
            AND id != '$id'
            LIMIT 1
        ");

        if (mysqli_num_rows($checkDuplicate) > 0) {

            echo "
            <script>
                alert('Nama sub-kategori sudah digunakan!');
            </script>
            ";
        } else {

            $update = mysqli_query($conn, "
                UPDATE post_subcategory
                SET
                    id_postcategory = '$id_postcategory',
                    name_subcategory = '$name_subcategory',
                    desc_category = '$desc_category'
                WHERE id = '$id'
            ");

            if ($update) {

                echo "
                <script>
                    alert('Data berhasil diupdate');
                    window.location='manage_post_subcategory.php';
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
}

/*
|--------------------------------------------------------------------------
| HAPUS SATU
|--------------------------------------------------------------------------
*/
if (isset($_GET['hapus'])) {

    $id = mysqli_real_escape_string($conn, $_GET['hapus']);

    $delete = mysqli_query($conn, "
        DELETE FROM post_subcategory
        WHERE id = '$id'
    ");

    if ($delete) {

        echo "
        <script>
            alert('Data berhasil dihapus');
            window.location='manage_post_subcategory.php';
        </script>
        ";
    } else {

        echo "
        <script>
            alert('Gagal hapus data');
        </script>
        ";
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS TERPILIH
|--------------------------------------------------------------------------
*/
if (isset($_POST['hapus_terpilih'])) {

    if (!empty($_POST['selected_data'])) {

        $ids = $_POST['selected_data'];

        foreach ($ids as $id) {

            $id = mysqli_real_escape_string($conn, $id);

            mysqli_query($conn, "
                DELETE FROM post_subcategory
                WHERE id = '$id'
            ");
        }

        echo "
        <script>
            alert('Data terpilih berhasil dihapus');
            window.location='manage_post_subcategory.php';
        </script>
        ";
    }
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
    <title>Manage Sub-Kategori Postingan - Dashboard | Konig Guard Bureau</title>


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
                                        Manage Sub-Kategori Postingan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Sub-Kategori Postingan</h1>
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
                            <h4 class="card-title">Manage Sub-Kategori Postingan</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
                        </div>

                        <div class="card-body">
                            <!-- FILTER -->
                            <form method="GET">

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <div class="d-flex align-items-center">

                                        Show

                                        <select
                                            name="show"
                                            onchange="this.form.submit()"
                                            class="form-control mx-2 w-auto">

                                            <option value="5" <?= $show == 5 ? 'selected' : ''; ?>>5</option>

                                            <option value="10" <?= $show == 10 ? 'selected' : ''; ?>>10</option>

                                            <option value="20" <?= $show == 20 ? 'selected' : ''; ?>>20</option>

                                            <option value="50" <?= $show == 50 ? 'selected' : ''; ?>>50</option>

                                        </select>

                                        entries

                                    </div>

                                    <div class="d-flex">

                                        <select
                                            name="filter_category"
                                            class="form-control mr-2">

                                            <option value="">Semua Kategori</option>

                                            <?php
                                            mysqli_data_seek($queryCategory, 0);

                                            while ($category = mysqli_fetch_assoc($queryCategory)) :
                                            ?>

                                                <option
                                                    value="<?= $category['id']; ?>"
                                                    <?= $filterCategory == $category['id'] ? 'selected' : ''; ?>>

                                                    <?= htmlspecialchars($category['name_category']); ?>

                                                </option>

                                            <?php endwhile; ?>

                                        </select>

                                        <input
                                            type="text"
                                            name="search"
                                            value="<?= htmlspecialchars($search); ?>"
                                            class="form-control mr-2"
                                            placeholder="Search...">

                                        <button
                                            type="submit"
                                            class="btn btn-primary">
                                            Search
                                        </button>

                                    </div>

                                </div>

                            </form>

                            <!-- TABLE -->
                            <form method="POST" id="formHapusTerpilih">
                                <input
                                    type="hidden"
                                    name="hapus_terpilih"
                                    value="1">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>No</th>
                                                <th>Nama Kategori</th>
                                                <th>Nama Sub-Kategori</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">

                                            <?php
                                            $no = $start + 1;

                                            while ($row = mysqli_fetch_assoc($querySubcategory)) :
                                            ?>

                                                <tr>
                                                    <td>
                                                        <input
                                                            type="checkbox"
                                                            class="rowCheck"
                                                            name="selected_data[]"
                                                            value="<?= $row['id']; ?>">
                                                    </td>

                                                    <td><?= $no++; ?></td>

                                                    <td><?= htmlspecialchars($row['name_category']); ?></td>

                                                    <td><?= htmlspecialchars($row['name_subcategory']); ?></td>

                                                    <td><?= htmlspecialchars($row['desc_category']); ?></td>

                                                    <td>
                                                        <button
                                                            type="button"
                                                            class="btn btn-warning btn-sm"
                                                            onclick="editData(
                    '<?= $row['id']; ?>',
                    '<?= $row['id_postcategory']; ?>',
                    '<?= htmlspecialchars($row['name_subcategory'], ENT_QUOTES); ?>',
                    '<?= htmlspecialchars($row['desc_category'], ENT_QUOTES); ?>'
                )">
                                                            Edit
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="confirmHapus('<?= $row['id']; ?>')">
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
                                        class="btn btn-danger"
                                        id="deleteSelected">
                                        Hapus Terpilih
                                    </button>
                                    <ul class="pagination">

                                        <!-- PREV -->
                                        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">

                                            <a
                                                class="page-link"
                                                href="?page=<?= $page - 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>&filter_category=<?= $filterCategory; ?>">
                                                Prev
                                            </a>

                                        </li>

                                        <?php

                                        $maxVisible = 5;

                                        $startPage = max(1, $page - 2);

                                        $endPage = min($totalPages, $page + 2);

                                        if ($page <= 3) {

                                            $startPage = 1;

                                            $endPage = min($totalPages, $maxVisible);
                                        }

                                        if ($page >= $totalPages - 2) {

                                            $startPage = max(1, $totalPages - ($maxVisible - 1));

                                            $endPage = $totalPages;
                                        }

                                        // FIRST PAGE
                                        if ($startPage > 1) {

                                            echo '
        <li class="page-item">
            <a class="page-link"
                href="?page=1&show=' . $show . '&search=' . urlencode($search) . '&filter_category=' . $filterCategory . '">
                1
            </a>
        </li>
        ';

                                            if ($startPage > 2) {

                                                echo '
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            ';
                                            }
                                        }

                                        // PAGE LOOP
                                        for ($i = $startPage; $i <= $endPage; $i++) :
                                        ?>

                                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">

                                                <a
                                                    class="page-link"
                                                    href="?page=<?= $i; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>&filter_category=<?= $filterCategory; ?>">

                                                    <?= $i; ?>

                                                </a>

                                            </li>

                                        <?php endfor; ?>

                                        <?php

                                        // LAST PAGE
                                        if ($endPage < $totalPages) {

                                            if ($endPage < $totalPages - 1) {

                                                echo '
            <li class="page-item disabled">
                <span class="page-link">...</span>
            </li>
            ';
                                            }

                                            echo '
        <li class="page-item">
            <a class="page-link"
                href="?page=' . $totalPages . '&show=' . $show . '&search=' . urlencode($search) . '&filter_category=' . $filterCategory . '">
                ' . $totalPages . '
            </a>
        </li>
        ';
                                        }

                                        ?>

                                        <!-- NEXT -->
                                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">

                                            <a
                                                class="page-link"
                                                href="?page=<?= $page + 1; ?>&show=<?= $show; ?>&search=<?= urlencode($search); ?>&filter_category=<?= $filterCategory; ?>">

                                                Next

                                            </a>

                                        </li>

                                    </ul>
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

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Data</h5>
                </div>

                <form method="POST">
                    <div class="modal-body">
                        <label>Pilih Kategori</label>
                        <select name="id_postcategory" class="form-control mb-3" name="id_postcategory">

                            <option value="">-- Pilih Kategori --</option>

                            <?php
                            mysqli_data_seek($queryCategory, 0);

                            while ($category = mysqli_fetch_assoc($queryCategory)) :
                            ?>

                                <option value="<?= $category['id']; ?>">
                                    <?= htmlspecialchars($category['name_category']); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                        <label>Tambah Sub-Kategori</label>
                        <input type="text" name="name_subcategory" class="form-control mb-3" placeholder="Nama Kategori Postingan">

                        <label>Tambah Deskripsi</label>
                        <textarea type="text" name="desc_category" class="form-control" placeholder="Deskripsi Kategori Postingan"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="submit"
                            name="tambah_subcategory"
                            class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT DISINI -->
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST">

                    <div class="modal-header">
                        <h5>Edit Data</h5>
                    </div>

                    <div class="modal-body">

                        <input
                            type="hidden"
                            name="edit_id"
                            id="edit_id">

                        <label>Pilih Kategori</label>

                        <select
                            name="edit_id_postcategory"
                            id="kategoriEdit"
                            class="form-control mb-3">

                            <option value="">-- Pilih Kategori --</option>

                            <?php
                            mysqli_data_seek($queryCategory, 0);

                            while ($category = mysqli_fetch_assoc($queryCategory)) :
                            ?>

                                <option value="<?= $category['id']; ?>">
                                    <?= htmlspecialchars($category['name_category']); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                        <label>Edit Sub-Kategori</label>

                        <input
                            type="text"
                            name="edit_name_subcategory"
                            id="subkategoriEdit"
                            class="form-control mb-3"
                            placeholder="Nama Sub-Kategori">

                        <label>Edit Deskripsi</label>

                        <textarea
                            name="edit_desc_category"
                            id="deskripsiEdit"
                            class="form-control"
                            placeholder="Deskripsi"></textarea>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="submit"
                            name="update_subcategory"
                            class="btn btn-success">
                            Update
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- MODAL VALIDASI -->
    <div class="modal fade" id="modalValidasi">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Peringatan</h5>
                </div>

                <div class="modal-body">
                    Pilih dulu sebelum hapus!
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL HAPUS SATU -->
    <div class="modal fade" id="modalHapus">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Konfirmasi Hapus</h5>
                </div>

                <div class="modal-body">
                    Yakin ingin hapus data ini?
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Konfirmasi Hapus</h5>
                </div>

                <div class="modal-body">
                    Yakin ingin hapus data terpilih?
                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Batal
                    </button>


                    <button
                        type="button"
                        id="confirmDeleteSelected"
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
        /*
    |--------------------------------------------------------------------------
    | EDIT DATA
    |--------------------------------------------------------------------------
    */
        function editData(id, kategori, subkategori, deskripsi) {

            document.getElementById('edit_id').value = id;

            document.getElementById('kategoriEdit').value = kategori;

            document.getElementById('subkategoriEdit').value = subkategori;

            document.getElementById('deskripsiEdit').value = deskripsi;

            $('#modalEdit').modal('show');
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK ALL
        |--------------------------------------------------------------------------
        */
        $('#checkAll').on('click', function() {

            $('.rowCheck').prop('checked', this.checked);
        });

        /*
        |--------------------------------------------------------------------------
        | AUTO UNCHECK CHECKALL
        |--------------------------------------------------------------------------
        */
        $(document).on('click', '.rowCheck', function() {

            if ($('.rowCheck:checked').length === $('.rowCheck').length) {

                $('#checkAll').prop('checked', true);

            } else {

                $('#checkAll').prop('checked', false);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | HAPUS SATU
        |--------------------------------------------------------------------------
        */
        function confirmHapus(id) {

            $('#btnConfirmHapus').attr(
                'href',
                'manage_post_subcategory.php?hapus=' + id
            );

            $('#modalHapus').modal('show');
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS TERPILIH
        |--------------------------------------------------------------------------
        */
        $('#deleteSelected').on('click', function() {

            let totalChecked = $('.rowCheck:checked').length;

            if (totalChecked === 0) {

                $('#modalValidasi').modal('show');

            } else {

                $('#modalHapusTerpilih').modal('show');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | CONFIRM DELETE SELECTED
        |--------------------------------------------------------------------------
        */
        $('#confirmDeleteSelected').on('click', function() {

            $('#formHapusTerpilih').submit();
        });
    </script>
</body>

</html>