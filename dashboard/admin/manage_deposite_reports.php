<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Laporan Deposit - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Laporan Deposit
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Laporan Deposit</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- SUMMARY -->
                <div class="card p-4 text-center mb-4">
                    <h5>Total Deposit</h5>
                    <h1 id="totalDeposit" style="font-weight:700;">Rp. 0</h1>

                    <hr>

                    <h6>Total Pengeluaran</h6>
                    <h4 id="totalPengeluaran" class="text-danger">Rp. 0</h4>
                </div>

                <!-- ===================== TABEL PEMASUKAN ===================== -->
                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h5><span class="material-icons text-success">trending_up</span> Tabel Pemasukan</h5>

                        <div class="d-flex gap-2">
                            <select id="filterBulanMasuk" class="form-control">
                                <option value="">Bulan</option>
                            </select>

                            <select id="filterTahunMasuk" class="form-control">
                                <option value="">Tahun</option>
                            </select>

                            <input type="text" id="searchMasuk" class="form-control" placeholder="Search...">
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-2" style="gap:10px;">
                        <span>Show</span>
                        <select id="entriesMasuk" class="form-control" style="width:80px;">
                            <option>5</option>
                            <option>10</option>
                            <option>25</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Sumber</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody id="tableMasuk"></tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-2">
                            <div id="paginationMasuk"></div>
                        </div>
                    </div>
                </div>

                <!-- ===================== TABEL PENGELUARAN ===================== -->
                <div class="card p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <h5><span class="material-icons text-danger">trending_down</span> Tabel Pengeluaran</h5>

                        <div class="d-flex gap-2">
                            <select id="filterBulanKeluar" class="form-control">
                                <option value="">Bulan</option>
                            </select>

                            <select id="filterTahunKeluar" class="form-control">
                                <option value="">Tahun</option>
                            </select>

                            <select id="filterTypeKeluar" class="form-control">
                                <option value="">Type</option>
                                <option>Opex</option>
                                <option>Non-Opex</option>
                            </select>

                            <input type="text" id="searchKeluar" class="form-control" placeholder="Search...">
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-2" style="gap:10px;">
                        <span>Show</span>
                        <select id="entriesKeluar" class="form-control" style="width:80px;">
                            <option>5</option>
                            <option>10</option>
                            <option>25</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Type Pengeluaran</th>
                                    <th>Judul</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody id="tableKeluar"></tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-2">
                            <div id="paginationKeluar"></div>
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

    <div id="previewModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center;">
        <img id="previewImg" style="max-width:80%; max-height:80%;">
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
        // ================= STATE =================
        let pageMasuk = 1;
        let pageKeluar = 1;

        // ================= DATA =================
        let pemasukan = [{
                tanggal: "2024-05-02",
                kategori: "Penjualan",
                sumber: "PT A",
                jumlah: 25000000,
                metode: "Transfer",
                bukti: "file.jpg"
            },
            {
                tanggal: "2024-05-10",
                kategori: "Lain-lain",
                sumber: "CV B",
                jumlah: 15000000,
                metode: "Cash",
                bukti: "file.pdf"
            }
        ];

        let pengeluaran = [{
                tanggal: "2024-05-03",
                type: "Opex",
                judul: "ATK",
                jumlah: 750000,
                deskripsi: "Beli alat",
                bukti: "file.jpg"
            },
            {
                tanggal: "2024-05-08",
                type: "Non-Opex",
                judul: "Laptop",
                jumlah: 10000000,
                deskripsi: "Pembelian asset",
                bukti: "file.pdf"
            }
        ];

        // ================= HELPER =================
        function rupiah(angka) {
            return "Rp. " + angka.toLocaleString("id-ID");
        }

        // ================= ANIMATION =================
        function animateValue(id, start, end, duration) {
            let current = start;
            let increment = end / (duration / 16);
            let obj = document.getElementById(id);

            let timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    current = end;
                    clearInterval(timer);
                }
                obj.innerText = rupiah(Math.floor(current));
            }, 16);
        }

        // ================= TOTAL =================
        function hitungTotal() {
            let totalMasuk = pemasukan.reduce((a, b) => a + b.jumlah, 0);
            let totalKeluar = pengeluaran.reduce((a, b) => a + b.jumlah, 0);

            animateValue("totalDeposit", 0, totalMasuk, 1000);
            animateValue("totalPengeluaran", 0, totalKeluar, 1000);
        }

        // ================= PREVIEW =================
        function previewImage(src) {
            document.getElementById("previewImg").src = src;
            document.getElementById("previewModal").style.display = "flex";
        }

        document.getElementById("previewModal").onclick = function() {
            this.style.display = "none";
        }

        // ================= PAGINATION =================
        function changePage(type, page) {
            if (page < 1) return;

            if (type === 'masuk') {
                pageMasuk = page;
                renderMasuk();
            } else {
                pageKeluar = page;
                renderKeluar();
            }
        }

        function renderPagination(containerId, totalPage, currentPage, type) {
            let el = document.getElementById(containerId);
            let html = "";

            let prevDisabled = currentPage === 1 ? "disabled" : "";
            let nextDisabled = currentPage === totalPage ? "disabled" : "";

            html += `<button class="pagination-btn ${prevDisabled}" 
    ${prevDisabled ? '' : `onclick="changePage('${type}', ${currentPage-1})"`}>
    Prev
  </button>`;

            for (let i = 1; i <= totalPage; i++) {
                html += `<button 
      class="pagination-btn ${i===currentPage ? 'active' : ''}"
      onclick="changePage('${type}', ${i})">${i}</button>`;
            }

            html += `<button class="pagination-btn ${nextDisabled}" 
    ${nextDisabled ? '' : `onclick="changePage('${type}', ${currentPage+1})"`}>
    Next
  </button>`;

            el.innerHTML = html;
        }

        // ================= RENDER MASUK =================
        function renderMasuk() {
            let tbody = document.getElementById("tableMasuk");
            let search = document.getElementById("searchMasuk").value.toLowerCase();
            let bulan = document.getElementById("filterBulanMasuk").value;
            let tahun = document.getElementById("filterTahunMasuk").value;

            let filtered = pemasukan.filter(d => {
                let tgl = new Date(d.tanggal);
                return (
                    (d.kategori.toLowerCase().includes(search) ||
                        d.sumber.toLowerCase().includes(search)) &&
                    (bulan === "" || (tgl.getMonth() + 1) == bulan) &&
                    (tahun === "" || tgl.getFullYear() == tahun)
                );
            });

            let limit = parseInt(document.getElementById("entriesMasuk").value);
            let totalPage = Math.ceil(filtered.length / limit) || 1;

            if (pageMasuk > totalPage) pageMasuk = totalPage;

            let start = (pageMasuk - 1) * limit;
            let data = filtered.slice(start, start + limit);

            tbody.innerHTML = "";

            data.forEach(d => {
                let fileName = d.bukti.split('/').pop();
                tbody.innerHTML += `
      <tr>
        <td>${d.tanggal}</td>
        <td>${d.kategori}</td>
        <td>${d.sumber}</td>
        <td class="text-success">${rupiah(d.jumlah)}</td>
        <td>${d.metode}</td>
        <td>
  ${
    d.bukti.endsWith('.pdf') 
    ? `<a href="${d.bukti}" target="_blank" style="display:flex; align-items:center; gap:5px;">
         <span class="material-icons text-danger">picture_as_pdf</span>
         <span>${fileName}</span>
       </a>`
    : `<div style="display:flex; align-items:center; gap:5px; cursor:pointer;" onclick="previewImage('${d.bukti}')">
         <span class="material-icons text-primary">image</span>
         <span>${fileName}</span>
       </div>`
  }
</td>
      </tr>
    `;
            });

            renderPagination("paginationMasuk", totalPage, pageMasuk, "masuk");


            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>`;

                renderPagination("paginationMasuk", 1, 1, "masuk"); // tetap tampil tapi disabled
                return;
            }
        }

        // ================= RENDER KELUAR =================
        function renderKeluar() {
            let tbody = document.getElementById("tableKeluar");
            let search = document.getElementById("searchKeluar").value.toLowerCase();
            let type = document.getElementById("filterTypeKeluar").value;

            let filtered = pengeluaran.filter(d =>
                d.judul.toLowerCase().includes(search) &&
                (type === "" || d.type === type)
            );

            let limit = parseInt(document.getElementById("entriesKeluar").value);
            let totalPage = Math.ceil(filtered.length / limit) || 1;

            if (pageKeluar > totalPage) pageKeluar = totalPage;

            let start = (pageKeluar - 1) * limit;
            let data = filtered.slice(start, start + limit);

            tbody.innerHTML = "";

            data.forEach(d => {
                let fileName = d.bukti.split('/').pop();
                tbody.innerHTML += `
      <tr>
        <td>${d.tanggal}</td>
        <td>${d.type}</td>
        <td>${d.judul}</td>
        <td class="text-danger">${rupiah(d.jumlah)}</td>
        <td>${d.deskripsi}</td>
        <td>
  ${
    d.bukti.endsWith('.pdf') 
    ? `<a href="${d.bukti}" target="_blank" style="display:flex; align-items:center; gap:5px;">
         <span class="material-icons text-danger">picture_as_pdf</span>
         <span>${fileName}</span>
       </a>`
    : `<div style="display:flex; align-items:center; gap:5px; cursor:pointer;" onclick="previewImage('${d.bukti}')">
         <span class="material-icons text-primary">image</span>
         <span>${fileName}</span>
       </div>`
  }
</td>
      </tr>
    `;
            });

            renderPagination("paginationKeluar", totalPage, pageKeluar, "keluar");

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>`;

                renderPagination("paginationKeluar", 1, 1, "keluar"); // tetap tampil tapi disabled
                return;
            }
        }

        // ================= EVENT =================
        document.querySelectorAll("input, select").forEach(el => {
            el.addEventListener("change", () => {
                pageMasuk = 1;
                pageKeluar = 1;
                renderMasuk();
                renderKeluar();
            });

            el.addEventListener("keyup", () => {
                pageMasuk = 1;
                pageKeluar = 1;
                renderMasuk();
                renderKeluar();
            });
        });

        // ================= INIT =================
        hitungTotal();
        renderMasuk();
        renderKeluar();
    </script>
</body>

</html>