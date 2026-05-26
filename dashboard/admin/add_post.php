<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

/*
|--------------------------------------------------------------------------
| LOAD DATA CATEGORY + SUBCATEGORY
|--------------------------------------------------------------------------
*/
$queryCategory = mysqli_query($conn, "
    SELECT *
    FROM post_category
    ORDER BY name_category ASC
");

$querySubcategory = mysqli_query($conn, "
    SELECT 
        ps.id,
        ps.id_postcategory,
        ps.name_subcategory,
        pc.name_category
    FROM post_subcategory ps
    LEFT JOIN post_category pc
        ON ps.id_postcategory = pc.id
    ORDER BY ps.name_subcategory ASC
");

/*
|--------------------------------------------------------------------------
| SIMPAN POST
|--------------------------------------------------------------------------
*/
if (isset($_POST['submit_post'])) {

    $titlePost         = mysqli_real_escape_string($conn, $_POST['title_post']);
    $idPostCategory    = intval($_POST['id_post_category']);
    $idPostSubcategory = intval($_POST['id_post_subcategory']);
    $postDesc          = mysqli_real_escape_string($conn, $_POST['post_desc']);

    $namaGambar = '';

    /*
    |--------------------------------------------------------------------------
    | UPLOAD IMAGE
    |--------------------------------------------------------------------------
    */
    if (isset($_FILES['post_img']) && $_FILES['post_img']['error'] == 0) {

        $folderUpload = __DIR__ . '/assets/images/uploads/posts/';

        if (!is_dir($folderUpload)) {
            mkdir($folderUpload, 0777, true);
        }

        $fileTmp  = $_FILES['post_img']['tmp_name'];
        $fileName = $_FILES['post_img']['name'];
        $fileSize = $_FILES['post_img']['size'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            echo "
            <script>
                alert('Format gambar harus JPG, JPEG atau PNG');
            </script>
            ";
        } else if ($fileSize > 2097152) {

            echo "
            <script>
                alert('Ukuran gambar maksimal 2MB');
            </script>
            ";
        } else {

            $namaGambar = time() . '_' . rand(1000, 9999) . '.' . $ext;

            move_uploaded_file(
                $fileTmp,
                $folderUpload . $namaGambar
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT DATABASE
    |--------------------------------------------------------------------------
    */
    $insert = mysqli_query($conn, "
        INSERT INTO post
        (
            title_post,
            id_post_category,
            id_post_subcategory,
            post_desc,
            post_img,
            created_at,
            update_at
        )
        VALUES
        (
            '$titlePost',
            '$idPostCategory',
            '$idPostSubcategory',
            '$postDesc',
            '$namaGambar',
            NOW(),
            NOW()
        )
    ");

    if ($insert) {

        echo "
        <script>
            window.onload = function() {
                $('#modalSuccessPosting').modal('show');
            }
        </script>
        ";
    } else {

        echo "
        <script>
            alert('Gagal menyimpan postingan');
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
    <title>Tambah Postingan - Dashboard | Konig Guard Bureau</title>

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        rel="stylesheet" />

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

    <!-- SUMMERNOTE -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

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
                                        Manage Postingan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Postingan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <div class="container-fluid mt-4">

                    <!-- HEADER -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-1">Tambah Postingan</h4>
                            <p class="text-muted mb-0">
                                Lengkapi informasi untuk menambahkan Postingan baru.
                            </p>
                        </div>
                    </div>

                    <!-- CARD FORM -->
                    <form method="POST"
                        enctype="multipart/form-data">
                        <div class="card p-4" style="border-radius:14px; border:none;">

                            <!-- JUDUL -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">
                                    Judul Postingan
                                </label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">
                                            <span class="material-icons" style="font-size:20px;">
                                                title
                                            </span>
                                        </span>
                                    </div>

                                    <input
                                        id="judulPostingan"
                                        name="title_post"
                                        type="text"
                                        class="form-control"
                                        placeholder="Masukkan judul postingan"
                                        required>
                                </div>
                            </div>

                            <!-- KATEGORI -->
                            <div class="row">

                                <!-- KATEGORI -->
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">
                                            Kategori
                                        </label>

                                        <select
                                            class="select2"
                                            name="id_post_category"
                                            id="kategoriSelect"
                                            required>

                                            <option value="">
                                                Pilih kategori...
                                            </option>

                                            <?php while ($category = mysqli_fetch_assoc($queryCategory)) : ?>

                                                <option value="<?= $category['id']; ?>">
                                                    <?= $category['name_category']; ?>
                                                </option>

                                            <?php endwhile; ?>

                                        </select>
                                    </div>
                                </div>

                                <!-- SUB KATEGORI -->
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">
                                            Sub Kategori
                                        </label>

                                        <select
                                            class="select2"
                                            name="id_post_subcategory"
                                            id="subkategoriSelect"
                                            required>

                                            <option value="">
                                                Pilih Sub Kategori...
                                            </option>

                                            <?php while ($sub = mysqli_fetch_assoc($querySubcategory)) : ?>

                                                <option
                                                    value="<?= $sub['id']; ?>"
                                                    data-category="<?= $sub['id_postcategory']; ?>">

                                                    <?= $sub['name_subcategory']; ?>
                                                    (<?= $sub['name_category']; ?>)

                                                </option>

                                            <?php endwhile; ?>

                                        </select>
                                    </div>
                                </div>

                            </div>

                            <!-- SUMMERNOTE -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">
                                    Isi Konten
                                </label>

                                <textarea
                                    id="summernote"
                                    name="post_desc"></textarea>
                            </div>

                            <!-- UPLOAD IMAGE -->
                            <div class="form-group mb-4">

                                <label class="font-weight-bold">
                                    Fitur Gambar / Thumbnail
                                </label>

                                <div
                                    class="border p-4"
                                    style="
            border-radius:12px;
            border-style:dashed !important;
            background:#fafbfe;
        ">

                                    <div class="d-flex align-items-center justify-content-between">

                                        <div class="d-flex align-items-center">

                                            <div
                                                class="d-flex align-items-center justify-content-center mr-3"
                                                style="
                        width:70px;
                        height:70px;
                        border-radius:12px;
                        background:rgba(103,116,223,.1);
                    ">

                                                <span
                                                    class="material-icons"
                                                    style="
                            font-size:36px;
                            color:var(--primary);
                        ">
                                                    cloud_upload
                                                </span>
                                            </div>

                                            <div>
                                                <h6 class="mb-1">
                                                    Upload Thumbnail Postingan
                                                </h6>

                                                <small class="text-muted">
                                                    Format JPG, PNG, JPEG maksimal 2MB
                                                </small>
                                            </div>

                                        </div>

                                        <div>

                                            <input
                                                type="file"
                                                id="uploadThumbnail"
                                                name="post_img"
                                                accept="image/*"
                                                hidden>

                                            <button
                                                type="button"
                                                class="btn btn-outline-primary d-flex align-items-center"
                                                onclick="document.getElementById('uploadThumbnail').click()">

                                                <span class="material-icons mr-2" style="font-size:20px;">
                                                    image
                                                </span>

                                                Pilih Gambar
                                            </button>

                                        </div>

                                    </div>

                                    <!-- PREVIEW IMAGE -->
                                    <div
                                        id="previewContainer"
                                        class="mt-4"
                                        style="display:none;">

                                        <div class="d-flex align-items-center">

                                            <img
                                                id="previewImage"
                                                src=""
                                                alt="Preview"
                                                style="
                        width:180px;
                        height:120px;
                        object-fit:cover;
                        border-radius:12px;
                        border:1px solid #dbe5ee;
                    ">

                                            <div class="ml-3">

                                                <h6 class="mb-1">
                                                    Preview Thumbnail
                                                </h6>

                                                <small
                                                    id="fileName"
                                                    class="text-muted">
                                                </small>

                                                <div class="mt-2">
                                                    <button
                                                        type="button"
                                                        id="removeImage"
                                                        class="btn btn-sm btn-danger d-flex align-items-center">

                                                        <span class="material-icons mr-1" style="font-size:16px;">
                                                            delete
                                                        </span>

                                                        Hapus
                                                    </button>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ACTION BUTTON -->
                            <div class="d-flex align-items-center">

                                <button type="submit"
                                    name="submit_post"
                                    id="btnSimpanPostingan"
                                    class="btn btn-primary d-flex align-items-center mr-2">
                                    <span class="material-icons mr-2" style="font-size:20px;">
                                        save
                                    </span>
                                    Simpan Postingan
                                </button>

                            </div>

                        </div>
                    </form>

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

    <!-- =========================
    MODAL SUKSES POSTINGAN
========================= -->

    <div class="modal fade" id="modalSuccessPosting" tabindex="-1" role="dialog">
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
                        Posting Berhasil
                    </h4>

                    <!-- TEXT -->
                    <p class="text-muted mb-4" id="successPostingText">
                        Postingan berhasil ditambahkan
                    </p>

                    <!-- BUTTON -->
                    <button type="button"
                        id="btnOkayPosting"
                        class="btn btn-success px-4"
                        style="
                        border-radius:10px;
                        min-width:120px;
                        height:45px;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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

    <!-- SUMMERNOTE -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

    <script>
        $('#summernote').summernote({
            placeholder: 'Tulis isi artikel atau berita...',
            tabsize: 2,
            height: 350,

            fontNames: [
                'Arial',
                'Arial Black',
                'Comic Sans MS',
                'Courier New',
                'Helvetica',
                'Impact',
                'Roboto',
                'Tahoma',
                'Times New Roman',
                'Verdana',
                'Poppins',
                'Montserrat'
            ],

            fontNamesIgnoreCheck: [
                'Poppins',
                'Montserrat'
            ],

            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    </script>

    <script>
        const uploadThumbnail = document.getElementById('uploadThumbnail');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const fileName = document.getElementById('fileName');
        const removeImage = document.getElementById('removeImage');

        uploadThumbnail.addEventListener('change', function() {

            const file = this.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function(e) {

                    previewImage.src = e.target.result;
                    fileName.textContent = file.name;

                    previewContainer.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }

        });

        removeImage.addEventListener('click', function() {

            uploadThumbnail.value = '';
            previewImage.src = '';
            fileName.textContent = '';

            previewContainer.style.display = 'none';

        });
    </script>

    <script>
        // SIMPAN POSTINGAN
        document.getElementById("btnSimpanPostingan")
            .addEventListener("click", function() {

                let judul = document.getElementById("judulPostingan").value.trim();

                // VALIDASI JUDUL
                if (judul === "") {
                    alert("Judul postingan wajib diisi!");
                    return;
                }

                // SET TEXT MODAL
                document.getElementById("successPostingText").innerHTML =
                    `<b>${judul}</b> berhasil diposting`;

                // SHOW MODAL
                $('#modalSuccessPosting').modal('show');

            });

        // BUTTON OKAY
        document.getElementById("btnOkayPosting")
            .addEventListener("click", function() {

                window.location.href = "manage_post.php";

            });
    </script>

    <script>
        $(window).on('load', function() {

            $('#kategoriSelect').select2({
                placeholder: 'Cari kategori...',
                width: '100%'
            });

            $('#subkategoriSelect').select2({
                placeholder: 'Cari sub kategori...',
                width: '100%'
            });

            // FILTER SUBCATEGORY
            $('#kategoriSelect').on('change', function() {

                let selectedCategory = $(this).val();

                $('#subkategoriSelect option').each(function() {

                    let categoryId = $(this).data('category');

                    if (
                        categoryId == selectedCategory ||
                        $(this).val() == ''
                    ) {

                        $(this).prop('disabled', false);

                    } else {

                        $(this).prop('disabled', true);

                    }

                });

                $('#subkategoriSelect')
                    .val('')
                    .trigger('change');

            });

        });
    </script>

</body>

</html>