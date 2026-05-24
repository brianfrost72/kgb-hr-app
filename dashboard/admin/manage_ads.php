<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

// =========================
// HAPUS IKLAN
// =========================
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    // ambil data gambar
    $getData = mysqli_query($conn, "
        SELECT *
        FROM manage_ads
        WHERE id = '$id'
    ");

    $ads = mysqli_fetch_assoc($getData);

    if ($ads) {

        // path gambar
        $imagePath = "../assets/images/uploads/ads/" . $ads['ad_img'];

        // hapus file jika ada
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // hapus database
        mysqli_query($conn, "
            DELETE FROM manage_ads
            WHERE id = '$id'
        ");
    }

    echo "
    <script>
        window.location='manage_ads.php';
    </script>
    ";
}

// TAMBAH

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ad_title = trim($_POST['ad_title']);
    $ad_link  = trim($_POST['ad_link']);

    if (
        empty($ad_title) ||
        empty($ad_link) ||
        empty($_FILES['ad_img']['name'])
    ) {

        echo "
        <script>
            alert('Lengkapi semua data!');
        </script>
        ";
    } else {

        // =========================
        // FOLDER UPLOAD
        // =========================
        $uploadDir = "../assets/images/uploads/ads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // =========================
        // FILE
        // =========================
        $fileTmp  = $_FILES['ad_img']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['ad_img']['name']);

        // path fisik upload
        $targetFile = $uploadDir . $fileName;

        // ekstensi
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {

            echo "
            <script>
                alert('Format gambar tidak didukung!');
            </script>
            ";
        } else {

            // upload file
            if (move_uploaded_file($fileTmp, $targetFile)) {

                // simpan nama file saja
                $ad_img = $fileName;

                $stmt = mysqli_prepare($conn, "
                    INSERT INTO manage_ads
                    (
                        ad_title,
                        ad_img,
                        ad_link
                    )
                    VALUES
                    (
                        ?, ?, ?
                    )
                ");

                mysqli_stmt_bind_param(
                    $stmt,
                    "sss",
                    $ad_title,
                    $ad_img,
                    $ad_link
                );

                $save = mysqli_stmt_execute($stmt);

                if ($save) {

                    echo "
                    <script>
                        window.location='manage_ads.php';
                    </script>
                    ";
                } else {

                    echo "
                    <script>
                        alert('Gagal simpan database!');
                    </script>
                    ";
                }
            } else {

                echo "
                <script>
                    alert('Upload gambar gagal!');
                </script>
                ";
            }
        }
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
    <title>Manage Iklan - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Iklan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Iklan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container mt-4">

                    <!-- FORM TAMBAH IKLAN -->
                    <form method="POST" enctype="multipart/form-data">

                        <div class="card p-4 mb-4" style="border-radius:12px;">
                            <h6 class="mb-3">Tambah Iklan</h6>

                            <!-- JUDUL IKLAN -->
                            <div class="form-group">
                                <label>Judul Iklan <span style="color:red">*</span></label>
                                <input type="text" name="ad_title" class="form-control" placeholder="Contoh: Iklan Hukum Info">
                            </div>

                            <!-- UPLOAD -->
                            <div class="form-group">
                                <label>Upload Gambar Iklan <span style="color:red">*</span></label>

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
                                    <p class="mt-2 mb-1">Klik atau drag & drop gambar di sini</p>
                                    <small class="text-muted">Format: JPG, PNG, WEBP (Max 5MB)</small>

                                    <input type="file"
                                        name="ad_img" id="fileInput" multiple hidden>
                                </div>

                                <!-- PREVIEW -->
                                <div id="preview" class="mt-2"></div>
                            </div>

                            <!-- LINK IKLAN -->
                            <div class="form-group">
                                <label>Link Iklan <span style="color:red">*</span></label>
                                <input type="text"
                                    name="ad_link" id="linkIklan" class="form-control" placeholder="Contoh: https://example.com">
                            </div>

                            <!-- BUTTON -->
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">
                                    <span class="material-icons" style="font-size:16px; vertical-align:middle;">
                                        save
                                    </span>
                                    Simpan Iklan
                                </button>
                            </div>
                        </div>

                    </form>

                    <!-- LIST Iklan -->
                    <div class="card p-4" style="border-radius:12px;">
                        <h6 class="mb-3">List Iklan</h6>

                        <!-- TOP CONTROL -->
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                Show
                                <select id="entriesSelect" class="form-control d-inline-block" style="width:80px;">

                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>

                                </select>
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
                                        <th>Judul Iklan</th>
                                        <th>Gambar</th>
                                        <th>Link Iklan</th>
                                        <th>Dibuat Pada</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                $queryAds = mysqli_query($conn, "
    SELECT *
    FROM manage_ads
    ORDER BY id DESC
");
                                ?>
                                <tbody id="tableBody">

                                    <?php
                                    $no = 1;

                                    while ($ads = mysqli_fetch_assoc($queryAds)) :
                                    ?>

                                        <tr>

                                            <!-- NO -->
                                            <!-- <td>
                                                <?= $no++; ?>
                                            </td> -->

                                            <!-- JUDUL -->
                                            <td style="min-width:180px;">
                                                <div style="font-weight:600;">
                                                    <?= htmlspecialchars($ads['ad_title']); ?>
                                                </div>
                                            </td>

                                            <!-- GAMBAR -->
                                            <td style="width:120px;">

                                                <img
                                                    src="../assets/images/uploads/ads/<?= htmlspecialchars($ads['ad_img']); ?>"
                                                    width="70"
                                                    height="70"
                                                    style="
            object-fit:cover;
            border-radius:8px;
            border:1px solid #e5e7eb;
        "
                                                    onerror="this.style.border='2px solid red'">

                                            </td>

                                            <!-- LINK -->
                                            <td style="min-width:350px;">

                                                <a
                                                    href="<?= $ads['ad_link']; ?>"
                                                    target="_blank"
                                                    style="
                display:inline-block;
                max-width:320px;
                overflow:hidden;
                text-overflow:ellipsis;
                white-space:nowrap;
            ">

                                                    <?= htmlspecialchars($ads['ad_link']); ?>

                                                </a>

                                            </td>

                                            <!-- TANGGAL -->
                                            <td style="min-width:150px;">
                                                <?= date('d M Y', strtotime($ads['created_at'])); ?>
                                                <br>
                                                <?= date('H:i', strtotime($ads['created_at'])); ?>
                                            </td>

                                            <!-- AKSI -->
                                            <td class="text-center" style="width:90px;">

                                                <a
                                                    href="?delete=<?= $ads['id']; ?>"
                                                    class="btn btn-sm btn-light text-danger"
                                                    onclick="return confirm('Yakin ingin menghapus iklan ini?')">

                                                    <span
                                                        class="material-icons"
                                                        style="font-size:18px;">

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

    <script>
        let filesArray = [];

        // klik upload
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
            handleFiles(e.dataTransfer.files);
        });

        // input file
        document.getElementById("fileInput").addEventListener("change", function() {
            handleFiles(this.files);
        });

        // handle file
        function handleFiles(files) {
            for (let file of files) {
                filesArray.push(file);
            }
            renderPreview();
        }

        // preview
        function renderPreview() {
            const preview = document.getElementById("preview");
            preview.innerHTML = "";

            filesArray.forEach(file => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.width = "60px";
                    img.style.margin = "5px";
                    img.style.borderRadius = "6px";
                    preview.appendChild(img);
                }

                reader.readAsDataURL(file);
            });
        }
    </script>

    <script>
        let tableData = [];
        let filteredData = [];

        let currentPage = 1;
        let rowsPerPage = 10;

        // =========================
        // AMBIL DATA TABEL
        // =========================
        function initTableData() {

            const rows = document.querySelectorAll("#tableBody tr");

            tableData = [];

            rows.forEach(row => {

                tableData.push({
                    html: row.innerHTML,
                    text: row.innerText.toLowerCase()
                });

            });

            filteredData = [...tableData];
        }

        initTableData();

        // =========================
        // RENDER TABLE
        // =========================
        function renderTable() {

            const tableBody = document.getElementById("tableBody");

            tableBody.innerHTML = "";

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            const pageData = filteredData.slice(start, end);

            pageData.forEach((row, index) => {

                const tr = document.createElement("tr");

                tr.innerHTML =
                    `<td>${start + index + 1}</td>` +
                    row.html.replace(/^<td>.*?<\/td>/, '');

                tableBody.appendChild(tr);

            });

            renderPagination();
            updateInfo();

        }

        // =========================
        // PAGINATION
        // =========================
        function renderPagination() {

            const pagination = document.getElementById("pagination");

            pagination.innerHTML = "";

            const totalPages = Math.ceil(filteredData.length / rowsPerPage);

            if (totalPages <= 1) return;

            // PREV
            pagination.innerHTML += `
            <button
                class="btn btn-sm btn-light mr-1"
                ${currentPage === 1 ? 'disabled' : ''}
                onclick="changePage(${currentPage - 1})">

                Previous

            </button>
        `;

            // PAGE NUMBER
            let pages = [];

            if (totalPages <= 8) {

                for (let i = 1; i <= totalPages; i++) {
                    pages.push(i);
                }

            } else {

                if (currentPage <= 4) {

                    pages = [1, 2, 3, 4, 5, '...', totalPages - 1, totalPages];

                } else if (currentPage >= totalPages - 3) {

                    pages = [
                        1,
                        2,
                        '...',
                        totalPages - 4,
                        totalPages - 3,
                        totalPages - 2,
                        totalPages - 1,
                        totalPages
                    ];

                } else {

                    pages = [
                        1,
                        '...',
                        currentPage - 1,
                        currentPage,
                        currentPage + 1,
                        '...',
                        totalPages
                    ];

                }

            }

            pages.forEach(page => {

                if (page === '...') {

                    pagination.innerHTML += `
                    <button
                        class="btn btn-sm btn-light mr-1"
                        disabled>

                        ...

                    </button>
                `;

                } else {

                    pagination.innerHTML += `
                    <button
                        class="btn btn-sm ${page === currentPage ? 'btn-primary' : 'btn-light'} mr-1"
                        onclick="changePage(${page})">

                        ${page}

                    </button>
                `;

                }

            });

            // NEXT
            pagination.innerHTML += `
            <button
                class="btn btn-sm btn-light"
                ${currentPage === totalPages ? 'disabled' : ''}
                onclick="changePage(${currentPage + 1})">

                Next

            </button>
        `;

        }

        // =========================
        // CHANGE PAGE
        // =========================
        function changePage(page) {

            const totalPages = Math.ceil(filteredData.length / rowsPerPage);

            if (page < 1 || page > totalPages) return;

            currentPage = page;

            renderTable();

        }

        // =========================
        // SHOW ENTRIES
        // =========================
        document.getElementById("entriesSelect")
            .addEventListener("change", function() {

                rowsPerPage = parseInt(this.value);

                currentPage = 1;

                renderTable();

            });

        // =========================
        // SEARCH
        // =========================
        document.getElementById("searchInput")
            .addEventListener("keyup", function() {

                const keyword = this.value.toLowerCase();

                filteredData = tableData.filter(row =>
                    row.text.includes(keyword)
                );

                currentPage = 1;

                renderTable();

            });

        // =========================
        // INFO TEXT
        // =========================
        function updateInfo() {

            const infoText = document.getElementById("infoText");

            const start =
                filteredData.length === 0 ?
                0 :
                ((currentPage - 1) * rowsPerPage) + 1;

            const end = Math.min(
                currentPage * rowsPerPage,
                filteredData.length
            );

            infoText.innerHTML =
                `Showing ${start} to ${end} of ${filteredData.length} entries`;

        }

        // =========================
        // FIRST RENDER
        // =========================
        renderTable();
    </script>
</body>

</html>