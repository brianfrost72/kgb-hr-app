// Validasi Login
document.getElementById("loginForm")?.addEventListener("submit", function (e) {
  const email = document.getElementById("login_email").value.trim();
  const pass = document.getElementById("login_password").value.trim();

  if (email === "" || pass === "") {
    e.preventDefault();
    alert("Semua field wajib diisi!");
  }
});

// Validasi Register
document
  .getElementById("registerForm")
  ?.addEventListener("submit", function (e) {
    const name = document.getElementById("reg_name").value.trim();
    const email = document.getElementById("reg_email").value.trim();
    const phone = document.getElementById("reg_phone").value.trim();
    const pass = document.getElementById("reg_password").value.trim();

    if (name === "" || email === "" || phone === "" || pass === "") {
      e.preventDefault();
      alert("Semua field wajib diisi!");
    }
  });

// Validasi Reset Password
document.getElementById("resetForm")?.addEventListener("submit", function (e) {
  const email = document.getElementById("reset_email").value.trim();

  if (email === "") {
    e.preventDefault();
    alert("Email wajib diisi!");
  }
});

// ===== AMBIL ELEMENT (AMAN WALAU HALAMAN BERBEDA) =====
const jenisAkun = document.getElementById("jenisAkun");
const formPersonal = document.getElementById("formPersonal");
const formKorporat = document.getElementById("formKorporat");
const togglePass = document.getElementById("togglePass");

// ===== SATU FUNGSI UNTUK SEMUA PASSWORD =====
function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  if (!input) return;

  input.type = input.type === "password" ? "text" : "password";
}

// ===== SWITCH FORM PERSONAL / KORPORAT =====
if (jenisAkun) {
  jenisAkun.addEventListener("change", function () {
    if (this.value === "personal") {
      formPersonal?.classList.remove("hidden");
      formKorporat?.classList.add("hidden");
    } else if (this.value === "korporat") {
      formKorporat?.classList.remove("hidden");
      formPersonal?.classList.add("hidden");
    }
  });
}

// ===== REGISTER: SHOW / HIDE PASSWORD =====
if (togglePass) {
  togglePass.addEventListener("change", function () {
    if (!formPersonal?.classList.contains("hidden")) {
      togglePassword("passwordPersonal");
    } else if (!formKorporat?.classList.contains("hidden")) {
      togglePassword("passwordKorporat");
    }
  });
}

