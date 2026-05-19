// m.in-reports
// ============================================ INPUT RUPIAH ======================================
let data = [];
let currentPage = 1;

function formatRupiah(angka) {
  return "Rp. " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ",-";
}

function formatInputRupiah(el) {
  let angka = el.value.replace(/[^0-9]/g, "");

  if (angka === "") {
    el.value = "";
    return;
  }

  let formatted = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  el.value = "Rp. " + formatted + ",-";
}

// ============================================== ADD DATA ==============================================
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

  let jumlahRaw = jumlahEl.value.replace(/[^0-9]/g, "");
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
    fileURL: file ? URL.createObjectURL(file) : null,
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

// ========================================= RENDER + PAGINATION =========================================
function renderTable() {
  let tbody = document.getElementById("tableBody");
  tbody.innerHTML = "";

  let search = document.getElementById("searchInput").value.toLowerCase();
  let show = parseInt(document.getElementById("showEntries").value);
  let bulan = document.getElementById("filterBulan").value;
  let tahun = document.getElementById("filterTahun").value;

  // ================= FILTER =================
  let filtered = data.filter((d) => {
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
        ? d.file.type.includes("image")
          ? `<img src="${d.fileURL}" width="50" style="cursor:pointer" onclick="window.open('${d.fileURL}')">`
          : `<button class="btn btn-sm btn-info" onclick="window.open('${d.fileURL}')">PDF</button>`
        : "-"
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
    <li class="page-item ${currentPage === 1 ? "disabled" : ""}">
        <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Prev</a>
    </li>`;

  // LOOP PAGE (biar tidak kepanjangan)
  let start = Math.max(1, currentPage - 2);
  let end = Math.min(totalPage, currentPage + 2);

  if (start > 1) {
    html += `<li class="page-item"><a class="page-link" onclick="changePage(1)">1</a></li>`;
    if (start > 2)
      html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
  }

  for (let i = start; i <= end; i++) {
    html += `
        <li class="page-item ${i === currentPage ? "active" : ""}">
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
    <li class="page-item ${currentPage === totalPage ? "disabled" : ""}">
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

// =========================================== ANIMASI ANGKA ===========================================
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

// ================================================ INIT ================================================
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

// ============================================= EDIT MODAL =============================================
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
  document.getElementById("editFile").onchange = function (e) {
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

  $("#editModal").modal("show");
}

// =========================================== UPDATE DATA ===========================================
// UPDATE DATA
function updateData() {
  let index = document.getElementById("editIndex").value;

  let jumlahRaw = document
    .getElementById("editJumlah")
    .value.replace(/[^0-9]/g, "");
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

  $("#editModal").modal("hide");
  renderTable();
}

// ============================================ DELETE DATA ===========================================
// DELETE DATA
function deleteData(index) {
  let confirmDelete = confirm("Yakin ingin menghapus data ini?");

  if (!confirmDelete) return;

  data.splice(index, 1);

  alert("Data berhasil dihapus!");
  renderTable();
}

// =============================================== FILTER ===============================================
document.getElementById("searchInput").addEventListener("keyup", function () {
  currentPage = 1;
  renderTable();
});

document.getElementById("showEntries").addEventListener("change", function () {
  currentPage = 1;
  renderTable();
});

