<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/* =========================================
   TAMBAH DATA CLIENT
========================================= */
if (isset($_POST['save_client'])) {

    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_desc = mysqli_real_escape_string($conn, $_POST['client_desc']);

    /* =========================================
       VALIDASI
    ========================================= */
    if (
        empty($client_name) ||
        empty($client_desc) ||
        empty($_FILES['client_pic']['name'])
    ) {

        $_SESSION['error'] = "Semua field wajib diisi!";
    } else {

        /* =========================================
           UPLOAD GAMBAR
        ========================================= */
        $uploadDir = __DIR__ . "/../assets/images/uploads/our_clients/";

        // BUAT FOLDER JIKA BELUM ADA
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['client_pic']['name']);
        $targetFile = $uploadDir . $fileName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($imageFileType, $allowedTypes)) {

            $_SESSION['error'] = "Format gambar harus JPG, JPEG, PNG atau WEBP!";
        } else {

            if (move_uploaded_file($_FILES['client_pic']['tmp_name'], $targetFile)) {

                /* =========================================
                   INSERT DATABASE
                ========================================= */
                $insertClient = mysqli_query($conn, "
                    INSERT INTO list_clients (
                        client_name,
                        client_pic,
                        client_desc,
                        created_at
                    ) VALUES (
                        '$client_name',
                        '$fileName',
                        '$client_desc',
                        NOW()
                    )
                ");

                if ($insertClient) {

                    $_SESSION['success'] = "Data klien berhasil ditambahkan!";
                } else {

                    $_SESSION['error'] = "Gagal menyimpan data: " . mysqli_error($conn);
                }
            } else {

                $_SESSION['error'] = "Upload gambar gagal!";
            }
        }
    }

    header("Location: manage_our_clients.php");
    exit;
}

/* =========================================
   EDIT DATA CLIENT
========================================= */
if (isset($_POST['edit_client'])) {

    $id_client   = (int) $_POST['id_client'];
    $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
    $client_desc = mysqli_real_escape_string($conn, $_POST['client_desc']);

    // AMBIL DATA LAMA
    $getOldData = mysqli_query($conn, "
        SELECT client_pic
        FROM list_clients
        WHERE id = '$id_client'
    ");

    $oldData = mysqli_fetch_assoc($getOldData);

    $client_pic = $oldData['client_pic'];

    /* =========================================
       UPLOAD GAMBAR BARU
    ========================================= */
    if (!empty($_FILES['client_pic']['name'])) {

        $uploadDir = __DIR__ . "/../assets/images/uploads/our_clients/";

        $fileName = time() . '_' . basename($_FILES['client_pic']['name']);
        $targetFile = $uploadDir . $fileName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($imageFileType, $allowedTypes)) {

            $_SESSION['error'] = "Format gambar harus JPG, JPEG, PNG atau WEBP!";

            header("Location: manage_our_clients.php");
            exit;
        }

        if (move_uploaded_file($_FILES['client_pic']['tmp_name'], $targetFile)) {

            // HAPUS GAMBAR LAMA
            if (
                !empty($oldData['client_pic']) &&
                file_exists($uploadDir . $oldData['client_pic'])
            ) {
                unlink($uploadDir . $oldData['client_pic']);
            }

            $client_pic = $fileName;
        }
    }

    /* =========================================
       UPDATE DATABASE
    ========================================= */
    $updateClient = mysqli_query($conn, "
        UPDATE list_clients
        SET
            client_name = '$client_name',
            client_pic  = '$client_pic',
            client_desc = '$client_desc'
        WHERE id = '$id_client'
    ");

    if ($updateClient) {

        $_SESSION['success'] = "Data klien berhasil diupdate!";
    } else {

        $_SESSION['error'] = "Gagal update data: " . mysqli_error($conn);
    }

    header("Location: manage_our_clients.php");
    exit;
}


/* =========================================
   HAPUS DATA CLIENT
========================================= */
if (isset($_GET['delete'])) {

    $id_client = (int) $_GET['delete'];

    // AMBIL DATA GAMBAR
    $getClient = mysqli_query($conn, "
        SELECT client_pic
        FROM list_clients
        WHERE id = '$id_client'
    ");

    $clientData = mysqli_fetch_assoc($getClient);

    // HAPUS FILE GAMBAR
    $filePath = __DIR__ . "/../assets/images/uploads/our_clients/" . $clientData['client_pic'];

    if (
        !empty($clientData['client_pic']) &&
        file_exists($filePath)
    ) {
        unlink($filePath);
    }

    // HAPUS DATABASE
    $deleteClient = mysqli_query($conn, "
        DELETE FROM list_clients
        WHERE id = '$id_client'
    ");

    if ($deleteClient) {

        $_SESSION['success'] = "Data klien berhasil dihapus!";
    } else {

        $_SESSION['error'] = "Gagal hapus data!";
    }

    header("Location: manage_our_clients.php");
    exit;
}

/* =========================================
   SHOW ENTRIES
========================================= */
$show = isset($_GET['show']) ? (int) $_GET['show'] : 10;

$allowedShow = [5, 10, 25, 50, 100];

if (!in_array($show, $allowedShow)) {
    $show = 10;
}

/* =========================================
   PAGINATION
========================================= */
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $show;

/* =========================================
   TOTAL DATA
========================================= */
$totalDataQuery = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM list_clients
");

$totalData = mysqli_fetch_assoc($totalDataQuery)['total'];

$totalPages = ceil($totalData / $show);

/* =========================================
   GET DATA CLIENTS
========================================= */
$queryClients = mysqli_query($conn, "
    SELECT 
        id,
        client_name,
        client_pic,
        client_desc,
        created_at
    FROM list_clients
    ORDER BY id DESC
    LIMIT $start, $show
");

if (!$queryClients) {
    die('Query Error: ' . mysqli_error($conn));
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
    <title>Manage Klien Kami - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Klien Kami
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Klien Kami</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">

                    <form method="POST" enctype="multipart/form-data">
                        <!-- FORM -->
                        <div class="card p-4 mb-4" style="border-radius:12px;">

                            <h5 class="mb-3 d-flex align-items-center">
                                <span class="material-icons mr-2" style="color: var(--primary);">
                                    image
                                </span>
                                Form Data Gambar Klien
                            </h5>

                            <div class="row">

                                <!-- Nama Klien -->
                                <div class="col-md-12 mb-3">
                                    <label>Nama Klien</label>

                                    <input
                                        type="text"
                                        name="client_name"
                                        class="form-control"
                                        placeholder="Masukkan nama klien"
                                        required>
                                </div>

                                <!-- Upload Gambar -->
                                <div class="col-md-12 mb-3">

                                    <label>Upload Gambar</label>

                                    <div class="border p-4 text-center"
                                        style="border-radius:10px; border-style:dashed;">

                                        <span class="material-icons"
                                            style="font-size:40px; color: var(--gray);">
                                            cloud_upload
                                        </span>

                                        <p class="mb-2">Klik untuk upload gambar</p>

                                        <input
                                            type="file"
                                            name="client_pic"
                                            class="form-control-file"
                                            accept=".jpg,.jpeg,.png,.webp"
                                            required>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-md-12 mb-3">

                                    <label>Deskripsi Gambar</label>

                                    <textarea
                                        class="form-control"
                                        name="client_desc"
                                        rows="3"
                                        placeholder="Masukkan deskripsi gambar"
                                        required></textarea>
                                </div>

                                <!-- BUTTON -->
                                <div class="col-md-12">

                                    <button
                                        type="submit"
                                        name="save_client"
                                        class="btn"
                                        style="background: var(--primary); color:#fff;">

                                        <span class="material-icons" style="font-size:18px;">
                                            save
                                        </span>

                                        Simpan
                                    </button>

                                </div>

                            </div>
                        </div>
                    </form>

                    <!-- NOTIFIKASI -->
                    <?php if (isset($_SESSION['success'])) : ?>

                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong>
                            <?= $_SESSION['success']; ?>

                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>

                        <?php unset($_SESSION['success']); ?>

                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])) : ?>

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong>
                            <?= $_SESSION['error']; ?>

                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>

                        <?php unset($_SESSION['error']); ?>

                    <?php endif; ?>


                    <!-- TABLE -->
                    <div class="card p-4" style="border-radius:12px;">

                        <h6 class="mb-3">List Data Gambar</h6>

                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                            <!-- SHOW ENTRIES -->
                            <div class="d-flex align-items-center">

                                <span class="mr-2">Show</span>

                                <select
                                    class="form-control"
                                    style="width:90px;"
                                    onchange="window.location.href='?show=' + this.value">

                                    <option value="5" <?= $show == 5 ? 'selected' : ''; ?>>
                                        5
                                    </option>

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

                                <span class="ml-2">entries</span>

                            </div>

                            <!-- TOTAL DATA -->
                            <div class="text-muted mt-2 mt-md-0">

                                Total Data:
                                <strong><?= $totalData; ?></strong>

                            </div>

                        </div>

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Nama Klien</th>
                                        <th width="140">Gambar</th>
                                        <th>Deskripsi</th>
                                        <th width="180">Tanggal Upload</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="tableBody">

                                    <?php if (mysqli_num_rows($queryClients) > 0) : ?>

                                        <?php $no = 1; ?>
                                        <?php while ($client = mysqli_fetch_assoc($queryClients)) : ?>

                                            <tr>

                                                <!-- NO -->
                                                <td>
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- NAMA CLIENT -->
                                                <td>
                                                    <?= htmlspecialchars($client['client_name']); ?>
                                                </td>

                                                <!-- GAMBAR -->
                                                <td>

                                                    <img
                                                        src="../assets/images/uploads/our_clients/<?= htmlspecialchars($client['client_pic']); ?>"
                                                        width="80"
                                                        height="80"
                                                        style="object-fit:cover; border-radius:10px; border:1px solid #ddd;">

                                                </td>

                                                <!-- DESKRIPSI -->
                                                <td style="max-width:300px; white-space:normal;">
                                                    <?= nl2br(htmlspecialchars($client['client_desc'])); ?>
                                                </td>

                                                <!-- TANGGAL -->
                                                <td>

                                                    <?= date('d F Y H:i', strtotime($client['created_at'])); ?>

                                                </td>

                                                <!-- AKSI -->
                                                <td>

                                                    <!-- EDIT -->
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm mr-1"
                                                        style="background: var(--primary); color:#fff;"
                                                        data-toggle="collapse"
                                                        data-target="#editClient<?= $client['id']; ?>">

                                                        <span class="material-icons" style="font-size:16px;">
                                                            edit
                                                        </span>

                                                    </button>

                                                    <!-- HAPUS -->
                                                    <a
                                                        href="?delete=<?= $client['id']; ?>"
                                                        class="btn btn-sm"
                                                        style="background: var(--danger); color:#fff;"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')">

                                                        <span class="material-icons" style="font-size:16px;">
                                                            delete
                                                        </span>

                                                    </a>

                                                </td>

                                            </tr>

                                            <!-- FORM EDIT -->
                                            <tr class="collapse" id="editClient<?= $client['id']; ?>">

                                                <td colspan="6">

                                                    <div class="card p-4 my-3" style="background:#f9fafc; border-radius:12px;">

                                                        <h6 class="mb-4 d-flex align-items-center">

                                                            <span class="material-icons mr-2" style="color:var(--primary);">
                                                                edit
                                                            </span>

                                                            Edit Data Klien

                                                        </h6>

                                                        <form method="POST" enctype="multipart/form-data">

                                                            <input
                                                                type="hidden"
                                                                name="id_client"
                                                                value="<?= $client['id']; ?>">

                                                            <div class="row">

                                                                <!-- NAMA -->
                                                                <div class="col-md-12 mb-3">

                                                                    <label>Nama Klien</label>

                                                                    <input
                                                                        type="text"
                                                                        name="client_name"
                                                                        class="form-control"
                                                                        value="<?= htmlspecialchars($client['client_name']); ?>"
                                                                        required>

                                                                </div>

                                                                <!-- PREVIEW -->
                                                                <div class="col-md-12 mb-3">

                                                                    <label>Gambar Lama</label>

                                                                    <div class="mb-3">

                                                                        <img
                                                                            src="../assets/images/uploads/our_clients/<?= htmlspecialchars($client['client_pic']); ?>"
                                                                            width="120"
                                                                            height="120"
                                                                            style="object-fit:cover; border-radius:12px; border:1px solid #ddd;">

                                                                    </div>

                                                                    <label>Ganti Gambar</label>

                                                                    <input
                                                                        type="file"
                                                                        name="client_pic"
                                                                        class="form-control-file"
                                                                        accept=".jpg,.jpeg,.png,.webp">

                                                                </div>

                                                                <!-- DESKRIPSI -->
                                                                <div class="col-md-12 mb-3">

                                                                    <label>Deskripsi</label>

                                                                    <textarea
                                                                        name="client_desc"
                                                                        class="form-control"
                                                                        rows="4"
                                                                        required><?= htmlspecialchars($client['client_desc']); ?></textarea>

                                                                </div>

                                                                <!-- BUTTON -->
                                                                <div class="col-md-12">

                                                                    <button
                                                                        type="submit"
                                                                        name="edit_client"
                                                                        class="btn"
                                                                        style="background:var(--primary); color:#fff;">

                                                                        <span class="material-icons" style="font-size:16px;">
                                                                            save
                                                                        </span>

                                                                        Simpan Perubahan

                                                                    </button>

                                                                </div>

                                                            </div>

                                                        </form>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <tr>

                                            <td colspan="6" class="text-center text-muted py-4">
                                                Belum ada data klien
                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                            <!-- PAGINATION -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">

                                <!-- INFO -->
                                <div class="text-muted mb-2">

                                    Showing
                                    <?= $start + 1; ?>
                                    to
                                    <?= min($start + $show, $totalData); ?>
                                    of
                                    <?= $totalData; ?> entries

                                </div>

                                <!-- PAGINATION -->
                                <nav>

                                    <ul class="pagination pagination-sm mb-0">

                                        <!-- PREVIOUS -->
                                        <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">

                                            <a
                                                class="page-link"
                                                href="?page=<?= $page - 1; ?>&show=<?= $show; ?>">

                                                Previous

                                            </a>

                                        </li>

                                        <?php
                                        $visiblePages = 5;

                                        if ($totalPages <= $visiblePages + 2) {

                                            for ($i = 1; $i <= $totalPages; $i++) :
                                        ?>

                                                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

                                                    <a
                                                        class="page-link"
                                                        href="?page=<?= $i; ?>&show=<?= $show; ?>">

                                                        <?= $i; ?>

                                                    </a>

                                                </li>

                                            <?php
                                            endfor;
                                        } else {

                                            // AWAL
                                            for ($i = 1; $i <= 5; $i++) :
                                            ?>

                                                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

                                                    <a
                                                        class="page-link"
                                                        href="?page=<?= $i; ?>&show=<?= $show; ?>">

                                                        <?= $i; ?>

                                                    </a>

                                                </li>

                                            <?php endfor; ?>

                                            <!-- TITIK -->
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>

                                            <?php
                                            // AKHIR
                                            for ($i = $totalPages - 2; $i <= $totalPages; $i++) :

                                                if ($i > 5) :
                                            ?>

                                                    <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

                                                        <a
                                                            class="page-link"
                                                            href="?page=<?= $i; ?>&show=<?= $show; ?>">

                                                            <?= $i; ?>

                                                        </a>

                                                    </li>

                                        <?php
                                                endif;

                                            endfor;
                                        }
                                        ?>

                                        <!-- NEXT -->
                                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">

                                            <a
                                                class="page-link"
                                                href="?page=<?= $page + 1; ?>&show=<?= $show; ?>">

                                                Next

                                            </a>

                                        </li>

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

</body>

</html>