<?php
session_start();

require_once __DIR__ . "/../koneksi.php";

date_default_timezone_set('Asia/Jakarta');

/* =========================================
   TAMBAH GALLERY
========================================= */
if (isset($_POST['save_gallery'])) {

    $name_gallery = trim($_POST['name_gallery']);

    // validasi nama
    if (empty($name_gallery)) {
        echo "<script>alert('Nama galeri wajib diisi!');</script>";
    } else {

        // folder upload
        $uploadDir = __DIR__ . "/../assets/images/uploads/gallery/";

        // buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // cek ada file
        if (!empty($_FILES['picture']['name'][0])) {

            foreach ($_FILES['picture']['tmp_name'] as $key => $tmpName) {

                $fileName = $_FILES['picture']['name'][$key];
                $fileSize = $_FILES['picture']['size'][$key];
                $fileError = $_FILES['picture']['error'][$key];

                // extension
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // valid extension
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (!in_array($extension, $allowed)) {
                    continue;
                }

                // max 5MB
                if ($fileSize > 5 * 1024 * 1024) {
                    continue;
                }

                // cek error upload
                if ($fileError !== 0) {
                    continue;
                }

                // rename file
                $newFileName = time() . '_' . rand(1000, 9999) . '.' . $extension;

                // upload path
                $uploadFile = $uploadDir . $newFileName;

                // upload file
                if (move_uploaded_file($tmpName, $uploadFile)) {

                    // insert database
                    $insertGallery = mysqli_query($conn, "
                        INSERT INTO gallery (
                            name_gallery,
                            picture,
                            created_at
                        ) VALUES (
                            '" . mysqli_real_escape_string($conn, $name_gallery) . "',
                            '" . mysqli_real_escape_string($conn, $newFileName) . "',
                            NOW()
                        )
                    ");

                    if (!$insertGallery) {
                        die("Insert Error: " . mysqli_error($conn));
                    }
                }
            }

            echo "
            <script>
                alert('Galeri berhasil disimpan!');
                window.location='manage_gallery.php';
            </script>
            ";
        } else {

            echo "
            <script>
                alert('Upload gambar terlebih dahulu!');
            </script>
            ";
        }
    }
}

/* =========================================
   DELETE GALLERY
========================================= */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    // ambil gambar
    $getGallery = mysqli_query($conn, "
        SELECT picture 
        FROM gallery 
        WHERE id = '$id'
    ");

    $dataGallery = mysqli_fetch_assoc($getGallery);

    // hapus file gambar
    if ($dataGallery) {

        $filePath = __DIR__ . "/../assets/images/uploads/gallery/" . $dataGallery['picture'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // hapus database
    $deleteGallery = mysqli_query($conn, "
        DELETE FROM gallery
        WHERE id = '$id'
    ");

    if ($deleteGallery) {

        echo "
        <script>
            alert('Galeri berhasil dihapus!');
            window.location='manage_gallery.php';
        </script>
        ";

        exit;
    } else {

        die('Delete Error: ' . mysqli_error($conn));
    }
}

/* =========================================
   AMBIL DATA GALLERY
========================================= */
$queryGallery = mysqli_query($conn, "
    SELECT 
        id,
        name_gallery,
        picture,
        created_at
    FROM gallery
    ORDER BY id DESC
");

if (!$queryGallery) {
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
    <title>Manage Galeri - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Galeri
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Galeri</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">

                    <!-- FORM TAMBAH GALERI -->
                    <form method="POST" enctype="multipart/form-data">

                        <div class="card p-4 mb-4" style="border-radius:12px;">
                            <h6 class="mb-3">Tambah Galeri</h6>

                            <!-- NAMA GALERI -->
                            <div class="form-group">
                                <label>Nama Galeri <span style="color:red">*</span></label>

                                <input
                                    type="text"
                                    name="name_gallery"
                                    class="form-control"
                                    placeholder="Contoh: Kegiatan Sekolah 2024"
                                    required>
                            </div>

                            <!-- UPLOAD -->
                            <div class="form-group">
                                <label>Upload Gambar <span style="color:red">*</span></label>

                                <div id="dropArea" style="
                border:2px dashed #dbe5ee;
                border-radius:10px;
                padding:40px;
                text-align:center;
                background:#fafbfe;
                cursor:pointer;
            ">
                                    <span class="material-icons" style="font-size:40px; color:#939fad;">
                                        cloud_upload
                                    </span>

                                    <p class="mt-2 mb-1">
                                        Klik atau drag & drop gambar di sini
                                    </p>

                                    <small class="text-muted">
                                        Format: JPG, PNG, WEBP (Max 5MB)
                                    </small>

                                    <input
                                        type="file"
                                        id="fileInput"
                                        name="picture[]"
                                        multiple
                                        hidden
                                        accept=".jpg,.jpeg,.png,.webp"
                                        required>
                                </div>

                                <!-- PREVIEW -->
                                <div id="preview" class="mt-2"></div>
                            </div>

                            <!-- BUTTON -->
                            <div class="text-right">
                                <button type="submit" name="save_gallery" class="btn btn-primary">

                                    <span class="material-icons"
                                        style="font-size:16px; vertical-align:middle;">
                                        save
                                    </span>

                                    Simpan Galeri
                                </button>
                            </div>
                        </div>

                    </form>

                    <!-- NOTIF DISINI -->

                    <!-- LIST GALERI -->
                    <div class="card p-4" style="border-radius:12px;">
                        <h6 class="mb-3">List Galeri</h6>

                        <!-- TOP CONTROL -->
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                Show
                                <select id="entriesSelect" class="form-control d-inline-block" style="width:70px;">
                                    <option>10</option>
                                    <option>25</option>
                                </select> entries
                            </div>

                            <div>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..." style="width:200px;">
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Galeri</th>
                                        <th>View Gambar</th>
                                        <th>Dibuat Pada</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">

                                    <?php
                                    $no = 1;

                                    while ($row = mysqli_fetch_assoc($queryGallery)) :
                                    ?>

                                        <tr>

                                            <!-- NO -->
                                            <td class="align-middle text-center" style="width:70px;">
                                                <?= $no++; ?>
                                            </td>

                                            <!-- NAMA -->
                                            <td class="align-middle">
                                                <?= htmlspecialchars($row['name_gallery']); ?>
                                            </td>

                                            <!-- GAMBAR -->
                                            <td class="align-middle text-center" style="width:160px;">

                                                <img
                                                    src="../assets/images/uploads/gallery/<?= htmlspecialchars($row['picture']); ?>"
                                                    style="
                width:80px;
                height:80px;
                object-fit:cover;
                border-radius:10px;
                border:1px solid #dee2e6;
            ">

                                            </td>

                                            <!-- TANGGAL -->
                                            <td class="align-middle" style="width:200px;">
                                                <?= date('d M Y H:i', strtotime($row['created_at'])); ?>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="align-middle text-center" style="width:120px;">

                                                <a
                                                    href="manage_gallery.php?delete=<?= $row['id']; ?>"
                                                    class="btn btn-sm btn-light text-danger"
                                                    onclick="return confirm('Yakin ingin menghapus galeri ini?')">

                                                    <span class="material-icons" style="font-size:18px;">
                                                        delete
                                                    </span>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small id="infoText">Showing 1 to 1 of 1 entries</small>

                            <div id="pagination"></div>
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

    <!-- =========================
    MODAL SUKSES SIMPAN GALERI
========================= -->

    <div class="modal fade" id="modalSuksesGaleri" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content border-0"
                style="
                border-radius:18px;
                overflow:hidden;
            ">

                <div class="modal-body text-center p-5">

                    <!-- ICON -->
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                        style="
                        width:90px;
                        height:90px;
                        border-radius:50%;
                        background:#ecfdf3;
                    ">

                        <span class="material-icons"
                            style="
                            font-size:50px;
                            color:#16a34a;
                        ">
                            check_circle
                        </span>

                    </div>

                    <!-- TITLE -->
                    <h4 class="font-weight-bold mb-2">
                        Simpan Berhasil
                    </h4>

                    <!-- TEXT -->
                    <p class="text-muted mb-4" id="textGaleriBerhasil">
                        Galeri berhasil disimpan
                    </p>

                    <!-- BUTTON -->
                    <button type="button"
                        class="btn btn-success px-4"
                        data-dismiss="modal"
                        style="
                        min-width:120px;
                        height:45px;
                        border-radius:10px;
                    ">
                        Okay
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
        // klik area upload
        document.getElementById("dropArea").addEventListener("click", () => {
            document.getElementById("fileInput").click();
        });

        // drag & drop
        const dropArea = document.getElementById("dropArea");

        dropArea.addEventListener("dragover", (e) => {

            e.preventDefault();

            dropArea.style.background = "#eef2ff";
        });

        dropArea.addEventListener("dragleave", () => {

            dropArea.style.background = "#fafbfe";
        });

        dropArea.addEventListener("drop", (e) => {

            e.preventDefault();

            dropArea.style.background = "#fafbfe";

            // masukkan file ke input
            document.getElementById("fileInput").files = e.dataTransfer.files;

            renderPreview(e.dataTransfer.files);
        });

        // ketika pilih file
        document.getElementById("fileInput").addEventListener("change", function() {

            renderPreview(this.files);
        });

        // render preview gambar
        function renderPreview(files) {

            const preview = document.getElementById("preview");

            preview.innerHTML = "";

            Array.from(files).forEach(file => {

                const reader = new FileReader();

                reader.onload = function(e) {

                    preview.innerHTML += `
                <img 
                    src="${e.target.result}" 
                    style="
                        width:80px;
                        height:80px;
                        object-fit:cover;
                        border-radius:10px;
                        margin:5px;
                        border:1px solid #dee2e6;
                    "
                >
            `;
                };

                reader.readAsDataURL(file);
            });
        }
    </script>

    <script>
        let tableData = [];
        let filteredData = [];

        let currentPage = 1;
        let rowsPerPage = 10;

        // ambil data tabel
        function initTableData() {

            const rows = document.querySelectorAll("#tableBody tr");

            tableData = Array.from(rows).map(row => {

                return {
                    html: row.innerHTML,
                    text: row.innerText.toLowerCase()
                };
            });

            filteredData = [...tableData];
        }

        // render tabel
        function renderTable() {

            const tableBody = document.getElementById("tableBody");

            tableBody.innerHTML = "";

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            const pageData = filteredData.slice(start, end);

            pageData.forEach((row, index) => {

                const tr = document.createElement("tr");

                // replace nomor
                const rowHTML = row.html.replace(
                    /<td class="align-middle text-center" style="width:70px;">.*?<\/td>/,
                    `<td class="align-middle text-center" style="width:70px;">
                ${start + index + 1}
            </td>`
                );

                tr.innerHTML = rowHTML;

                tableBody.appendChild(tr);
            });

            renderPagination();
            updateInfoText();
        }

        // pagination style:
        // 1 2 3 4 5 ... 20 21 22
        function renderPagination() {

            const totalPages = Math.ceil(filteredData.length / rowsPerPage);

            const pagination = document.getElementById("pagination");

            pagination.innerHTML = "";

            if (totalPages <= 1) return;

            // PREV
            pagination.innerHTML += `
        <button 
            class="btn btn-sm btn-light mr-1"
            ${currentPage === 1 ? 'disabled' : ''}
            onclick="changePage(${currentPage - 1})"
        >
            Previous
        </button>
    `;

            let pages = [];

            // awal
            for (let i = 1; i <= Math.min(5, totalPages); i++) {
                pages.push(i);
            }

            // titik
            if (totalPages > 8) {
                pages.push("...");
            }

            // akhir
            let startLast = Math.max(6, totalPages - 2);

            for (let i = startLast; i <= totalPages; i++) {
                if (!pages.includes(i)) {
                    pages.push(i);
                }
            }

            // render
            pages.forEach(page => {

                if (page === "...") {

                    pagination.innerHTML += `
                <span class="mx-1">...</span>
            `;

                } else {

                    pagination.innerHTML += `
                <button
                    class="btn btn-sm mr-1 ${
                        currentPage === page
                        ? 'btn-primary'
                        : 'btn-light'
                    }"
                    onclick="changePage(${page})"
                >
                    ${page}
                </button>
            `;
                }
            });

            // NEXT
            pagination.innerHTML += `
        <button 
            class="btn btn-sm btn-light ml-1"
            ${currentPage === totalPages ? 'disabled' : ''}
            onclick="changePage(${currentPage + 1})"
        >
            Next
        </button>
    `;
        }

        // pindah halaman
        function changePage(page) {

            const totalPages = Math.ceil(filteredData.length / rowsPerPage);

            if (page < 1 || page > totalPages) return;

            currentPage = page;

            renderTable();
        }

        // show entries
        document.getElementById("entriesSelect")
            .addEventListener("change", function() {

                rowsPerPage = parseInt(this.value);

                currentPage = 1;

                renderTable();
            });

        // search
        document.getElementById("searchInput")
            .addEventListener("keyup", function() {

                const keyword = this.value.toLowerCase();

                filteredData = tableData.filter(row =>
                    row.text.includes(keyword)
                );

                currentPage = 1;

                renderTable();
            });

        // info text
        function updateInfoText() {

            const infoText = document.getElementById("infoText");

            const start =
                filteredData.length === 0 ?
                0 :
                ((currentPage - 1) * rowsPerPage) + 1;

            const end = Math.min(
                currentPage * rowsPerPage,
                filteredData.length
            );

            infoText.innerText =
                `Showing ${start} to ${end} of ${filteredData.length} entries`;
        }

        // first load
        initTableData();
        renderTable();
    </script>
</body>

</html>