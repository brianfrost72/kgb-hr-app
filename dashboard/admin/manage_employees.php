<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Personel - Dashboard | Konig Guard Bureau</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex" />

    <!-- Perfect Scrollbar -->
    <link
        type="text/css"
        href="assets/vendor/perfect-scrollbar.css"
        rel="stylesheet" />

    <!-- App CSS -->
    <link type="text/css" href="assets/css/app.css" rel="stylesheet" />
    <link type="text/css" href="assets/css/app.rtl.css" rel="stylesheet" />

    <!-- Material Design Icons -->
    <link
        type="text/css"
        href="assets/css/vendor-material-icons.css"
        rel="stylesheet" />

    <!-- Font Awesome FREE Icons -->
    <link
        type="text/css"
        href="assets/css/vendor-fontawesome-free.css"
        rel="stylesheet" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id=UA-133433427-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());
        gtag("config", "UA-133433427-1");
    </script>

    <!-- Flatpickr -->
    <link
        type="text/css"
        href="assets/css/vendor-flatpickr.css"
        rel="stylesheet" />
    <link
        type="text/css"
        href="assets/css/vendor-flatpickr-airbnb.css"
        rel="stylesheet" />

    <!-- Vector Maps -->
    <link
        type="text/css"
        href="assets/vendor/jqvmap/jqvmap.min.css"
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
                                        Manage Personel
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Personel</h1>
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
                            <h4 class="card-title">Manage Personel</h4>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">Tambah</button>
                        </div>

                        <div class="card-body">
                            <!-- FILTER -->
                            <div class="row align-items-end mb-4">

                                <!-- SHOW ENTRIES -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Show Entries
                                    </label>

                                    <select id="showEntries" class="form-control">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>20</option>
                                    </select>
                                </div>

                                <!-- FILTER DEPARTEMEN -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Departemen
                                    </label>

                                    <select id="filterDepartment" class="form-control">
                                        <option value="">Semua</option>
                                        <option>HR</option>
                                        <option>Admin</option>
                                        <option>IT</option>
                                        <option>Akuntansi</option>
                                    </select>
                                </div>

                                <!-- FILTER JABATAN -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Jabatan
                                    </label>

                                    <select id="filterJabatan" class="form-control">
                                        <option value="">Semua</option>
                                        <option>Manager</option>
                                        <option>Supervisor</option>
                                        <option>Staff</option>
                                        <option>Admin</option>
                                    </select>
                                </div>

                                <!-- FILTER GENDER -->
                                <div class="col-lg-2 col-md-3 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Jenis Kelamin
                                    </label>

                                    <select id="filterGender" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="l">Laki-laki</option>
                                        <option value="p">Perempuan</option>
                                    </select>
                                </div>

                                <!-- SEARCH -->
                                <div class="col-lg-4 col-md-12 mb-2">
                                    <label class="small text-muted d-block mb-1">
                                        Search
                                    </label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <span class="material-icons">search</span>
                                            </span>
                                        </div>

                                        <input type="text"
                                            id="searchInput"
                                            class="form-control"
                                            placeholder="Cari personel...">
                                    </div>
                                </div>

                            </div>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>No</th>
                                            <th>NIK Karyawan</th>
                                            <th>Nama</th>
                                            <th>Jenis Kelamin (L/P)</th>
                                            <th>Departemen</th>
                                            <th>Jabatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                </table>
                            </div>

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

    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Personel</h5>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-md-6">

                            <!-- NAMA -->
                            <div class="form-group">
                                <label>Nama Lengkap</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">person</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="namaTambah"
                                        class="form-control"
                                        placeholder="Andy Lau">
                                </div>
                            </div>

                            <!-- TEMPAT LAHIR -->
                            <div class="form-group">
                                <label>Tempat Lahir</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">location_city</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="tempatLahirTambah"
                                        class="form-control"
                                        placeholder="Jakarta">
                                </div>
                            </div>

                            <!-- TANGGAL LAHIR -->
                            <div class="form-group">

                                <label>Tanggal Lahir</label>

                                <div class="input-group">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">
                                                date_range
                                            </span>
                                        </span>
                                    </div>

                                    <input type="date"
                                        id="tanggalLahirTambah"
                                        class="form-control">

                                </div>

                                <!-- TEXT UMUR -->
                                <small id="umurText"
                                    class="text-muted d-block mt-2"
                                    style="
            font-size:13px;
        ">

                                    Umur akan muncul di sini

                                </small>

                            </div>

                            <!-- EMAIL -->
                            <div class="form-group">
                                <label>Email</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">email</span>
                                        </span>
                                    </div>

                                    <input type="email"
                                        id="emailTambah"
                                        class="form-control"
                                        placeholder="email@gmail.com">
                                </div>
                            </div>

                            <!-- TELEPON -->
                            <div class="form-group">
                                <label>No Telp / HP</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">call</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="teleponTambah"
                                        class="form-control"
                                        placeholder="081xxxxxxxx">
                                </div>
                            </div>

                            <!-- GENDER -->
                            <div class="form-group">
                                <label>Jenis Kelamin</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">wc</span>
                                        </span>
                                    </div>

                                    <select id="genderTambah" class="form-control">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option>Laki-laki</option>
                                        <option>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- STATUS -->
                            <div class="form-group">
                                <label>Status Perkawinan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">favorite</span>
                                        </span>
                                    </div>

                                    <select id="statusTambah" class="form-control">
                                        <option value="">Pilih Status</option>
                                        <option>Belum Nikah</option>
                                        <option>Menikah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- DEPARTEMEN -->
                            <div class="form-group">
                                <label>Departemen</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">domain</span>
                                        </span>
                                    </div>

                                    <select id="departmentTambah" class="form-control">
                                        <option value="">Pilih Departemen</option>
                                        <option>HR</option>
                                        <option>Admin</option>
                                        <option>Akuntansi</option>
                                    </select>
                                </div>
                            </div>

                            <!-- JABATAN -->
                            <div class="form-group">
                                <label>Jabatan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">work</span>
                                        </span>
                                    </div>

                                    <select id="jabatanTambah" class="form-control">
                                        <option value="">Pilih Jabatan</option>
                                        <option>Manager</option>
                                        <option>Supervisor</option>
                                        <option>Staff</option>
                                    </select>
                                </div>
                            </div>

                            <!-- KTP -->
                            <div class="form-group">
                                <label>Nomor KTP</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="ktpTambah"
                                        class="form-control"
                                        placeholder="Nomor KTP">
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-6">

                            <!-- NPWP -->
                            <div class="form-group">
                                <label>Nomor NPWP</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="npwpTambah"
                                        class="form-control"
                                        placeholder="Nomor NPWP">
                                </div>
                            </div>

                            <!-- BPJS KESEHATAN -->
                            <div class="form-group">
                                <label>Nomor BPJS Kesehatan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="bpjsTambah"
                                        class="form-control"
                                        placeholder="Nomor BPJS Kesehatan">
                                </div>
                            </div>

                            <!-- BPJS TK -->
                            <div class="form-group">
                                <label>Nomor BPJS KetenagaKerjaan</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">credit_card</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="bpjsTKTambah"
                                        class="form-control"
                                        placeholder="Nomor BPJS TK">
                                </div>
                            </div>

                            <!-- KTA -->
                            <div class="form-group">
                                <label>Nomor KTA Petugas</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">assignment_ind</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="ktaTambah"
                                        class="form-control"
                                        placeholder="Nomor KTA">
                                </div>
                            </div>

                            <!-- NO. REKENING -->
                            <div class="form-group">
                                <label>No Rekening</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">account_balance</span>
                                        </span>
                                    </div>

                                    <input type="text"
                                        id="rekeningTambah"
                                        class="form-control"
                                        placeholder="BCA 123456789">
                                </div>
                            </div>

                            <!-- ALAMAT -->
                            <div class="form-group">
                                <label>Alamat</label>

                                <div class="input-group">
                                    <div class="input-group-prepend align-items-stretch">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons">home</span>
                                        </span>
                                    </div>

                                    <textarea id="alamatTambah"
                                        class="form-control"
                                        rows="5"
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                </div>
                            </div>

                            <!-- FOTO -->
                            <div class="form-group">
                                <label>Upload Foto</label>

                                <div class="custom-file">
                                    <input type="file"
                                        id="photoTambah"
                                        class="custom-file-input">

                                    <label class="custom-file-label">
                                        Pilih Foto
                                    </label>
                                </div>
                            </div>

                            <!-- PREVIEW -->
                            <div class="mt-4 text-center">

                                <img id="previewPhoto"
                                    src=""
                                    class="rounded-circle shadow-sm border"
                                    style="
                        width:120px;
                        height:120px;
                        object-fit:cover;
                    ">

                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="tambahData()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Personel</h5>
                </div>

                <div class="modal-body row">
                    <input type="hidden" id="editIndex">

                    <div class="col-md-6">
                        <!-- NAMA -->
                        <div class="form-group">
                            <label>Nama Lengkap</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">person</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="namaEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- TEMPAT LAHIR -->
                        <div class="form-group">
                            <label>Tempat Lahir</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">location_city</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="tempatLahirEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div class="form-group">

                            <label>Tanggal Lahir</label>

                            <div class="input-group">

                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">
                                            date_range
                                        </span>
                                    </span>
                                </div>

                                <input type="date"
                                    id="tanggalLahirEdit"
                                    class="form-control">

                            </div>

                            <!-- TEXT UMUR -->
                            <small id="umurTextEdit"
                                class="text-muted d-block mt-2"
                                style="
            font-size:13px;
        ">

                                Umur akan muncul di sini

                            </small>

                        </div>

                        <!-- EMAIL -->
                        <div class="form-group">
                            <label>Email</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">email</span>
                                    </span>
                                </div>

                                <input type="email"
                                    id="emailEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- TELEPON -->
                        <div class="form-group">
                            <label>No Telp / HP</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">call</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="teleponEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- GENDER -->
                        <div class="form-group">
                            <label>Jenis Kelamin</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">wc</span>
                                    </span>
                                </div>

                                <select id="genderEdit" class="form-control">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option>Laki-laki</option>
                                    <option>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="form-group">
                            <label>Status Perkawinan</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">favorite</span>
                                    </span>
                                </div>

                                <select id="statusEdit" class="form-control">
                                    <option value="">Pilih Status</option>
                                    <option>Belum Nikah</option>
                                    <option>Menikah</option>
                                </select>
                            </div>
                        </div>

                        <!-- DEPARTEMEN -->
                        <div class="form-group">
                            <label>Departemen</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">domain</span>
                                    </span>
                                </div>

                                <select id="departmentEdit" class="form-control">
                                    <option value="">Pilih Departemen</option>
                                    <option>HR</option>
                                    <option>Admin</option>
                                    <option>Akuntansi</option>
                                </select>
                            </div>
                        </div>

                        <!-- JABATAN -->
                        <div class="form-group">
                            <label>Jabatan</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">work</span>
                                    </span>
                                </div>

                                <select id="jabatanEdit" class="form-control">
                                    <option value="">Pilih Jabatan</option>
                                    <option>Manager</option>
                                    <option>Supervisor</option>
                                    <option>Staff</option>
                                </select>
                            </div>
                        </div>

                        <!-- KTP -->
                        <div class="form-group">
                            <label>Nomor KTP</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">credit_card</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="ktpEdit"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <!-- NPWP -->
                        <div class="form-group">
                            <label>Nomor NPWP</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">credit_card</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="npwpEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- BPJS KESEHATAN -->
                        <div class="form-group">
                            <label>Nomor BPJS Kesehatan</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">credit_card</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="bpjsEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- BPJS TK -->
                        <div class="form-group">
                            <label>Nomor BPJS KetenagaKerjaan</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">credit_card</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="bpjsTKEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- KTA -->
                        <div class="form-group">
                            <label>Nomor KTA Petugas</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">assignment_ind</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="ktaEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- REKENING -->
                        <div class="form-group">
                            <label>No Rekening</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">account_balance</span>
                                    </span>
                                </div>

                                <input type="text"
                                    id="rekeningEdit"
                                    class="form-control">
                            </div>
                        </div>

                        <!-- ALAMAT -->
                        <div class="form-group">
                            <label>Alamat</label>

                            <div class="input-group">
                                <div class="input-group-prepend align-items-stretch">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons">home</span>
                                    </span>
                                </div>

                                <textarea id="alamatEdit"
                                    class="form-control"
                                    rows="5"></textarea>
                            </div>
                        </div>

                        <!-- FOTO -->
                        <div class="form-group">
                            <label>Upload Foto</label>

                            <div class="custom-file">
                                <input type="file"
                                    id="photoEdit"
                                    class="custom-file-input">

                                <label class="custom-file-label">
                                    Pilih Foto
                                </label>
                            </div>
                        </div>

                        <!-- PREVIEW -->
                        <div class="mt-4 text-center">

                            <img id="previewPhotoEdit"
                                src=""
                                class="rounded-circle shadow-sm border"
                                style="
                        width:120px;
                        height:120px;
                        object-fit:cover;
                    ">

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" onclick="updateData()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------- MODAL VIEW ------------------------------------- -->
    <div class="modal fade" id="modalView">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0">

                <div class="modal-body p-0">

                    <!-- LOADING -->
                    <div id="loadingView" class="text-center p-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Loading profile...</p>
                    </div>

                    <!-- PROFILE -->
                    <div id="contentView" style="display:none;">

                        <!-- HEADER COVER -->
                        <div class="profile-cover">
                            <div class="avatar-wrapper">
                                <img id="viewPhoto" class="profile-avatar">
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="profile-body text-center">

                            <!-- AVATAR -->
                            <!-- <img id="viewPhoto" class="profile-avatar"> -->

                            <!-- NAME -->
                            <h4 id="viewNama" class="mt-2 mb-1"></h4>
                            <span id="viewRoles" class="badge badge-pill badge-primary px-3 py-1"></span>

                            <!-- INFO GRID -->
                            <div class="profile-grid mt-4">

                                <div>
                                    <small>Tempat Lahir</small>
                                    <p id="viewTempatlahir"></p>
                                </div>

                                <div>
                                    <small>Tanggal Lahir</small>
                                    <p id="viewTanggallahir"></p>
                                </div>

                                <div>
                                    <small>Email</small>
                                    <p id="viewEmail"></p>
                                </div>

                                <div>
                                    <small>No. Telepon/HP</small>
                                    <p id="viewTelp"></p>
                                </div>

                                <div>
                                    <small>Status</small>
                                    <p id="viewStatus"></p>
                                </div>

                                <div>
                                    <small>Departemen</small>
                                    <p id="viewDepartment"></p>
                                </div>

                                <div>
                                    <small>Jabatan</small>
                                    <p id="viewJabatan"></p>
                                </div>

                                <div>
                                    <small>Jenis Kelamin</small>
                                    <p id="viewGender"></p>
                                </div>

                                <div>
                                    <small>Alamat</small>
                                    <p id="viewAlamat"></p>
                                </div>

                                <div>
                                    <small>No. KTP</small>
                                    <p id="viewKtp"></p>
                                </div>

                                <div>
                                    <small>No. KTA</small>
                                    <p id="viewKta"></p>
                                </div>

                                <div>
                                    <small>No. NPWP</small>
                                    <p id="viewNpwp"></p>
                                </div>

                                <div>
                                    <small>No. BPJS Kesehatan</small>
                                    <p id="viewBpjs"></p>
                                </div>

                                <div>
                                    <small>No. BPJS TK</small>
                                    <p id="viewBpjsTK"></p>
                                </div>

                                <div>
                                    <small>No. Rekening</small>
                                    <p id="viewRekening"></p>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- MODAL VALIDASI -->
    <div class="modal fade" id="modalValidasi">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg"
                style="
                border-radius:22px;
                overflow:hidden;
            ">

                <div class="modal-body text-center p-5">

                    <!-- ICON -->
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                        style="
                        width:90px;
                        height:90px;
                        border-radius:50%;
                        background:#fff4e5;
                    ">

                        <span class="material-icons"
                            style="
                            font-size:50px;
                            color:#ff9800;
                        ">
                            error_outline
                        </span>

                    </div>

                    <h3 class="font-weight-bold mb-2">
                        Validasi
                    </h3>

                    <p class="text-muted mb-4"
                        id="validasiText">

                        Data wajib diisi

                    </p>

                    <button class="btn btn-warning px-4"
                        data-dismiss="modal"
                        style="
                        border-radius:12px;
                        height:45px;
                        min-width:130px;
                        color:white;
                    ">
                        Mengerti
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SUKSES -->
    <div class="modal fade" id="modalSuccess">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg"
                style="
                border-radius:22px;
                overflow:hidden;
            ">

                <div class="modal-body text-center p-5">

                    <!-- ICON -->
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center"
                        style="
                        width:95px;
                        height:95px;
                        border-radius:50%;
                        background:#eafaf1;
                    ">

                        <span class="material-icons"
                            style="
                            font-size:55px;
                            color:#28a745;
                        ">
                            check_circle
                        </span>

                    </div>

                    <h3 class="font-weight-bold mb-2"
                        id="successTitle">

                        Berhasil

                    </h3>

                    <p class="text-muted mb-4"
                        id="successText">

                        Data berhasil disimpan

                    </p>

                    <button class="btn btn-success px-4"
                        data-dismiss="modal"
                        style="
                        border-radius:12px;
                        height:45px;
                        min-width:130px;
                    ">
                        OK
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
    <script src="assets/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/vendor/popper.min.js"></script>
    <script src="assets/vendor/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="assets/vendor/perfect-scrollbar.min.js"></script>

    <!-- DOM Factory -->
    <script src="assets/vendor/dom-factory.js"></script>

    <!-- MDK -->
    <script src="assets/vendor/material-design-kit.js"></script>

    <!-- App -->
    <script src="assets/js/toggle-check-all.js"></script>
    <script src="assets/js/check-selected-row.js"></script>
    <script src="assets/js/dropdown.js"></script>
    <script src="assets/js/sidebar-mini.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

    <!-- Flatpickr -->
    <script src="assets/vendor/flatpickr/flatpickr.min.js"></script>
    <script src="assets/js/flatpickr.js"></script>

    <!-- Global Settings -->
    <script src="assets/js/settings.js"></script>

    <!-- Moment.js -->
    <script src="assets/vendor/moment.min.js"></script>
    <script src="assets/vendor/moment-range.js"></script>


    <!-- Vector Maps -->
    <script src="assets/vendor/jqvmap/jquery.vmap.min.js"></script>
    <script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
    <script src="assets/js/vector-maps.js"></script>

    <script>
        let data = [{
                nik: "1234567890",
                nama: "Brian",
                tempatlahir: "Jakarta",
                tanggallahir: "1990-01-01",
                email: "brian@mail.com",
                telepon: "08123456789",
                gender: "Laki-laki",
                status: "Sudah Menikah",
                department: "HR",
                jabatan: "Admin",
                ktp: "1234567890123456",
                kta: "987654321",
                npwp: "1234567890",
                bpjs: "1234567890",
                bpjsTK: "1234567890",
                rekening: "1234567890",
                alamat: "Jakarta",
                photo: ""
            },
            {
                nik: "1234567891",
                nama: "Steven",
                tempatlahir: "Bandung",
                tanggallahir: "1990-01-01",
                email: "steven@mail.com",
                telepon: "08123456789",
                gender: "Laki-laki",
                status: "Belum Menikah",
                department: "IT",
                jabatan: "HR",
                ktp: "0987654321098765",
                kta: "123456789",
                npwp: "0987654321",
                bpjs: "0987654321",
                bpjsTK: "0987654321",
                rekening: "0987654321",
                alamat: "Bandung",
                photo: ""
            }
        ];

        let currentPage = 1;
        let rowsPerPage = 5;

        function renderTable() {
            let tbody = document.getElementById("tableBody");
            tbody.innerHTML = "";

            let search = document.getElementById("searchInput").value.toLowerCase();
            let filterDept = document.getElementById("filterDepartment").value.toLowerCase();
            let filterJabatan = document.getElementById("filterJabatan").value.toLowerCase();
            let filterGender = document.getElementById("filterGender").value.toLowerCase();

            // FILTER DATA
            let filtered = data.filter(d => {

                let nama = (d.nama || "").toLowerCase();
                let gender = (d.gender || "").toLowerCase();
                let department = (d.department || "").toLowerCase();
                let jabatan = (d.jabatan || "").toLowerCase();

                // SEARCH GLOBAL
                let matchSearch =
                    nama.includes(search) ||
                    gender.includes(search) ||
                    department.includes(search) ||
                    jabatan.includes(search);

                // FILTER DEPARTEMEN
                let matchDept =
                    filterDept === "" || department === filterDept;

                // FILTER JABATAN
                let matchJabatan =
                    filterJabatan === "" || jabatan === filterJabatan;

                // FILTER GENDER
                let matchGender =
                    filterGender === "" || gender.includes(filterGender);

                return (
                    matchSearch &&
                    matchDept &&
                    matchJabatan &&
                    matchGender
                );

            });

            // PAGINATION
            let start = (currentPage - 1) * rowsPerPage;
            let paginated = filtered.slice(start, start + rowsPerPage);

            // RENDER TABLE
            paginated.forEach((item, index) => {
                tbody.innerHTML += `
<tr>
    <td>
        <input type="checkbox" class="rowCheck" data-index="${start + index}">
    </td>

    <td>${start + index + 1}</td>

    <td>${item.nik || '-'}</td>

    <td class="d-flex align-items-center">
        <img src="${item.photo || 'assets/images/avatars/foto-sushi-128246.jpg'}"
             class="rounded-circle mr-2"
             width="40" height="40"
             style="object-fit:cover;">
        ${item.nama || '-'}
    </td>

    <td>${item.gender || '-'}</td>
    <td>${item.department || '-'}</td>
    <td>${item.jabatan || '-'}</td>

    <td>
        <button class="btn btn-info btn-sm" onclick="viewData(${start + index})">
            <i class="material-icons">remove_red_eye</i>
        </button>

        <button class="btn btn-warning btn-sm" onclick="editData(${start + index})">
            <i class="material-icons">edit</i>
        </button>

        <button class="btn btn-danger btn-sm" onclick="hapusData(${start + index})">
            <i class="material-icons">delete</i>
        </button>
    </td>
</tr>
`;
            });

            renderPagination(filtered.length);
        }

        document.getElementById("filterDepartment").onchange = function() {
            currentPage = 1;
            renderTable();
        };

        document.getElementById("filterJabatan").onchange = function() {
            currentPage = 1;
            renderTable();
        };

        document.getElementById("filterGender").onchange = function() {
            currentPage = 1;
            renderTable();
        };

        function renderPagination(total) {
            let pageCount = Math.ceil(total / rowsPerPage);
            let pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            if (pageCount <= 1) return;

            // PREV BUTTON
            pagination.innerHTML += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
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
            <li class="page-item ${i === currentPage ? 'active' : ''}">
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
        <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
            <a class="page-link" onclick="changePage(${currentPage + 1})">Next</a>
        </li>
    `;
        }

        function changePage(page) {
            currentPage = page;
            renderTable();
        }

        function tambahData() {
            let nama = document.getElementById("namaTambah").value;
            let tempatlahir = document.getElementById("tempatLahirTambah").value;
            let tanggalLahir = document.getElementById("tanggalLahirTambah").value;
            let email = document.getElementById("emailTambah").value;
            let telepon = document.getElementById("teleponTambah").value;
            let gender = document.getElementById("genderTambah").value;
            let status = document.getElementById("statusTambah").value;
            let department = document.getElementById("departmentTambah").value;
            let jabatan = document.getElementById("jabatanTambah").value;
            let ktp = document.getElementById("ktpTambah").value;
            let kta = document.getElementById("ktaTambah").value;
            let npwp = document.getElementById("npwpTambah").value;
            let bpjs = document.getElementById("bpjsTambah").value;
            let bpjsTK = document.getElementById("bpjsTKTambah").value;
            let rekening = document.getElementById("rekeningTambah").value;
            let alamat = document.getElementById("alamatTambah").value;
            let photo = document.getElementById("photoTambah").files[0];

            let photoBase64 = "";
            if (photo) {
                photoBase64 = URL.createObjectURL(photo);
            }

            if (
                !nama || !tempatlahir || !tanggalLahir ||
                !email || !telepon || !gender ||
                !status || !department || !jabatan ||
                !ktp || !npwp || !bpjs ||
                !bpjsTK || !rekening || !alamat || !photo
            ) {

                document.getElementById("validasiText").innerHTML =
                    "Semua data personel wajib diisi";

                $('#modalValidasi').modal('show');

                return;
            }

            data.push({
                nama,
                tempatlahir,
                tanggalLahir,
                email,
                telepon,
                gender,
                status,
                department,
                jabatan,
                ktp,
                npwp,
                bpjs,
                bpjsTK,
                rekening,
                alamat,
                photo: photoBase64
            });

            $('#modalTambah').modal('hide');

            renderTable();

            // TITLE
            document.getElementById("successTitle").innerHTML =
                "Tambah Berhasil";

            // TEXT
            document.getElementById("successText").innerHTML = `
    <strong>${nama}</strong> berhasil ditambahkan
`;

            // SHOW
            $('#modalSuccess').modal('show');
        }

        function editData(index) {
            let user = data[index];

            document.getElementById("editIndex").value = index;

            document.getElementById("namaEdit").value = user.nama || "";
            document.getElementById("tempatLahirEdit").value = user.tempatlahir || "";
            document.getElementById("tanggalLahirEdit").value = user.tanggallahir || "";
            // HITUNG UMUR SAAT EDIT
            if (user.tanggallahir) {

                const tanggalLahir = new Date(user.tanggallahir);
                const today = new Date();

                let umur = today.getFullYear() - tanggalLahir.getFullYear();

                const bulan = today.getMonth() - tanggalLahir.getMonth();

                if (
                    bulan < 0 ||
                    (
                        bulan === 0 &&
                        today.getDate() < tanggalLahir.getDate()
                    )
                ) {
                    umur--;
                }

                document.getElementById("umurTextEdit").innerHTML =
                    '<span class="material-icons align-middle mr-1" style="font-size:16px;">cake</span>' +
                    'Umur saat ini <b>' + umur + ' Tahun</b>';

            } else {

                document.getElementById("umurTextEdit").innerHTML =
                    'Umur akan muncul di sini';

            }
            document.getElementById("emailEdit").value = user.email || "";
            document.getElementById("teleponEdit").value = user.telepon || "";
            document.getElementById("genderEdit").value = user.gender || "";
            document.getElementById("statusEdit").value = user.status || "";
            document.getElementById("departmentEdit").value = user.department || "";
            document.getElementById("jabatanEdit").value = user.jabatan || "";
            document.getElementById("ktpEdit").value = user.ktp || "";
            document.getElementById("npwpEdit").value = user.npwp || "";
            document.getElementById("bpjsEdit").value = user.bpjs || "";
            document.getElementById("bpjsTKEdit").value = user.bpjsTK || "";
            document.getElementById("rekeningEdit").value = user.rekening || "";
            document.getElementById("ktaEdit").value = user.kta || "";
            document.getElementById("alamatEdit").value = user.alamat || "";

            document.getElementById("previewPhotoEdit").src = user.photo || 'https://via.placeholder.com/100';

            photoEditBase64 = user.photo || "";

            $('#modalEdit').modal('show');
        }

        function updateData() {
            let index = document.getElementById("editIndex").value;

            let nama = document.getElementById("namaEdit").value;
            let tempatlahir = document.getElementById("tempatLahirEdit").value;
            let tanggalLahir = document.getElementById("tanggalLahirEdit").value;
            let email = document.getElementById("emailEdit").value;
            let telepon = document.getElementById("teleponEdit").value;
            let gender = document.getElementById("genderEdit").value;
            let status = document.getElementById("statusEdit").value;
            let department = document.getElementById("departmentEdit").value;
            let jabatan = document.getElementById("jabatanEdit").value;
            let ktp = document.getElementById("ktpEdit").value;
            let kta = document.getElementById("ktaEdit").value;
            let npwp = document.getElementById("npwpEdit").value;
            let bpjs = document.getElementById("bpjsEdit").value;
            let bpjsTK = document.getElementById("bpjsTKEdit").value;
            let rekening = document.getElementById("rekeningEdit").value;
            let alamat = document.getElementById("alamatEdit").value;

            if (
                !nama || !tempatlahir || !tanggalLahir ||
                !email || !telepon || !gender ||
                !status || !department || !jabatan ||
                !ktp || !npwp || !bpjs ||
                !bpjsTK || !rekening || !alamat
            ) {

                document.getElementById("validasiText").innerHTML =
                    "Data edit personel wajib diisi";

                $('#modalValidasi').modal('show');

                return;
            }

            data[index] = {
                ...data[index],
                nama,
                tempatlahir,
                tanggalLahir,
                email,
                telepon,
                gender,
                status,
                department,
                jabatan,
                ktp,
                kta,
                npwp,
                bpjs,
                bpjsTK,
                rekening,
                alamat,
                photo: photoEditBase64 || data[index].photo
            };

            $('#modalEdit').modal('hide');

            renderTable();

            // TITLE
            document.getElementById("successTitle").innerHTML =
                "Edit Berhasil";

            // TEXT
            document.getElementById("successText").innerHTML = `
    <strong>${nama}</strong> berhasil di edit
`;

            // SHOW
            $('#modalSuccess').modal('show');
        }

        function hapusData(index) {
            if (confirm("Yakin hapus data ini?")) {
                data.splice(index, 1);
                renderTable();
            }
        }

        document.getElementById("deleteSelected").onclick = function() {
            let checks = document.querySelectorAll(".rowCheck:checked");

            if (checks.length === 0) {
                alert("Pilih data dulu!");
                return;
            }

            if (confirm("Hapus data terpilih?")) {
                let indexes = [...checks].map(c => c.dataset.index).sort((a, b) => b - a);
                indexes.forEach(i => data.splice(i, 1));
                renderTable();
            }
        };

        document.getElementById("checkAll").onclick = function() {
            document.querySelectorAll(".rowCheck").forEach(c => c.checked = this.checked);
        };

        document.getElementById("searchInput").onkeyup = renderTable;

        document.getElementById("showEntries").onchange = function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            renderTable();
        };

        renderTable();
    </script>

    <script>
        let photoBase64 = "";

        document.getElementById("photoTambah").onchange = function(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            reader.onload = function(event) {
                let img = new Image();
                img.src = event.target.result;

                img.onload = function() {
                    let canvas = document.createElement("canvas");
                    let maxSize = 300;

                    let scale = Math.min(maxSize / img.width, maxSize / img.height);
                    canvas.width = img.width * scale;
                    canvas.height = img.height * scale;

                    let ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    photoBase64 = canvas.toDataURL("image/jpeg");

                    document.getElementById("previewPhoto").src = photoBase64;
                }
            };
            reader.readAsDataURL(file);
        };
    </script>

    <script>
        function viewData(index) {

            $('#modalView').modal('show');

            document.getElementById("loadingView").style.display = "block";
            document.getElementById("contentView").style.display = "none";

            let user = data[index];

            setTimeout(() => {

                document.getElementById("viewPhoto").src =
                    user.photo || 'assets/images/avatars/foto-sushi-128246.jpg';

                document.getElementById("viewNama").innerText = user.nama;
                document.getElementById("viewTempatlahir").innerText = user.tempatlahir || '-';
                document.getElementById("viewTanggallahir").innerText = user.tanggalLahir || '-';
                document.getElementById("viewEmail").innerText = user.email;
                document.getElementById("viewTelp").innerText = user.telepon;
                document.getElementById("viewDepartment").innerText = user.department || '-';
                document.getElementById("viewJabatan").innerText = user.jabatan || '-';
                document.getElementById("viewGender").innerText = user.gender || '-';
                document.getElementById("viewStatus").innerText = user.status || '-';
                document.getElementById("viewKtp").innerText = user.ktp || '-';
                document.getElementById("viewKta").innerText = user.kta || '-';
                document.getElementById("viewNpwp").innerText = user.npwp || '-';
                document.getElementById("viewBpjs").innerText = user.bpjs || '-';
                document.getElementById("viewBpjsTK").innerText = user.bpjsTK || '-';
                document.getElementById("viewRekening").innerText = user.rekening || '-';
                document.getElementById("viewAlamat").innerText = user.alamat || '-';

                let cover = user.cover ||
                    "https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d";

                document.querySelector(".profile-cover").style.backgroundImage =
                    `url('${cover}')`;

                document.getElementById("loadingView").style.display = "none";
                document.getElementById("contentView").style.display = "block";

            }, 600);
        }
    </script>

    <script>
        let photoEditBase64 = "";

        document.getElementById("photoEdit").onchange = function(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            reader.onload = function(event) {
                let img = new Image();
                img.src = event.target.result;

                img.onload = function() {
                    let canvas = document.createElement("canvas");
                    let maxSize = 300;

                    let scale = Math.min(maxSize / img.width, maxSize / img.height);
                    canvas.width = img.width * scale;
                    canvas.height = img.height * scale;

                    let ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    photoEditBase64 = canvas.toDataURL("image/jpeg");

                    document.getElementById("previewPhotoEdit").src = photoEditBase64;
                }
            };
            reader.readAsDataURL(file);
        };
    </script>

    <script>
        // TIDAK AUTO FILL SEARCH INPUT OLEH BROWSER
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("searchInput");

            // kosongkan paksa
            input.value = "";

            // trick supaya chrome gak inject value
            input.setAttribute("readonly", true);

            setTimeout(() => {
                input.removeAttribute("readonly");
            }, 100);
        });
    </script>

    <script>
        $('#tanggalLahirTambah').on('change', function() {

            const tanggalLahir = new Date($(this).val());

            if (!$(this).val()) {

                $('#umurText').html('Umur akan muncul di sini');
                return;

            }

            const today = new Date();

            let umur = today.getFullYear() - tanggalLahir.getFullYear();

            const bulan = today.getMonth() - tanggalLahir.getMonth();

            if (
                bulan < 0 ||
                (
                    bulan === 0 &&
                    today.getDate() < tanggalLahir.getDate()
                )
            ) {
                umur--;
            }

            $('#umurText').html(
                '<span class="material-icons align-middle mr-1" style="font-size:16px;">cake</span>' +
                'Umur saat ini <b>' + umur + ' Tahun</b>'
            );

        });
    </script>

    <script>
        $('#tanggalLahirEdit').on('change', function() {

            const tanggalLahir = new Date($(this).val());

            if (!$(this).val()) {

                $('#umurTextEdit').html('Umur akan muncul di sini');
                return;

            }

            const today = new Date();

            let umur = today.getFullYear() - tanggalLahir.getFullYear();

            const bulan = today.getMonth() - tanggalLahir.getMonth();

            if (
                bulan < 0 ||
                (
                    bulan === 0 &&
                    today.getDate() < tanggalLahir.getDate()
                )
            ) {
                umur--;
            }

            $('#umurTextEdit').html(
                '<span class="material-icons align-middle mr-1" style="font-size:16px;">cake</span>' +
                'Umur saat ini <b>' + umur + ' Tahun</b>'
            );

        });
    </script>
</body>

</html>