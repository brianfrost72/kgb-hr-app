<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Gaji Karyawan - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Gaji Karyawan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Gaji Karyawan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- TITLE -->
                <div class="mb-3">
                    <h4>Tambah Penggajian Karyawan</h4>
                    <small class="text-muted">Input data gaji, bonus, lembur dan potongan</small>
                </div>

                <!-- FORM -->
                <div class="card p-3 mb-4" style="background:#fff;border-radius:8px;">

                    <!-- TOP FORM -->
                    <div class="row mb-4 pb-3" style="border-bottom:1px solid #ECEEF0;">

                        <div class="col-md-3">
                            <label>Karyawan</label>
                            <select class="form-control">
                                <option>Pilih Karyawan</option>
                                <option>Andi</option>
                                <option>Budi</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Periode</label>
                            <input type="month" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Tanggal Bayar</label>
                            <input type="date" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Metode Pembayaran</label>
                            <select class="form-control">
                                <option>Transfer Bank</option>
                                <option>Cash</option>
                            </select>
                        </div>

                    </div>

                    <!-- MAIN CONTENT -->
                    <div class="row">

                        <!-- PENDAPATAN -->
                        <div class="col-md-4" style="border-right:1px solid #ECEEF0;">
                            <h6 class="mb-3 text-success">Pendapatan</h6>

                            <label>Gaji Pokok</label>
                            <input type="number" class="form-control mb-2 rupiah-input pendapatan-input">

                            <label>Tunjangan</label>
                            <input type="number" class="form-control mb-2 rupiah-input pendapatan-input">

                            <label>Bonus</label>
                            <input type="number" class="form-control mb-2 rupiah-input pendapatan-input">

                            <label>Lembur</label>
                            <input type="number" class="form-control mb-3 rupiah-input pendapatan-input">

                            <div class="p-2" style="background:#FAFBFE;border-radius:6px;">
                                <strong>Total Pendapatan</strong>
                                <span id="totalPendapatan" class="float-right text-success">Rp 0</span>
                            </div>
                        </div>

                        <!-- POTONGAN -->
                        <div class="col-md-4" style="border-right:1px solid #ECEEF0;">
                            <h6 class="mb-3 text-danger">Potongan</h6>

                            <label>BPJS Kesehatan</label>
                            <input type="number" class="form-control mb-2 rupiah-input potongan-input">

                            <label>BPJS Ketenagakerjaan</label>
                            <input type="number" class="form-control mb-2 rupiah-input potongan-input">

                            <label>PPh 21</label>
                            <input type="number" class="form-control mb-2 rupiah-input potongan-input">

                            <label>Lain-lain</label>
                            <input type="number" class="form-control mb-3 rupiah-input potongan-input">

                            <div class="p-2" style="background:#FAFBFE;border-radius:6px;">
                                <strong>Total Potongan</strong>
                                <span id="totalPotongan" class="float-right text-danger">Rp 0</span>
                            </div>
                        </div>

                        <!-- RINGKASAN -->
                        <div class="col-md-4">
                            <h6 class="mb-3">Ringkasan</h6>

                            <div class="p-3 mb-3" style="background:#FAFBFE;border-radius:8px;">
                                <p>Total Pendapatan
                                    <span id="summaryPendapatan" class="float-right text-success">Rp 0</span>
                                </p>
                                <p>Total Potongan
                                    <span id="summaryPotongan" class="float-right text-danger">Rp 0</span>
                                </p>

                                <hr>

                                <h5>Take Home Pay
                                    <span id="takeHomePay" class="float-right text-primary">Rp 0</span>
                                </h5>
                            </div>

                            <textarea class="form-control mb-3" placeholder="Catatan..."></textarea>

                            <div class="text-right">
                                <button class="btn btn-light">Reset</button>
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="card p-3" style="background:#fff;border-radius:8px;">

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                        <!-- TITLE -->
                        <div>
                            <h6 class="mb-0">List Penggajian</h6>
                        </div>

                        <!-- FILTER -->
                        <div class="d-flex align-items-center flex-wrap">

                            <!-- SHOW ENTRIES -->
                            <div class="mr-2">
                                <select id="showEntries"
                                    class="form-control"
                                    style="width:90px;">

                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>

                                </select>
                            </div>

                            <!-- FILTER DIVISI -->
                            <div class="mr-2">
                                <select id="filterDivisi"
                                    class="form-control"
                                    style="width:170px;">

                                    <option value="">Semua Divisi</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="IT">IT</option>
                                    <option value="Finance">Finance</option>
                                    <option value="HRD">HRD</option>

                                </select>
                            </div>

                            <!-- FILTER PERIODE -->
                            <div class="mr-2">
                                <input type="month"
                                    id="filterPeriode"
                                    class="form-control"
                                    style="width:170px;">
                            </div>

                            <!-- SEARCH -->
                            <div>
                                <input type="text"
                                    id="searchInput"
                                    class="form-control"
                                    style="width:200px;"
                                    placeholder="Cari...">
                            </div>

                        </div>

                    </div>

                    <!-- TABLE -->
                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Karyawan</th>
                                    <th>Divisi</th>
                                    <th>Periode</th>
                                    <th>Tgl Bayar</th>
                                    <th>Total</th>
                                    <th>Potongan</th>
                                    <th>Take Home Pay</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="tableBody">

                                <tr>
                                    <td>1</td>
                                    <td>Andi</td>
                                    <td>Marketing</td>
                                    <td>Mei 2025</td>
                                    <td>31/05/2025</td>

                                    <td class="text-success">
                                        Rp 8.000.000
                                    </td>

                                    <td class="text-danger">
                                        Rp 1.000.000
                                    </td>

                                    <td class="text-primary">
                                        Rp 7.000.000
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            View
                                        </button>

                                        <button class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                        <!-- PAGINATION -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

                            <!-- INFO -->
                            <div id="paginationInfo" class="mb-2">
                                Menampilkan 1 sampai 10 dari 100 data
                            </div>

                            <!-- PAGINATION -->
                            <div class="d-flex align-items-center flex-wrap" id="pagination">

                                <!-- generated by js -->

                            </div>

                        </div>

                        <!-- EXPORT -->
                        <button class="btn btn-primary"
                            onclick="exportExcel()">

                            Export Excel

                        </button>
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

    <!-- DETAIL VIEW -->
    <div id="detailPayrollCard"
        class="card p-3 mt-4"
        style="display:none;background:#fff;border-radius:8px;">

        <!-- TITLE -->
        <div class="mb-3">
            <h4>Detail Penggajian Karyawan</h4>

            <small class="text-muted">
                Informasi lengkap data penggajian
            </small>
        </div>

        <!-- FORM -->
        <div class="card p-3"
            style="background:#fff;border-radius:8px;">

            <!-- TOP FORM -->
            <div class="row mb-4 pb-3"
                style="border-bottom:1px solid #ECEEF0;">

                <div class="col-md-3">
                    <label>Karyawan</label>

                    <input type="text" id="detailNama"
                        class="form-control" readonly>
                    </input>
                </div>

                <div class="col-md-3">
                    <label>Periode</label>

                    <input type="text" id="detailPeriode"
                        class="form-control" readonly>
                    </input>
                </div>

                <div class="col-md-3">
                    <label>Tanggal Bayar</label>

                    <input type="date" id="detailTanggal"
                        class="form-control" readonly>
                    </input>
                </div>

                <div class="col-md-3">
                    <label>Metode Pembayaran</label>

                    <select id="detailMetode"
                        class="form-control" disabled>
                        <option>Transfer Bank</option>
                        <option>Cash</option>
                    </select>
                </div>

            </div>

            <!-- MAIN CONTENT -->
            <div class="row">

                <!-- PENDAPATAN -->
                <div class="col-md-4"
                    style="border-right:1px solid #ECEEF0;">

                    <h6 class="mb-3 text-success">
                        Pendapatan
                    </h6>

                    <label>Gaji Pokok</label>

                    <input type="text" id="detailGaji"
                        class="form-control mb-2 detail-rupiah" readonly>
                    </input>

                    <label>Tunjangan</label>

                    <input type="text" id="detailTunjangan"
                        class="form-control mb-2 detail-rupiah" readonly>
                    </input>

                    <label>Bonus</label>

                    <input type="text" id="detailBonus"
                        class="form-control mb-2 detail-rupiah" readonly>
                    </input>

                    <label>Lembur</label>

                    <input type="text" id="detailLembur"
                        class="form-control mb-3 detail-rupiah" readonly>
                    </input>

                    <div class="p-2"
                        style="background:#FAFBFE;border-radius:6px;">

                        <strong>Total Pendapatan</strong>

                        <span id="detailTotalPendapatan"
                            class="float-right text-success">

                        </span>

                    </div>

                </div>

                <!-- POTONGAN -->
                <div class="col-md-4"
                    style="border-right:1px solid #ECEEF0;">

                    <h6 class="mb-3 text-danger">
                        Potongan
                    </h6>

                    <label>BPJS Kesehatan</label>

                    <input type="text" id="detailBPJS"
                        class="form-control mb-2 detail-rupiah" readonly>
                    </input>

                    <label>BPJS Ketenagakerjaan</label>

                    <input type="text" id="detailBPJSTK"
                        class="form-control mb-2 detail-rupiah" readonly>
                    </input>

                    <label>PPh 21</label>

                    <input type="text" id="detailPPH"
                        class="form-control mb-2 detail-rupiah" readonly>
                    </input>

                    <label>Lain-lain</label>

                    <input type="text" id="detailLain"
                        class="form-control mb-3 detail-rupiah" readonly>
                    </input>

                    <div class="p-2"
                        style="background:#FAFBFE;border-radius:6px;">

                        <strong>Total Potongan</strong>

                        <span id="detailTotalPotongan"
                            class="float-right text-danger">

                        </span>

                    </div>

                </div>

                <!-- RINGKASAN -->
                <div class="col-md-4">

                    <h6 class="mb-3">
                        Ringkasan
                    </h6>

                    <div class="p-3 mb-3"
                        style="background:#FAFBFE;border-radius:8px;">

                        <p>
                            Total Pendapatan

                            <span id="summaryPendapatanView"
                                class="float-right text-success">

                            </span>
                        </p>

                        <p>
                            Total Potongan

                            <span id="summaryPotonganView"
                                class="float-right text-danger">

                            </span>
                        </p>

                        <hr>

                        <h5>
                            Take Home Pay

                            <span id="summaryTakeHomeView"
                                class="float-right text-primary">

                            </span>
                        </h5>

                    </div>

                    <label>Catatan</label>

                    <textarea id="detailCatatan"
                        class="form-control mb-3"
                        readonly>
                </textarea>

                    <div class="text-right">

                        <!-- EDIT -->
                        <button id="btnEditPayroll"
                            class="btn btn-warning"
                            onclick="toggleEditPayroll()">

                            Edit

                        </button>

                        <!-- CANCEL -->
                        <button id="btnCancelEdit"
                            class="btn btn-light"
                            onclick="cancelEditPayroll()"
                            style="display:none;">

                            Batal

                        </button>

                        <button class="btn btn-secondary"
                            onclick="printSlipGaji()">

                            Print Slip

                        </button>

                        <button class="btn btn-light"
                            onclick="closeDetailPayroll()">

                            Tutup

                        </button>

                    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <!-- Moment.js -->
    <script src="assets/vendor/moment.min.js"></script>
    <script src="assets/vendor/moment-range.js"></script>


    <!-- Vector Maps -->
    <script src="assets/vendor/jqvmap/jquery.vmap.min.js"></script>
    <script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
    <script src="assets/js/vector-maps.js"></script>

    <script>
        // FORMAT RUPIAH
        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            return rupiah ? "Rp " + rupiah : "";
        }

        // AMBIL ANGKA
        function getNumber(value) {
            return parseInt(value.replace(/[^0-9]/g, "")) || 0;
        }

        // INPUT
        const pendapatanInputs = document.querySelectorAll(".pendapatan-input");
        const potonganInputs = document.querySelectorAll(".potongan-input");

        // TOTAL
        const totalPendapatan = document.getElementById("totalPendapatan");
        const totalPotongan = document.getElementById("totalPotongan");

        const summaryPendapatan = document.getElementById("summaryPendapatan");
        const summaryPotongan = document.getElementById("summaryPotongan");

        const takeHomePay = document.getElementById("takeHomePay");

        // FORMAT INPUT
        document.querySelectorAll(".rupiah-input").forEach(input => {

            input.setAttribute("type", "text");

            input.addEventListener("input", function() {

                let angka = this.value.replace(/[^0-9]/g, "");
                this.value = formatRupiah(angka);

                hitungTotal();
            });

        });

        // HITUNG TOTAL
        function hitungTotal() {

            let totalP = 0;
            let totalPot = 0;

            pendapatanInputs.forEach(input => {
                totalP += getNumber(input.value);
            });

            potonganInputs.forEach(input => {
                totalPot += getNumber(input.value);
            });

            let takeHome = totalP - totalPot;

            totalPendapatan.innerHTML = formatRupiah(totalP.toString());
            totalPotongan.innerHTML = formatRupiah(totalPot.toString());

            summaryPendapatan.innerHTML = formatRupiah(totalP.toString());
            summaryPotongan.innerHTML = formatRupiah(totalPot.toString());

            takeHomePay.innerHTML = formatRupiah(takeHome.toString());
        }

        // RESET
        document.querySelector(".btn-light").addEventListener("click", function() {

            document.querySelectorAll(".rupiah-input").forEach(input => {
                input.value = "";
            });

            document.querySelector("textarea").value = "";

            totalPendapatan.innerHTML = "Rp 0";
            totalPotongan.innerHTML = "Rp 0";

            summaryPendapatan.innerHTML = "Rp 0";
            summaryPotongan.innerHTML = "Rp 0";

            takeHomePay.innerHTML = "Rp 0";
        });
    </script>

    <script>
        // DATA
        let payrollData = [];

        // DUMMY DATA
        for (let i = 1; i <= 120; i++) {

            payrollData.push({
                nama: "Karyawan " + i,
                divisi: i % 3 == 0 ? "Finance" : i % 2 == 0 ? "IT" : "Marketing",

                periode: i % 2 == 0 ? "2025-05" : "2025-06",

                tanggal: "31/05/2025",

                total: 8000000 + (i * 10000),

                potongan: 1000000
            });

        }

        // ELEMENT
        const tableBody = document.getElementById("tableBody");
        const showEntries = document.getElementById("showEntries");
        const filterDivisi = document.getElementById("filterDivisi");
        const filterPeriode = document.getElementById("filterPeriode");
        const searchInput = document.getElementById("searchInput");

        const pagination = document.getElementById("pagination");
        const paginationInfo = document.getElementById("paginationInfo");

        // PAGE
        let currentPage = 1;

        // FORMAT RUPIAH
        function rupiah(number) {

            return "Rp " + number.toLocaleString("id-ID");

        }

        // RENDER TABLE
        function renderTable(page = 1) {

            currentPage = page;

            let search = searchInput.value.toLowerCase();
            let divisi = filterDivisi.value;
            let periode = filterPeriode.value;
            let limit = parseInt(showEntries.value);

            // FILTER
            let filtered = payrollData.filter(item => {

                let matchSearch =
                    item.nama.toLowerCase().includes(search);

                let matchDivisi =
                    divisi === "" || item.divisi === divisi;

                let matchPeriode =
                    periode === "" || item.periode === periode;

                return matchSearch &&
                    matchDivisi &&
                    matchPeriode;

            });

            // TOTAL PAGE
            let totalPages = Math.ceil(filtered.length / limit);

            // START
            let start = (page - 1) * limit;

            // END
            let end = start + limit;

            // SLICE
            let paginated = filtered.slice(start, end);

            // RESET
            tableBody.innerHTML = "";

            // EMPTY
            if (paginated.length === 0) {

                tableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center">
                        Data tidak ditemukan
                    </td>
                </tr>
            `;

                pagination.innerHTML = "";
                paginationInfo.innerHTML = "";

                return;
            }

            // LOOP
            paginated.forEach((item, index) => {

                let takeHome = item.total - item.potongan;

                let periodeText = new Date(item.periode + "-01")
                    .toLocaleDateString("id-ID", {
                        month: "long",
                        year: "numeric"
                    });

                tableBody.innerHTML += `
                <tr>

                    <td>${start + index + 1}</td>

                    <td>${item.nama}</td>

                    <td>${item.divisi}</td>

                    <td>${periodeText}</td>

                    <td>${item.tanggal}</td>

                    <td class="text-success">
                        ${rupiah(item.total)}
                    </td>

                    <td class="text-danger">
                        ${rupiah(item.potongan)}
                    </td>

                    <td class="text-primary">
                        ${rupiah(takeHome)}
                    </td>

                    <td>

                        <button class="btn btn-sm btn-primary"
        onclick="viewPayroll(${index})">

    View

</button>

                       <button class="btn btn-sm btn-danger"
        onclick="hapusPayroll(${start + index})">

    Hapus

</button>

                    </td>

                </tr>
            `;
            });

            // INFO
            paginationInfo.innerHTML = `
            Menampilkan
            ${start + 1}
            sampai
            ${Math.min(end, filtered.length)}
            dari
            ${filtered.length} data
        `;

            renderPagination(totalPages, page);
        }

        function hapusPayroll(index) {

            // AMBIL DATA
            let item = payrollData[index];

            // VALIDASI
            let konfirmasi = confirm(
                "Yakin ingin menghapus data penggajian:\n\n" +
                item.nama +
                " ?"
            );

            // CANCEL
            if (!konfirmasi) {

                return;

            }

            // HAPUS DATA
            payrollData.splice(index, 1);

            // TUTUP DETAIL JIKA TERBUKA
            document.getElementById("detailPayrollCard")
                .style.display = "none";

            // REFRESH TABLE
            renderTable(currentPage);

            // ALERT
            alert("Data berhasil dihapus!");

        }

        // PAGINATION
        function renderPagination(totalPages, currentPage) {

            pagination.innerHTML = "";

            // BUTTON
            function createButton(page) {

                return `
                <button
                    onclick="renderTable(${page})"
                    class="pagination-btn ${page === currentPage ? 'active' : ''}">
                    ${page}
                </button>
            `;
            }

            // FIRST
            for (let i = 1; i <= Math.min(3, totalPages); i++) {

                pagination.innerHTML += createButton(i);

            }

            // DOTS START
            if (currentPage > 5) {

                pagination.innerHTML += `
                <span class="pagination-dots">
                    ...
                </span>
            `;
            }

            // MIDDLE
            let startPage = Math.max(4, currentPage - 1);
            let endPage = Math.min(totalPages - 3, currentPage + 1);

            for (let i = startPage; i <= endPage; i++) {

                pagination.innerHTML += createButton(i);

            }

            // DOTS END
            if (currentPage < totalPages - 4) {

                pagination.innerHTML += `
                <span class="pagination-dots">
                    ...
                </span>
            `;
            }

            // LAST
            for (let i = Math.max(totalPages - 2, 4); i <= totalPages; i++) {

                pagination.innerHTML += createButton(i);

            }

        }

        // EVENTS
        showEntries.addEventListener("change", () => {

            renderTable(1);

        });

        filterDivisi.addEventListener("change", () => {

            renderTable(1);

        });

        filterPeriode.addEventListener("input", () => {

            renderTable(1);

        });

        searchInput.addEventListener("input", () => {

            renderTable(1);

        });

        // FIRST LOAD
        renderTable();
    </script>
    <script>
        // EXPORT EXCEL
        function exportExcel() {

            // FILTER DATA
            let search =
                searchInput.value.toLowerCase();

            let divisi =
                filterDivisi.value;

            let periode =
                filterPeriode.value;

            let filtered = payrollData.filter(item => {

                let matchSearch =
                    item.nama.toLowerCase()
                    .includes(search);

                let matchDivisi =
                    divisi === "" ||
                    item.divisi === divisi;

                let matchPeriode =
                    periode === "" ||
                    item.periode === periode;

                return matchSearch &&
                    matchDivisi &&
                    matchPeriode;

            });

            // WORKBOOK
            const workbook =
                XLSX.utils.book_new();

            // GROUP PER BULAN
            const grouped = {};

            filtered.forEach(item => {

                let date =
                    new Date(item.periode + "-01");

                let month =
                    date.toLocaleDateString("id-ID", {
                        month: "short"
                    });

                let year =
                    date.toLocaleDateString("id-ID", {
                        year: "2-digit"
                    });

                let sheetName =
                    month + year;

                if (!grouped[sheetName]) {

                    grouped[sheetName] = [];

                }

                grouped[sheetName].push({

                    Karyawan: item.nama,

                    Divisi: item.divisi,

                    Periode: item.periode,

                    "Tgl Bayar": item.tanggal,

                    Total: item.total,

                    Potongan: item.potongan,

                    "Take Home Pay": item.total - item.potongan

                });

            });

            // SHEETS
            for (let sheet in grouped) {

                const worksheet =
                    XLSX.utils.json_to_sheet(
                        grouped[sheet]
                    );

                XLSX.utils.book_append_sheet(
                    workbook,
                    worksheet,
                    sheet
                );

            }

            // EXPORT
            XLSX.writeFile(
                workbook,
                "Data_Penggajian.xlsx"
            );

        }
    </script>

    <script>
        let currentEditIndex = null;
        let editMode = false;

        // VIEW DETAIL
        function viewPayroll(realIndex) {

            currentEditIndex = realIndex;

            let item = payrollData[realIndex];

            // SHOW CARD
            document.getElementById(
                "detailPayrollCard"
            ).style.display = "block";

            // TOP
            document.getElementById(
                "detailNama"
            ).value = item.nama;

            document.getElementById(
                "detailPeriode"
            ).value = item.periode;

            // FORMAT DATE
            let splitDate =
                item.tanggal.split("/");

            let formatDate =
                splitDate[2] + "-" +
                splitDate[1] + "-" +
                splitDate[0];

            document.getElementById(
                "detailTanggal"
            ).value = formatDate;

            // METODE
            document.getElementById(
                    "detailMetode"
                ).value =
                item.metode || "Transfer Bank";

            // PENDAPATAN
            detailGaji.value =
                rupiah(item.gaji || 5000000);

            detailTunjangan.value =
                rupiah(item.tunjangan || 1000000);

            detailBonus.value =
                rupiah(item.bonus || 1000000);

            detailLembur.value =
                rupiah(item.lembur || 500000);

            // POTONGAN
            detailBPJS.value =
                rupiah(item.bpjs || 300000);

            detailBPJSTK.value =
                rupiah(item.bpjstk || 200000);

            detailPPH.value =
                rupiah(item.pph || 500000);

            detailLain.value =
                rupiah(item.lain || 0);

            // CATATAN
            detailCatatan.value =
                item.catatan ||
                "";

            // HITUNG TOTAL
            hitungDetailTotal();

            // DEFAULT LOCK
            lockEditMode();

            // BUTTON RESET
            document.getElementById(
                "btnEditPayroll"
            ).innerHTML = "Edit";

            document.getElementById(
                "btnEditPayroll"
            ).classList.remove(
                "btn-success"
            );

            document.getElementById(
                "btnEditPayroll"
            ).classList.add(
                "btn-warning"
            );

            document.getElementById(
                "btnCancelEdit"
            ).style.display = "none";

            editMode = false;

            // SCROLL
            document.getElementById(
                "detailPayrollCard"
            ).scrollIntoView({
                behavior: "smooth"
            });

        }

        // LOCK EDIT
        function lockEditMode() {

            // JANGAN EDIT
            detailNama.readOnly = true;
            detailPeriode.readOnly = true;

            // BOLEH EDIT
            detailTanggal.readOnly = true;

            detailMetode.disabled = true;

            detailGaji.readOnly = true;
            detailTunjangan.readOnly = true;
            detailBonus.readOnly = true;
            detailLembur.readOnly = true;

            detailBPJS.readOnly = true;
            detailBPJSTK.readOnly = true;
            detailPPH.readOnly = true;
            detailLain.readOnly = true;

            detailCatatan.readOnly = true;

        }

        // UNLOCK EDIT
        function unlockEditMode() {

            // TETAP LOCK
            detailNama.readOnly = true;
            detailPeriode.readOnly = true;

            // BOLEH EDIT
            detailTanggal.readOnly = false;

            detailMetode.disabled = false;

            detailGaji.readOnly = false;
            detailTunjangan.readOnly = false;
            detailBonus.readOnly = false;
            detailLembur.readOnly = false;

            detailBPJS.readOnly = false;
            detailBPJSTK.readOnly = false;
            detailPPH.readOnly = false;
            detailLain.readOnly = false;

            detailCatatan.readOnly = false;

        }

        // TOTAL DETAIL
        function hitungDetailTotal() {

            let totalPendapatan =

                getNumber(detailGaji.value) +

                getNumber(detailTunjangan.value) +

                getNumber(detailBonus.value) +

                getNumber(detailLembur.value);

            let totalPotongan =

                getNumber(detailBPJS.value) +

                getNumber(detailBPJSTK.value) +

                getNumber(detailPPH.value) +

                getNumber(detailLain.value);

            let takeHome =
                totalPendapatan -
                totalPotongan;

            // UPDATE UI
            detailTotalPendapatan.innerHTML =
                rupiah(totalPendapatan);

            detailTotalPotongan.innerHTML =
                rupiah(totalPotongan);

            summaryPendapatanView.innerHTML =
                rupiah(totalPendapatan);

            summaryPotonganView.innerHTML =
                rupiah(totalPotongan);

            summaryTakeHomeView.innerHTML =
                rupiah(takeHome);

        }

        // FORMAT INPUT EDIT
        document
            .querySelectorAll(".detail-rupiah")
            .forEach(input => {

                input.addEventListener(
                    "input",
                    function() {

                        // HANYA ANGKA
                        let angka =
                            this.value.replace(
                                /[^0-9]/g,
                                ""
                            );

                        // FORMAT RP
                        this.value =
                            formatRupiah(angka);

                        // UPDATE TOTAL
                        hitungDetailTotal();

                    }
                );

            });

        // TOGGLE EDIT
        function toggleEditPayroll() {

            let btn =
                document.getElementById(
                    "btnEditPayroll"
                );

            // EDIT MODE
            if (!editMode) {

                editMode = true;

                unlockEditMode();

                btn.innerHTML = "Simpan";

                btn.classList.remove(
                    "btn-warning"
                );

                btn.classList.add(
                    "btn-success"
                );

                btnCancelEdit.style.display =
                    "inline-block";

            }

            // SAVE
            else {

                let item =
                    payrollData[currentEditIndex];

                // SAVE
                item.tanggal =
                    detailTanggal.value
                    .split("-")
                    .reverse()
                    .join("/");

                item.metode =
                    detailMetode.value;

                item.gaji =
                    getNumber(detailGaji.value);

                item.tunjangan =
                    getNumber(detailTunjangan.value);

                item.bonus =
                    getNumber(detailBonus.value);

                item.lembur =
                    getNumber(detailLembur.value);

                item.bpjs =
                    getNumber(detailBPJS.value);

                item.bpjstk =
                    getNumber(detailBPJSTK.value);

                item.pph =
                    getNumber(detailPPH.value);

                item.lain =
                    getNumber(detailLain.value);

                item.catatan =
                    detailCatatan.value;

                // TOTAL
                item.total =

                    item.gaji +

                    item.tunjangan +

                    item.bonus +

                    item.lembur;

                item.potongan =

                    item.bpjs +

                    item.bpjstk +

                    item.pph +

                    item.lain;

                // LOCK
                lockEditMode();

                // BUTTON
                editMode = false;

                btn.innerHTML = "Edit";

                btn.classList.remove(
                    "btn-success"
                );

                btn.classList.add(
                    "btn-warning"
                );

                btnCancelEdit.style.display =
                    "none";

                // REFRESH
                renderTable(currentPage);

                // REFRESH DETAIL
                viewPayroll(currentEditIndex);

                alert(
                    "Data berhasil diperbarui!"
                );

            }

        }

        // CANCEL
        function cancelEditPayroll() {

            editMode = false;

            viewPayroll(currentEditIndex);

        }

        function closeDetailPayroll(){

        // HIDE CARD
        document.getElementById(
            "detailPayrollCard"
        ).style.display = "none";

        // RESET EDIT MODE
        editMode = false;

        // RESET BUTTON
        document.getElementById(
            "btnEditPayroll"
        ).innerHTML = "Edit";

        document.getElementById(
            "btnEditPayroll"
        ).classList.remove(
            "btn-success"
        );

        document.getElementById(
            "btnEditPayroll"
        ).classList.add(
            "btn-warning"
        );

        // HIDE CANCEL
        document.getElementById(
            "btnCancelEdit"
        ).style.display = "none";

        // LOCK INPUT
        lockEditMode();

    }

    </script>

    <script>
        function printSlipGaji() {

            let nama = document.getElementById("detailNama").innerHTML;
            let periode = document.getElementById("detailPeriode").innerHTML;
            let tanggal = document.getElementById("detailTanggal").innerHTML;
            let metode = document.getElementById("detailMetode").innerHTML;

            let gaji = document.getElementById("detailGaji").innerHTML;
            let tunjangan = document.getElementById("detailTunjangan").innerHTML;
            let bonus = document.getElementById("detailBonus").innerHTML;
            let lembur = document.getElementById("detailLembur").innerHTML;

            let bpjs = document.getElementById("detailBPJS").innerHTML;
            let bpjstk = document.getElementById("detailBPJSTK").innerHTML;
            let pph = document.getElementById("detailPPH").innerHTML;
            let lain = document.getElementById("detailLain").innerHTML;

            let totalPendapatan =
                document.getElementById("detailTotalPendapatan").innerHTML;

            let totalPotongan =
                document.getElementById("detailTotalPotongan").innerHTML;

            let takeHome =
                document.getElementById("summaryTakeHomeView").innerHTML;

            let catatan =
                document.getElementById("detailCatatan").value;

            let printWindow = window.open('', '', 'width=400,height=800');

            printWindow.document.write(`

            <html>

            <head>

                <title>Slip Gaji</title>

                <style>

                    body{

                        font-family:Arial,sans-serif;
                        padding:20px;

                        color:#112b4a;
                    }

                    .slip{

                        border:1px solid #ddd;
                        border-radius:10px;

                        padding:20px;
                    }

                    .title{

                        text-align:center;
                        margin-bottom:20px;
                    }

                    .title h2{

                        margin:0;
                    }

                    .line{

                        border-bottom:1px dashed #ccc;
                        margin:15px 0;
                    }

                    .row{

                        display:flex;
                        justify-content:space-between;

                        margin-bottom:8px;
                        font-size:14px;
                    }

                    .section-title{

                        font-weight:bold;
                        margin-top:15px;
                        margin-bottom:10px;

                        color:#6774DF;
                    }

                    .total{

                        font-weight:bold;
                    }

                    .take-home{

                        font-size:18px;
                        font-weight:bold;

                        color:#6774DF;
                    }

                    .footer{

                        margin-top:25px;
                        font-size:12px;

                        text-align:center;
                        color:#777;
                    }

                    @media print{

                        body{
                            padding:0;
                        }

                        .slip{
                            border:none;
                        }

                    }

                </style>

            </head>

            <body>

                <div class="slip">

                    <div class="title-slip"
     style="
        text-align:center;
        margin-bottom:15px;
     ">

    <!-- LOGO -->
    <img
        src="assets/images/logos/logo-light.png"
        alt="Logo"
        style="
            width:250px;
            height:auto;
            display:block;
            margin:0 auto 8px auto;
            object-fit:contain;
        ">

    <h2 style="
        margin:0;
        font-size:24px;
        color:#112b4a;
        font-weight:700;
    ">
        SLIP GAJI
    </h2>

    <small style="
        color:#777;
        font-size:13px;
    ">
        Konig Guard Bureau
    </small>

</div>

                    <div class="line"></div>

                    <div class="row">
                        <span>Karyawan</span>
                        <span>${nama}</span>
                    </div>

                    <div class="row">
                        <span>Periode</span>
                        <span>${periode}</span>
                    </div>

                    <div class="row">
                        <span>Tanggal Bayar</span>
                        <span>${tanggal}</span>
                    </div>

                    <div class="row">
                        <span>Metode</span>
                        <span>${metode}</span>
                    </div>

                    <div class="line"></div>

                    <div class="section-title">
                        Pendapatan
                    </div>

                    <div class="row">
                        <span>Gaji Pokok</span>
                        <span>${gaji}</span>
                    </div>

                    <div class="row">
                        <span>Tunjangan</span>
                        <span>${tunjangan}</span>
                    </div>

                    <div class="row">
                        <span>Bonus</span>
                        <span>${bonus}</span>
                    </div>

                    <div class="row">
                        <span>Lembur</span>
                        <span>${lembur}</span>
                    </div>

                    <div class="row total">
                        <span>Total Pendapatan</span>
                        <span>${totalPendapatan}</span>
                    </div>

                    <div class="line"></div>

                    <div class="section-title">
                        Potongan
                    </div>

                    <div class="row">
                        <span>BPJS Kesehatan</span>
                        <span>${bpjs}</span>
                    </div>

                    <div class="row">
                        <span>BPJS TK</span>
                        <span>${bpjstk}</span>
                    </div>

                    <div class="row">
                        <span>PPh 21</span>
                        <span>${pph}</span>
                    </div>

                    <div class="row">
                        <span>Lain-lain</span>
                        <span>${lain}</span>
                    </div>

                    <div class="row total">
                        <span>Total Potongan</span>
                        <span>${totalPotongan}</span>
                    </div>

                    <div class="line"></div>

                    <div class="row take-home">
                        <span>Take Home Pay</span>
                        <span>${takeHome}</span>
                    </div>

                    <div class="line"></div>

                    <div style="font-size:13px;">
                        <strong>Catatan:</strong><br>
                        ${catatan}
                    </div>

                    <div class="footer">

                        Slip ini dicetak otomatis oleh sistem payroll.

                    </div>

                </div>

                <script>

                    window.onload = function(){

                        window.print();

                    }

                <\/script>

            </body>

            </html>

        `);

            printWindow.document.close();

        }
    </script>

</body>

</html>