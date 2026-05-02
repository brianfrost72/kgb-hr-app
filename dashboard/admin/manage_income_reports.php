<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Manage Laporan Pemasukan - Dashboard | Konig Guard Bureau</title>

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
                                        Manage Laporan Pemasukan
                                    </li>
                                </ol>
                            </nav>
                            <h1 class="m-0">Manage Laporan Pemasukan</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // END page__header -->

            <!-- ********************************// START page__content //******************************* -->
            <div class="container-fluid page__container">
                <!-- ================= FORM INPUT ================= -->
                <div class="card mb-4">
                    <div class="card-body">

                        <h4 class="mb-3">
                            <span class="material-icons">add_circle</span>
                            Tambah Laporan Pemasukan
                        </h4>

                        <div class="row">
                            <div class="col-md-3">
                                <label>Tanggal</label>
                                <input type="date" id="tanggal" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Kategori</label>
                                <select id="kategori" class="form-control">
                                    <option>Penjualan</option>
                                    <option>Jasa</option>
                                    <option>Lain-lain</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Sumber</label>
                                <input type="text" id="sumber" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Jumlah (Rp)</label>
                                <input type="text" id="jumlah" class="form-control" onkeyup="formatInputRupiah(this)">
                            </div>

                            <div class="col-md-6 mt-3">
                                <label>Metode Pembayaran</label>
                                <select id="metode" class="form-control">
                                    <option>Transfer Bank</option>
                                    <option>Tunai</option>
                                    <option>E-Wallet</option>
                                </select>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label>Upload Bukti (PDF / Image)</label>
                                <input type="file" id="file" class="form-control" accept=".pdf,image/*">
                            </div>
                        </div>

                        <div class="mt-3 text-right">
                            <button onclick="addData()" class="btn btn-primary">
                                <span class="material-icons">save</span>
                                Simpan
                            </button>
                        </div>

                    </div>
                </div>


                <!-- ================= TABLE ================= -->
                <div class="card">
                    <div class="card-body">

                        <!-- HEADER + TOTAL -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>
                                <span class="material-icons">receipt</span>
                                Daftar Laporan Pemasukan
                            </h4>

                            <h4 id="totalText" style="font-weight: bold;">
                                Rp. 0,-
                            </h4>

                        </div>

                        <!-- FILTER -->
                        <div class="row mb-3 align-items-center">

                            <!-- LEFT: FILTER -->
                            <div class="col-md-6 d-flex gap-2">
                                <select id="filterBulan" class="form-control mr-2" onchange="renderTable()">
                                    <option value="">Semua Bulan</option>
                                    <option value="0">Jan</option>
                                    <option value="1">Feb</option>
                                    <option value="2">Mar</option>
                                    <option value="3">Apr</option>
                                    <option value="4">Mei</option>
                                    <option value="5">Jun</option>
                                    <option value="6">Jul</option>
                                    <option value="7">Agu</option>
                                    <option value="8">Sep</option>
                                    <option value="9">Okt</option>
                                    <option value="10">Nov</option>
                                    <option value="11">Des</option>
                                </select>

                                <select id="filterTahun" class="form-control" onchange="renderTable()">
                                    <option value="">Semua Tahun</option>
                                </select>
                            </div>

                            <!-- RIGHT: SHOW + SEARCH -->
                            <div class="col-md-6 d-flex justify-content-end align-items-center">

                                <!-- SHOW ENTRIES -->
                                <div class="d-flex align-items-center mr-3">
                                    <span class="mr-2">Show</span>
                                    <select id="showEntries" class="form-control" style="width:80px;" onchange="renderTable()">
                                        <option>5</option>
                                        <option selected>10</option>
                                        <option>25</option>
                                    </select>
                                    <span class="ml-2">entries</span>
                                </div>

                                <!-- SEARCH -->
                                <input type="text" id="searchInput" placeholder="Search..."
                                    class="form-control" style="width:200px;" onkeyup="renderTable()">

                            </div>

                        </div>

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Sumber</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                            </table>
                            <div id="pagination" class="mt-3 text-center"></div>
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

    <!-- ********************************** // MODAL ********************************** -->
    <!-- MODAL EDIT -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content p-3">

                <h4>Edit Laporan</h4>

                <input type="hidden" id="editIndex">

                <input type="date" id="editTanggal" class="form-control mb-2">
                <input type="text" id="editSumber" class="form-control mb-2">

                <input type="text" id="editJumlah" class="form-control mb-2"
                    onkeyup="formatInputRupiah(this)">

                <select id="editMetode" class="form-control mb-2">
                    <option>Transfer Bank</option>
                    <option>Tunai</option>
                    <option>E-Wallet</option>
                </select>

                <!-- PREVIEW FILE LAMA -->
                <div id="oldPreview" class="mb-2"></div>

                <!-- PREVIEW FILE BARU -->
                <div id="newPreview" class="mb-2"></div>

                <input type="file" id="editFile" class="form-control mb-2" accept=".pdf,image/*">

                <button onclick="updateData()" class="btn btn-primary">Update</button>

            </div>
        </div>
    </div>
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
        let data = [];
        let currentPage = 1;

        function formatRupiah(angka) {
            return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-";
        }

        function formatInputRupiah(el) {
            let angka = el.value.replace(/[^0-9]/g, '');

            if (angka === "") {
                el.value = "";
                return;
            }

            let formatted = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            el.value = "Rp. " + formatted + ",-";
        }

        function addData() {
            let tanggalEl = document.getElementById("tanggal");
            let kategoriEl = document.getElementById("kategori");
            let sumberEl = document.getElementById("sumber");
            let jumlahEl = document.getElementById("jumlah");
            let metodeEl = document.getElementById("metode");
            let fileEl = document.getElementById("file");

            let tanggal = tanggalEl.value;
            let kategori = kategoriEl.value;
            let sumber = sumberEl.value;
            let metode = metodeEl.value;

            let jumlahRaw = jumlahEl.value.replace(/[^0-9]/g, '');
            let jumlah = parseInt(jumlahRaw);

            if (!tanggal || isNaN(jumlah)) {
                alert("Isi data dengan benar!");
                return;
            }

            let file = fileEl.files[0];

            data.push({
                tanggal,
                kategori,
                sumber,
                jumlah,
                metode,
                file: file,
                fileURL: file ? URL.createObjectURL(file) : null
            });

            renderTable();

            // RESET
            tanggalEl.value = "";
            kategoriEl.selectedIndex = 0;
            sumberEl.value = "";
            jumlahEl.value = "";
            metodeEl.selectedIndex = 0;
            fileEl.value = "";

            tanggalEl.focus();

            alert("Data berhasil ditambahkan!");
        }

        function renderTable() {
            let tbody = document.getElementById("tableBody");
            tbody.innerHTML = "";

            let search = document.getElementById("searchInput").value.toLowerCase();
            let show = parseInt(document.getElementById("showEntries").value);
            let bulan = document.getElementById("filterBulan").value;
            let tahun = document.getElementById("filterTahun").value;

            // ================= FILTER =================
            let filtered = data.filter(d => {
                let tgl = new Date(d.tanggal);

                let cocokSearch =
                    d.sumber.toLowerCase().includes(search) ||
                    d.kategori.toLowerCase().includes(search);

                let cocokBulan = bulan === "" || tgl.getMonth() == bulan;
                let cocokTahun = tahun === "" || tgl.getFullYear() == tahun;

                return cocokSearch && cocokBulan && cocokTahun;
            });

            // ================= PAGINATION =================
            let totalData = filtered.length;
            let totalPage = Math.ceil(totalData / show);

            if (currentPage > totalPage) currentPage = totalPage || 1;

            let start = (currentPage - 1) * show;
            let paginated = filtered.slice(start, start + show);

            // ================= RENDER TABLE =================
            let total = 0;

            paginated.forEach((d, i) => {
                total += d.jumlah;

                tbody.innerHTML += `
<tr>
    <td>${start + i + 1}</td>
    <td>${d.tanggal}</td>
    <td>${d.kategori}</td>
    <td>${d.sumber}</td>
    <td>${formatRupiah(d.jumlah)}</td>
    <td>${d.metode}</td>
<td>
    ${
        d.fileURL
        ? (d.file.type.includes("image")
            ? `<img src="${d.fileURL}" width="50" style="cursor:pointer" onclick="window.open('${d.fileURL}')">`
            : `<button class="btn btn-sm btn-info" onclick="window.open('${d.fileURL}')">PDF</button>`
        )
        : '-'
    }
</td>
    <td>
        <button class="btn btn-sm btn-warning" onclick="openEdit(${start + i})">
            <span class="material-icons">edit</span>
        </button>

        <button class="btn btn-sm btn-danger" onclick="deleteData(${start + i})">
            <span class="material-icons">delete</span>
        </button>
    </td>
</tr>
`;
            });

            // ================= TOTAL =================
            let totalFiltered = filtered.reduce((a, b) => a + b.jumlah, 0);
            animateTotal(totalFiltered);

            // ================= PAGINATION =================
            renderPagination(totalPage);
        }

        function renderPagination(totalPage) {
            let container = document.getElementById("pagination");

            if (totalPage <= 1) {
                container.innerHTML = "";
                return;
            }

            let html = `<ul class="pagination justify-content-center">`;

            // PREV
            html += `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Prev</a>
    </li>`;

            // LOOP PAGE (biar tidak kepanjangan)
            let start = Math.max(1, currentPage - 2);
            let end = Math.min(totalPage, currentPage + 2);

            if (start > 1) {
                html += `<li class="page-item"><a class="page-link" onclick="changePage(1)">1</a></li>`;
                if (start > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            for (let i = start; i <= end; i++) {
                html += `
        <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
        </li>`;
            }

            if (end < totalPage) {
                if (end < totalPage - 1)
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;

                html += `<li class="page-item"><a class="page-link" onclick="changePage(${totalPage})">${totalPage}</a></li>`;
            }

            // NEXT
            html += `
    <li class="page-item ${currentPage === totalPage ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
    </li>`;

            html += `</ul>`;

            container.innerHTML = html;
        }

        function changePage(page) {
            let show = parseInt(document.getElementById("showEntries").value);
            let totalPage = Math.ceil(data.length / show);

            if (page < 1) page = 1;
            if (page > totalPage) page = totalPage;

            currentPage = page;
            renderTable();
        }

        function animateTotal(target) {
            let el = document.getElementById("totalText");
            let current = 0;
            let increment = target / 50;

            let interval = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(interval);
                }
                el.innerText = formatRupiah(Math.floor(current));
            }, 20);
        }

        // INIT TAHUN
        (function initYear() {
            let select = document.getElementById("filterTahun");
            let now = new Date().getFullYear();

            for (let i = now; i >= 2020; i--) {
                let opt = document.createElement("option");
                opt.value = i;
                opt.text = i;
                select.appendChild(opt);
            }
        })();

        // EDIT MODAL
        function openEdit(index) {
            let d = data[index];

            document.getElementById("editIndex").value = index;
            document.getElementById("editTanggal").value = d.tanggal;
            document.getElementById("editSumber").value = d.sumber;
            document.getElementById("editJumlah").value = formatRupiah(d.jumlah);
            document.getElementById("editMetode").value = d.metode;

            // PREVIEW FILE LAMA
            let oldPreview = document.getElementById("oldPreview");

            if (d.fileURL) {
                if (d.file.type.includes("image")) {
                    oldPreview.innerHTML = `<img src="${d.fileURL}" width="120">`;
                } else {
                    oldPreview.innerHTML = `<a href="${d.fileURL}" target="_blank">Lihat PDF Lama</a>`;
                }
            } else {
                oldPreview.innerHTML = "Tidak ada file";
            }

            // PREVIEW FILE BARU
            document.getElementById("editFile").onchange = function(e) {
                let file = e.target.files[0];
                let preview = document.getElementById("newPreview");

                if (!file) return;

                let url = URL.createObjectURL(file);

                if (file.type.includes("image")) {
                    preview.innerHTML = `<img src="${url}" width="120">`;
                } else {
                    preview.innerHTML = `<span>PDF siap diupload</span>`;
                }
            };

            $('#editModal').modal('show');
        }

        // UPDATE DATA
        function updateData() {
            let index = document.getElementById("editIndex").value;

            let jumlahRaw = document.getElementById("editJumlah").value.replace(/[^0-9]/g, '');
            let jumlah = parseInt(jumlahRaw);

            data[index].tanggal = document.getElementById("editTanggal").value;
            data[index].sumber = document.getElementById("editSumber").value;
            data[index].jumlah = jumlah;
            data[index].metode = document.getElementById("editMetode").value;

            let file = document.getElementById("editFile").files[0];

            if (file) {
                data[index].file = file;
                data[index].fileURL = URL.createObjectURL(file);
            }

            $('#editModal').modal('hide');
            renderTable();
        }

        // DELETE DATA
        function deleteData(index) {
            let confirmDelete = confirm("Yakin ingin menghapus data ini?");

            if (!confirmDelete) return;

            data.splice(index, 1);

            alert("Data berhasil dihapus!");
            renderTable();
        }
    </script>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            currentPage = 1;
            renderTable();
        });

        document.getElementById("showEntries").addEventListener("change", function() {
            currentPage = 1;
            renderTable();
        });
    </script>
</body>

</html>