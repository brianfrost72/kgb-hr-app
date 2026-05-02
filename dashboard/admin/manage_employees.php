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
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    Show
                                    <select id="showEntries" class="form-control d-inline w-auto">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>20</option>
                                    </select>
                                    entries
                                </div>

                                <!-- FILTER DEPARTEMEN -->
                                <select id="filterDepartment" class="form-control filter-select">
                                    <option value="">Semua Departemen</option>
                                    <option>HR</option>
                                    <option>Admin</option>
                                    <option>IT</option>
                                    <option>Akuntansi</option>
                                </select>

                                <!-- FILTER JABATAN -->
                                <select id="filterJabatan" class="form-control filter-select">
                                    <option value="">Semua Jabatan</option>
                                    <option>Manager</option>
                                    <option>Supervisor</option>
                                    <option>Staff</option>
                                    <option>Admin</option>
                                </select>

                                <input type="text" id="searchInput" class="form-control w-25" placeholder="Search...">
                            </div>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>No</th>
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

                <div class="modal-body row">
                    <div class="col-md-6">
                        <input type="text" id="namaTambah" class="form-control mb-2" placeholder="Nama">
                        <input type="email" id="emailTambah" class="form-control mb-2" placeholder="Email">

                        <select id="genderTambah" class="form-control mb-2">
                            <option value="">Jenis Kelamin</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>

                        <select id="statusTambah" class="form-control mb-2">
                            <option value="">Pilih Status</option>
                            <option>Belum Nikah</option>
                            <option>Menikah</option>
                        </select>

                        <select id="departmentTambah" class="form-control mb-2">
                            <option value="">Pilih Departemen</option>
                            <option>HR</option>
                            <option>Admin</option>
                            <option>Akuntansi</option>
                        </select>

                        <select id="jabatanTambah" class="form-control mb-2">
                            <option value="">Pilih Jabatan</option>
                            <option>Manager</option>
                            <option>Supervisor</option>
                            <option>Staff</option>
                        </select>

                        <input type="text" id="ktpTambah" class="form-control mb-2" placeholder="No KTP">
                        <input type="text" id="ktaTambah" class="form-control mb-2" placeholder="No KTA">
                    </div>

                    <div class="col-md-6">
                        <textarea id="alamatTambah" class="form-control mb-2" placeholder="Alamat"></textarea>

                        <input type="file" id="photoTambah" class="form-control mb-2">

                        <img id="previewPhoto" class="rounded mt-2" width="100">
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
                        <input type="text" id="namaEdit" class="form-control mb-2">
                        <input type="email" id="emailEdit" class="form-control mb-2">

                        <select id="genderEdit" class="form-control mb-2">
                            <option value="">Jenis Kelamin</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>

                        <select id="statusEdit" class="form-control mb-2">
                            <option value="">Pilih Status</option>
                            <option>Belum Nikah</option>
                            <option>Menikah</option>
                        </select>

                        <select id="departmentEdit" class="form-control mb-2">
                            <option value="">Pilih Departemen</option>
                            <option>HR</option>
                            <option>Admin</option>
                            <option>Akuntansi</option>
                        </select>

                        <select id="jabatanEdit" class="form-control mb-2">
                            <option value="">Pilih Jabatan</option>
                            <option>Manager</option>
                            <option>Supervisor</option>
                            <option>Staff</option>
                        </select>

                        <input type="text" id="ktpEdit" class="form-control mb-2">
                        <input type="text" id="ktaEdit" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                        <textarea id="alamatEdit" class="form-control mb-2"></textarea>

                        <input type="file" id="photoEdit" class="form-control mb-2">

                        <img id="previewPhotoEdit" class="rounded mt-2" width="100">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" onclick="updateData()">Update</button>
                </div>
            </div>
        </div>
    </div>

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
                                    <small>Email</small>
                                    <p id="viewEmail"></p>
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
                                    <small>Gender</small>
                                    <p id="viewGender"></p>
                                </div>

                                <div>
                                    <small>Alamat</small>
                                    <p id="viewAlamat"></p>
                                </div>

                                <div>
                                    <small>No KTP</small>
                                    <p id="viewKtp"></p>
                                </div>

                                <div>
                                    <small>No KTA</small>
                                    <p id="viewKta"></p>
                                </div>
                            </div>

                        </div>

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

    <!-- Moment.js -->
    <script src="assets/vendor/moment.min.js"></script>
    <script src="assets/vendor/moment-range.js"></script>


    <!-- Vector Maps -->
    <script src="assets/vendor/jqvmap/jquery.vmap.min.js"></script>
    <script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
    <script src="assets/js/vector-maps.js"></script>

    <script>
        let data = [{
                nama: "Brian",
                email: "brian@mail.com",
                gender: "L",
                department: "HR",
                jabatan: "Admin",
                alamat: "Jakarta",
                ktp: "1234567890123456",
                kta: "987654321",
                photo: ""
            },
            {
                nama: "Steven",
                email: "steven@mail.com",
                gender: "L",
                department: "IT",
                jabatan: "HR",
                alamat: "Bandung",
                ktp: "0987654321098765",
                kta: "123456789",
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

                return matchSearch && matchDept && matchJabatan;
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
            let email = document.getElementById("emailTambah").value;
            let gender = document.getElementById("genderTambah").value;
            let department = document.getElementById("departmentTambah").value;
            let jabatan = document.getElementById("jabatanTambah").value;
            let alamat = document.getElementById("alamatTambah").value;
            let ktp = document.getElementById("ktpTambah").value;
            let kta = document.getElementById("ktaTambah").value;

            if (!nama || !email || !gender || !department || !jabatan || !alamat || !ktp || !kta) {
                alert("Wajib isi data!");
                return;
            }

            data.push({
                nama,
                email,
                gender,
                department,
                jabatan,
                alamat,
                ktp,
                kta,
                photo: photoBase64
            });

            renderTable();
        }

        function editData(index) {
            let user = data[index];

            document.getElementById("editIndex").value = index;

            document.getElementById("namaEdit").value = user.nama || "";
            document.getElementById("emailEdit").value = user.email || "";
            document.getElementById("genderEdit").value = user.gender || "";
            document.getElementById("departmentEdit").value = user.department || "";
            document.getElementById("jabatanEdit").value = user.jabatan || "";
            document.getElementById("alamatEdit").value = user.alamat || "";
            document.getElementById("ktpEdit").value = user.ktp || "";
            document.getElementById("ktaEdit").value = user.kta || "";

            document.getElementById("previewPhotoEdit").src = user.photo || 'https://via.placeholder.com/100';

            photoEditBase64 = user.photo || "";

            $('#modalEdit').modal('show');
        }

        function updateData() {
            let index = document.getElementById("editIndex").value;

            let nama = document.getElementById("namaEdit").value;
            let email = document.getElementById("emailEdit").value;
            let gender = document.getElementById("genderEdit").value;
            let department = document.getElementById("departmentEdit").value;
            let jabatan = document.getElementById("jabatanEdit").value;
            let alamat = document.getElementById("alamatEdit").value;
            let ktp = document.getElementById("ktpEdit").value;
            let kta = document.getElementById("ktaEdit").value;

            if (!nama || !email || !gender || !department || !jabatan || !alamat || !ktp || !kta) {
                alert("Data wajib diisi!");
                return;
            }

            data[index] = {
                ...data[index],
                nama,
                email,
                gender,
                department,
                jabatan,
                alamat,
                ktp,
                kta,
                photo: photoEditBase64 || data[index].photo
            };

            $('#modalEdit').modal('hide');
            renderTable();
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
        function togglePassword() {
            let input = document.getElementById("passwordTambah");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>

    <script>
        function viewData(index) {
            $('#modalView').modal('show');

            document.getElementById("loadingView").style.display = "block";
            document.getElementById("contentView").style.display = "none";

            setTimeout(() => {
                let user = data[index];

                document.getElementById("viewPhoto").src = user.photo || 'assets/images/avatars/foto-sushi-128246.jpg';
                document.getElementById("viewNama").innerText = user.nama;
                document.getElementById("viewEmail").innerText = user.email;
                document.getElementById("viewDepartment").innerText = user.department || '-';
                document.getElementById("viewJabatan").innerText = user.jabatan || '-';
                document.getElementById("viewGender").innerText = user.gender || '-';
                document.getElementById("viewAlamat").innerText = user.alamat || '-';
                document.getElementById("viewKtp").innerText = user.ktp || '-';
                document.getElementById("viewKta").innerText = user.kta || '-';

                document.getElementById("loadingView").style.display = "none";
                document.getElementById("contentView").style.display = "block";
            }, 600);
            let cover = user.cover || "https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d";

            document.querySelector(".profile-cover").style.backgroundImage = `url('${cover}')`;
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
        function togglePasswordEdit() {
            let input = document.getElementById("passwordEdit");
            input.type = input.type === "password" ? "text" : "password";
        }
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
</body>

</html>