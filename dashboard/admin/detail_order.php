<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Detail Pesanan - Dashboard | Konig Guard Bureau</title>
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
                                        Detail Pesanan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Detail Pesanan</h1>

                        </div>
                        <a href="manage_orders.php"
                            class="btn btn-primary ml-1">Kembali List Order</a>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">

                <!-- HEADER -->
                <div class="card border-0 shadow-sm mt-4 mb-4"
                    style="border-radius:22px;">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <div>

                                <h3 class="font-weight-bold mb-2">
                                    Detail Pemesanan
                                </h3>

                                <div class="d-flex align-items-center flex-wrap">

                                    <span class="text-muted mr-3">
                                        Order ID : #ORD-250518-001
                                    </span>

                                    <span class="badge badge-primary px-3 py-2"
                                        style="
                                border-radius:30px;
                                font-size:12px;
                            ">
                                        Sedang Berlangsung
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- PROGRESS -->
                <div class="card border-0 shadow-sm mb-4"
                    style="border-radius:22px;">

                    <div class="card-body p-4">

                        <h5 class="font-weight-bold mb-5">
                            Progress Pemesanan Jasa
                        </h5>

                        <div class="order-progress-wrapper">

                            <!-- LINE -->
                            <div class="order-progress-line"></div>

                            <!-- ACTIVE LINE -->
                            <div class="order-progress-line-active progress-step-3"></div>


                            <div class="row text-center">

                                <!-- SUCCESS -->
                                <div class="col progress-item success">

                                    <div class="progress-circle">

                                        <span class="material-icons">
                                            check
                                        </span>

                                    </div>

                                    <h6 class="mt-3 mb-1">
                                        Order Masuk
                                    </h6>

                                    <small class="text-muted">
                                        Form order diterima
                                    </small>

                                </div>


                                <!-- SUCCESS -->
                                <div class="col progress-item success">

                                    <div class="progress-circle">

                                        <span class="material-icons">
                                            check
                                        </span>

                                    </div>

                                    <h6 class="mt-3 mb-1">
                                        Simulasi Harga
                                    </h6>

                                    <small class="text-muted">
                                        Harga diberikan
                                    </small>

                                </div>


                                <!-- ON PROGRESS -->
                                <div class="col progress-item on-progress">

                                    <div class="progress-circle pulse-animation">

                                        <span class="material-icons rotate-animation">
                                            sync
                                        </span>

                                    </div>

                                    <h6 class="mt-3 mb-1">
                                        Kontrak
                                    </h6>

                                    <small class="text-muted">
                                        Menunggu Persetujuan
                                    </small>

                                </div>


                                <!-- PENDING -->
                                <div class="col progress-item pending">

                                    <div class="progress-circle">
                                        4
                                    </div>

                                    <h6 class="mt-3 mb-1">
                                        Pembayaran
                                    </h6>

                                    <small class="text-muted">
                                        Menunggu Pembayaran
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- CONTENT -->
                <div class="row">

                    <!-- ORDER -->
                    <div class="col-xl-3 col-lg-6 mb-4">

                        <div class="card border-0 shadow-sm h-100"
                            style="border-radius:22px;">

                            <div class="card-body p-4"
                                style="
                overflow-y:auto;
                max-height:520px;
            ">

                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <h5 class="font-weight-bold mb-0">
                                        Order Masuk
                                    </h5>

                                    <span class="badge badge-success px-3 py-2"
                                        style="border-radius:20px;">
                                        Selesai
                                    </span>

                                </div>


                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Nama Lengkap
                                    </small>

                                    <input class="form-control bg-light" type="text" value="Andi Saputra" disabled>

                                </div>
                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Tipe Klien
                                    </small>

                                    <select class="form-control bg-light" disabled>
                                        <option value="1">Perusahaan</option>
                                    </select>

                                </div>


                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Email
                                    </small>

                                    <input class="form-control bg-light" type="text" value="andi@gmail.com" disabled>

                                </div>


                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        No Telp
                                    </small>

                                    <input class="form-control bg-light" type="text" value="+628123456789" disabled>

                                </div>

                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Jumlah Personil
                                    </small>

                                    <input class="form-control bg-light" type="text" value="5" disabled>

                                </div>


                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Jenis Jasa
                                    </small>

                                    <select class="form-control bg-light" disabled>
                                        <option value="1">Security</option>
                                    </select>

                                </div>


                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Lokasi Penempatan
                                    </small>

                                    <textarea class="form-control bg-light" rows="3" disabled>Jl. Pancaroba 15 Genteng, Jakarta Utara</textarea>

                                </div>

                                <div class="mb-3">

                                    <small class="text-muted d-block">
                                        Detail Kebutuhan
                                    </small>

                                    <textarea class="form-control bg-light" disabled>Ini Deskripsi</textarea>

                                </div>

                                <div class="text-center mt-5">
                                    <button class="btn btn-success btn-block py-2 mb-4 btn-setujui-order">

                                        <span class="material-icons align-middle mr-1"
                                            style="font-size:18px;">

                                            check

                                        </span>

                                        Setujui

                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- SIMULASI -->
                    <div class="col-xl-3 col-lg-6 mb-4 card-simulasi"
                        style="display:none;">

                        <div class="card border-0 shadow-sm w-100 h-100"
                            style="
            border-radius:22px;
        ">

                            <div class="card-body p-4"
                                style="
                overflow-y:auto;
                max-height:520px;
            ">

                                <!-- HEADER -->
                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <h5 class="font-weight-bold mb-0">
                                        Simulasi Harga
                                    </h5>

                                    <span class="badge badge-success px-3 py-2"
                                        style="border-radius:20px;">

                                        Selesai

                                    </span>

                                </div>


                                <!-- JASA -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Jasa Yang Dipesan
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        value="Pembuatan Website Company"
                                        disabled>

                                </div>


                                <!-- PERSONIL -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Jumlah Personil
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        value="5 Personil"
                                        disabled>

                                </div>


                                <!-- BIAYA SATUAN -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Biaya Satuan
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                        </div>

                                        <input type="text"
                                            class="form-control rupiah biaya-satuan"
                                            value="1.500.000">

                                    </div>

                                </div>


                                <!-- SUBTOTAL -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Subtotal
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                        </div>

                                        <input type="text"
                                            class="form-control subtotal"
                                            readonly>

                                    </div>

                                </div>


                                <!-- BIAYA LAYANAN -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Biaya Layanan
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                        </div>

                                        <input type="text"
                                            class="form-control rupiah biaya-layanan"
                                            value="500.000">

                                    </div>

                                </div>


                                <!-- BIAYA TAMBAHAN -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Biaya Tambahan
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                        </div>

                                        <input type="text"
                                            class="form-control rupiah biaya-tambahan"
                                            value="250.000">

                                    </div>

                                </div>


                                <!-- PAJAK -->
                                <div class="form-group">

                                    <label class="text-muted mb-2">
                                        Pajak
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                        </div>

                                        <input type="text"
                                            class="form-control rupiah biaya-pajak"
                                            value="825.000">

                                    </div>

                                </div>


                                <!-- TOTAL -->
                                <div class="form-group">

                                    <label class="font-weight-bold text-primary mb-2">
                                        Total Pembayaran
                                    </label>

                                    <div class="input-group">

                                        <div class="input-group-prepend">

                                            <span class="input-group-text bg-primary text-white">
                                                Rp
                                            </span>

                                        </div>

                                        <input type="text"
                                            class="form-control font-weight-bold text-primary total-pembayaran"
                                            readonly>

                                    </div>

                                </div>


                                <!-- CATATAN -->
                                <div class="form-group mb-0">

                                    <label class="text-muted mb-2">
                                        Catatan
                                    </label>

                                    <textarea class="form-control"
                                        rows="4"
                                        placeholder="Tambahkan catatan simulasi harga..."></textarea>

                                </div>

                                <div class="text-center mt-5">
                                    <button class="btn btn-success btn-block py-2 mb-4 btn-kirim-estimasi">

                                        <span class="material-icons align-middle mr-1"
                                            style="font-size:18px;">

                                            send

                                        </span>

                                        Kirim Estimasi

                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- KONTRAK -->
                    <div class="col-xl-3 col-lg-6 mb-4 card-kontrak"
                        style="display:none;">

                        <div class="card border-0 shadow-sm w-100 h-100"
                            style="
            border-radius:22px;
        ">

                            <div class="card-body p-4"
                                style="
                overflow-y:auto;
                max-height:520px;
            ">

                                <!-- HEADER -->
                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <h5 class="font-weight-bold mb-0">
                                        Kontrak
                                    </h5>

                                    <span class="badge badge-warning px-3 py-2"
                                        style="border-radius:20px;">

                                        Menunggu

                                    </span>

                                </div>



                                <!-- =========================
                 ADMIN UPLOAD
            ========================== -->

                                <label class="text-muted d-block mb-3">
                                    Upload Kontrak Dari Admin
                                </label>


                                <!-- UPLOAD BOX -->
                                <label for="uploadKontrakAdmin"
                                    class="upload-box text-center w-100 mb-3"
                                    style="cursor:pointer;">

                                    <span class="material-icons mb-2"
                                        style="font-size:45px;">

                                        cloud_upload

                                    </span>

                                    <h6 class="font-weight-bold">
                                        Upload Kontrak
                                    </h6>

                                    <small class="text-muted">
                                        DOCX / PDF
                                    </small>

                                </label>

                                <input type="file"
                                    id="uploadKontrakAdmin"
                                    accept=".pdf,.doc,.docx"
                                    hidden>



                                <!-- FILE PREVIEW -->
                                <a href="assets/file/Kontrak.docx"
                                    target="_blank"
                                    class="text-decoration-none preview-kontrak-link">

                                    <div class="border p-3 mb-3"
                                        style="
                        border-radius:18px;
                        border-style:dashed !important;
                    ">

                                        <div class="d-flex align-items-center">

                                            <div class="mr-3 rounded d-flex align-items-center justify-content-center"
                                                style="
                                width:60px;
                                height:60px;
                                background:#edf4ff;
                                color:#4a90e2;
                            ">

                                                <span class="material-icons icon-preview-kontrak">
                                                    description
                                                </span>

                                            </div>

                                            <div class="flex-grow-1">

                                                <strong class="d-block text-dark nama-file-kontrak">
                                                    Nama File Muncul disini
                                                </strong>

                                                <small class="text-muted ukuran-file-kontrak">
                                                    Ukuran File Muncul disini
                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                </a>



                                <!-- TOGGLE EMAIL -->
                                <div class="d-flex align-items-center justify-content-between mb-4">

                                    <div>

                                        <strong class="d-block">
                                            Kirim Ke Email
                                        </strong>

                                        <small class="text-muted">
                                            Kontrak otomatis dikirim ke email klien
                                        </small>

                                    </div>


                                    <div class="custom-control custom-switch">

                                        <input type="checkbox"
                                            class="custom-control-input"
                                            id="toggleEmail">

                                        <label class="custom-control-label"
                                            for="toggleEmail"></label>

                                    </div>

                                    <div class="email-wrapper mt-3"
                                        style="display:none;">

                                        <input type="email"
                                            class="form-control email-klien"
                                            placeholder="Masukkan email klien">

                                        <small class="text-muted">
                                            Pastikan email klien benar sebelum mengirim kontrak.
                                        </small>

                                    </div>

                                </div>



                                <!-- BUTTON -->
                                <button class="btn btn-primary btn-block py-2 mb-4 btn-kirim-kontrak">

                                    <span class="material-icons align-middle mr-1"
                                        style="font-size:18px;">

                                        send

                                    </span>

                                    Kirim Kontrak

                                </button>





                                <!-- =========================
                 FILE DARI KLIEN
            ========================== -->

                                <label class="text-muted d-block mb-3">
                                    File Balasan Kontrak Dari Klien
                                </label>


                                <!-- FILE -->
                                <a href="assets/file/Kontrak_Klien.pdf"
                                    target="_blank"
                                    class="text-decoration-none">

                                    <div class="border p-3 mb-4"
                                        style="
                        border-radius:18px;
                        border-style:dashed !important;
                    ">

                                        <div class="d-flex align-items-center">

                                            <div class="mr-3 rounded d-flex align-items-center justify-content-center"
                                                style="
                                width:60px;
                                height:60px;
                                background:#e8fff2;
                                color:#28c76f;
                            ">

                                                <span class="material-icons">
                                                    description
                                                </span>

                                            </div>

                                            <div class="flex-grow-1">

                                                <strong class="d-block text-dark">
                                                    Kontrak_TTD_Klien.pdf
                                                </strong>

                                                <small class="text-muted">
                                                    1.8 MB
                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                </a>



                                <!-- BUTTON -->
                                <button class="btn btn-success btn-block py-2 btn-submit-kontrak">

                                    <span class="material-icons align-middle mr-1"
                                        style="font-size:18px;">

                                        check

                                    </span>

                                    Submit Verifikasi

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- PEMBAYARAN -->
                    <div class="col-xl-3 col-lg-6 mb-4 card-pembayaran"
                        style="display:none;">

                        <div class="card border-0 shadow-sm w-100 h-100"
                            style="
            border-radius:22px;
        ">

                            <div class="card-body p-4"
                                style="
                overflow-y:auto;
                max-height:520px;
            ">

                                <!-- HEADER -->
                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <h5 class="font-weight-bold mb-0">
                                        Pembayaran
                                    </h5>

                                    <span class="badge badge-warning px-3 py-2"
                                        style="border-radius:20px;">

                                        Pending

                                    </span>

                                </div>



                                <!-- TOTAL PEMBAYARAN -->
                                <div class="p-4 mb-4"
                                    style="
                    background:#fff8e8;
                    border-radius:18px;
                ">

                                    <small class="text-muted d-block mb-2">
                                        Total Yang Harus Dibayarkan
                                    </small>

                                    <h3 class="font-weight-bold text-warning mb-0 pembayaran-total-text">
                                        0
                                    </h3>

                                </div>





                                <!-- INFORMASI BANK -->
                                <div class="border p-3 mb-4"
                                    style="
                    border-radius:18px;
                    border-style:dashed !important;
                ">

                                    <div class="d-flex align-items-center">

                                        <!-- LOGO -->
                                        <div class="mr-3 rounded d-flex align-items-center justify-content-center"
                                            style="
                            width:65px;
                            height:65px;
                            background:#edf4ff;
                        ">

                                            <img src="assets/images/Bank_Central_Asia.svg"
                                                alt="BCA"
                                                style="
                                width:42px;
                            ">

                                        </div>


                                        <!-- DETAIL -->
                                        <div>

                                            <small class="text-muted d-block">
                                                Transfer Bank
                                            </small>

                                            <strong class="d-block">
                                                Bank BCA
                                            </strong>

                                            <small class="d-block">
                                                1234567890
                                            </small>

                                            <small class="text-muted">
                                                A/N PT DIGITAL INDONESIA
                                            </small>

                                        </div>

                                    </div>

                                </div>





                                <!-- NOTE -->
                                <div class="alert alert-warning mb-4"
                                    style="
                    border-radius:18px;
                ">

                                    <div class="d-flex align-items-start">

                                        <span class="material-icons mr-2">
                                            warning
                                        </span>

                                        <div>

                                            <strong class="d-block mb-1">
                                                Perhatian Pembayaran
                                            </strong>

                                            <small>
                                                Jika pesanan dibatalkan setelah proses berjalan,
                                                maka dikenakan biaya pinalti sebesar
                                                <strong>
                                                    Rp 500.000
                                                </strong>.
                                                Harap bijak dalam melakukan pemesanan
                                                di aplikasi kami.
                                            </small>

                                        </div>

                                    </div>

                                </div>





                                <!-- UPLOAD BOX -->
                                <label for="uploadPembayaran"
                                    class="upload-box text-center w-100 mb-3"
                                    style="cursor:pointer;">

                                    <span class="material-icons mb-2"
                                        style="font-size:45px;">

                                        cloud_upload

                                    </span>

                                    <h6 class="font-weight-bold">
                                        Upload Bukti Transfer
                                    </h6>

                                    <small class="text-muted">
                                        JPG / PNG / PDF
                                    </small>

                                </label>

                                <input type="file"
                                    id="uploadPembayaran"
                                    hidden>





                                <!-- PREVIEW FILE -->
                                <a href="assets/file/Bukti_Transfer.pdf"
                                    target="_blank"
                                    class="text-decoration-none">

                                    <div class="border p-3 mb-4"
                                        style="
                        border-radius:18px;
                        border-style:dashed !important;
                    ">

                                        <div class="d-flex align-items-center">

                                            <div class="mr-3 rounded d-flex align-items-center justify-content-center"
                                                style="
                                width:60px;
                                height:60px;
                                background:#e8fff2;
                                color:#28c76f;
                            ">

                                                <span class="material-icons">
                                                    receipt
                                                </span>

                                            </div>

                                            <div class="flex-grow-1">

                                                <strong class="d-block text-dark">
                                                    Bukti_Transfer.pdf
                                                </strong>

                                                <small class="text-muted">
                                                    1.2 MB
                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                </a>

                                <!-- BUTTON -->
                                <button class="btn btn-success btn-block py-2 btn-submit-pembayaran">

                                    <span class="material-icons align-middle mr-1"
                                        style="font-size:18px;">

                                        check

                                    </span>

                                    Submit Pembayaran

                                </button>

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

    <!-- MODAL LOADING -->
    <div class="modal fade"
        id="modalLoading"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0"
                style="border-radius:22px;">

                <div class="modal-body text-center p-5">

                    <div class="spinner-border text-primary mb-4"
                        style="
                        width:4rem;
                        height:4rem;
                    ">
                    </div>

                    <h4 class="font-weight-bold mb-2">
                        Memproses...
                    </h4>

                    <p class="text-muted mb-0 modal-loading-text">
                        Mohon tunggu sebentar
                    </p>

                </div>

            </div>

        </div>

    </div>



    <!-- MODAL SUKSES -->
    <div class="modal fade"
        id="modalSuccess"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0"
                style="border-radius:22px;">

                <div class="modal-body text-center p-5">

                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="
                        width:90px;
                        height:90px;
                        background:#e8fff2;
                        color:#28c76f;
                    ">

                        <span class="material-icons"
                            style="font-size:50px;">
                            check
                        </span>

                    </div>

                    <h3 class="font-weight-bold mb-3">
                        Berhasil
                    </h3>

                    <p class="text-muted mb-0 modal-success-text">
                        Data berhasil diproses
                    </p>

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
        /* =========================
   FORMAT RUPIAH
========================= */
        function formatRupiah(angka) {

            angka = angka.replace(/[^\d]/g, '');

            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        }


        /* =========================
           AMBIL ANGKA
        ========================= */
        function ambilAngka(value) {

            return parseInt(value.replace(/\./g, '')) || 0;

        }


        /* =========================
           FORMAT INPUT
        ========================= */
        $(document).on('input', '.rupiah', function() {

            let value = $(this).val();

            $(this).val(formatRupiah(value));

            hitungTotal();

        });


        /* =========================
           HITUNG TOTAL
        ========================= */
        function hitungTotal() {

            let jumlahPersonil = 5;

            let biayaSatuan = ambilAngka($('.biaya-satuan').val());
            let biayaLayanan = ambilAngka($('.biaya-layanan').val());
            let biayaTambahan = ambilAngka($('.biaya-tambahan').val());
            let biayaPajak = ambilAngka($('.biaya-pajak').val());

            let subtotal = biayaSatuan * jumlahPersonil;

            let total =
                subtotal +
                biayaLayanan +
                biayaTambahan +
                biayaPajak;


            $('.subtotal').val(
                formatRupiah(subtotal.toString())
            );

            $('.total-pembayaran').val(
                formatRupiah(total.toString())
            );

            /* TOTAL PEMBAYARAN CARD */
            $('.pembayaran-total-text').text(
                'Rp ' + formatRupiah(total.toString())
            );

        }


        /* =========================
           LOAD
        ========================= */
        $(document).ready(function() {

            $('.rupiah').each(function() {

                $(this).val(
                    formatRupiah($(this).val())
                );

            });

            hitungTotal();

        });
    </script>

    <script>
        /* =========================
   TOGGLE EMAIL
========================= */

        $('#toggleEmail').change(function() {

            if ($(this).is(':checked')) {

                $('.email-wrapper').slideDown(200);

            } else {

                $('.email-wrapper').slideUp(200);

            }

        });


        /* =========================
           MODAL FUNCTION
        ========================= */

        function showLoading(text) {

            $('.modal-loading-text').text(text);

            $('#modalLoading').modal('show');

        }


        function showSuccess(text) {

            $('#modalLoading').modal('hide');

            $('.modal-success-text').html(text);

            setTimeout(function() {

                $('#modalSuccess').modal('show');

            }, 400);

        }



        /* =========================
           ORDER DISETUJUI
        ========================= */

        $('.btn-setujui-order').click(function() {

            showLoading('Menyetujui order...');

            setTimeout(function() {

                showSuccess(
                    'Order berhasil disetujui'
                );

                $('.card-simulasi').slideDown(300);

            }, 2000);

        });



        /* =========================
           ESTIMASI
        ========================= */

        $('.btn-kirim-estimasi').click(function() {

            showLoading('Mengirim estimasi harga...');

            setTimeout(function() {

                showSuccess(
                    'Estimasi harga berhasil dikirim'
                );

                $('.card-kontrak').slideDown(300);

            }, 2000);

        });



        /* =========================
           KIRIM KONTRAK
        ========================= */

        $('.btn-kirim-kontrak').click(function() {

            let kirimEmail = $('#toggleEmail').is(':checked');

            let email = $('.email-klien').val();


            if (kirimEmail && email == '') {

                alert('Masukkan email klien terlebih dahulu');

                return;

            }


            showLoading('Mengirim kontrak...');

            setTimeout(function() {

                if (kirimEmail) {

                    showSuccess(
                        'Kontrak berhasil dikirim dan email berhasil dikirim ke klien'
                    );

                } else {

                    showSuccess(
                        'Kontrak berhasil dikirim'
                    );

                }

            }, 2000);

        });



        /* =========================
           VERIFIKASI KONTRAK
        ========================= */

        $('.btn-submit-kontrak').click(function() {

            showLoading('Memverifikasi kontrak...');

            setTimeout(function() {

                showSuccess(
                    'Kontrak berhasil disetujui'
                );

                $('.card-pembayaran').slideDown(300);

            }, 2000);

        });



        /* =========================
           PEMBAYARAN
        ========================= */

        $('.btn-submit-pembayaran').click(function() {

            showLoading('Memproses pembayaran...');

            setTimeout(function() {

                showSuccess(
                    'Pembayaran berhasil diterima'
                );

            }, 2000);

        });
    </script>

    <script>
        /* =========================
   PREVIEW FILE KONTRAK
========================= */

        $('#uploadKontrakAdmin').change(function(e) {

            let file = e.target.files[0];

            if (!file) {
                return;
            }

            let fileURL = URL.createObjectURL(file);

            let ukuranFile = (file.size / 1024 / 1024).toFixed(2);

            $('.preview-kontrak-link').attr('href', fileURL);

            $('.nama-file-kontrak').text(file.name);

            $('.ukuran-file-kontrak').text(
                ukuranFile + ' MB'
            );

            $('.preview-kontrak-link').slideDown(200);


            /* GANTI ICON BERDASARKAN FILE */
            let extension = file.name.split('.').pop().toLowerCase();

            if (extension == 'pdf') {

                $('.icon-preview-kontrak').text('picture_as_pdf');

            } else {

                $('.icon-preview-kontrak').text('description');

            }

        });
    </script>

</body>

</html>