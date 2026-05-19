<?php
session_start();
if (!isset($_SESSION['reg_data'])) {
    header("Location: registrasi.php");
    exit;
}
$email = $_SESSION['reg_data']['email'];
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Akun | Konig Guard Bureau</title>
    <link rel="stylesheet" href="assets/css/login.css" />
</head>

<body>
    <div class="wrap">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
        <img src="assets/images/logo-only.png" class="float-logo">
    </div>



    <div class="otpVerification">
        <div class="verification-container neon-pulse">

            <!-- KONTEN OTP -->
            <div class="otp-content">
                <div class="logo-wrapper">
                    <img src="assets/images/logo-light.png" alt="logo">
                </div>

                <h1>OTP Verification</h1>
                <p>Enter the OTP you received to <span id="email"><?= htmlspecialchars($email) ?></span>
                </p>

                <div class="otp-input">
                    <input type="text" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" inputmode="numeric" pattern="[0-9]*">
                </div>

                <button id="verifyBtn">Verify</button>

                <div class="resend-text">
                    Belum menerima kode?
                    <span class="resend-link"> Kirim Ulang OTP</span>
                    <span id="timer"></span>
                </div>
            </div>

            <!-- SUCCESS -->
            <div class="otp-success">
                <div class="checkmark">✓</div>
                <h3>Akun berhasil dibuat</h3>
                <small>Mengarahkan ke Halaman Login...</small>
            </div>

        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", () => {

            // ================= AMBIL ELEMEN =================
            const inputs = document.querySelectorAll(".otp-input input");
            const verifyBtn = document.getElementById("verifyBtn");
            const otpContent = document.querySelector(".otp-content");
            const otpSuccess = document.querySelector(".otp-success");
            const resendLink = document.querySelector(".resend-link");
            const timerDisplay = document.getElementById("timer");

            let timeLeft = 60; // detik
            let timerId = null;

            // ================= INPUT OTP =================
            inputs.forEach((input, index) => {
                input.addEventListener("input", () => {
                    input.value = input.value.replace(/[^0-9]/g, "").slice(0, 1);
                    if (input.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            // ================= TIMER =================
            function startTimer() {
                clearInterval(timerId);
                timeLeft = 60;

                resendLink.style.pointerEvents = "none";
                resendLink.style.opacity = "0.5";

                timerId = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(timerId);
                        timerDisplay.textContent = "(boleh kirim ulang)";
                        resendLink.style.pointerEvents = "auto";
                        resendLink.style.opacity = "1";
                    } else {
                        timerDisplay.textContent = `(${timeLeft}s)`;
                        timeLeft--;
                    }
                }, 1000);
            }

            // ================= RESEND OTP =================
            resendLink.addEventListener("click", async () => {
                if (timeLeft > 0) return;

                try {
                    await fetch("resend_otp.php");
                    startTimer();
                } catch {
                    alert("Gagal kirim ulang OTP");
                }
            });

            // ================= VERIFY OTP =================
            verifyBtn.addEventListener("click", async () => {
                const otp = Array.from(inputs).map(i => i.value).join("");

                if (otp.length !== 6) {
                    alert("Masukkan 6 digit OTP");
                    return;
                }

                verifyBtn.disabled = true;
                verifyBtn.innerText = "Verifying...";

                try {
                    const res = await fetch("verify_otp.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            otp
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        otpContent.style.display = "none";
                        otpSuccess.style.display = "block";
                        setTimeout(() => location.href = "index.php", 3000);
                    } else {
                        alert(data.message);
                        verifyBtn.disabled = false;
                        verifyBtn.innerText = "Verify";
                    }
                } catch {
                    alert("Server error");
                    verifyBtn.disabled = false;
                    verifyBtn.innerText = "Verify";
                }
            });

            // ================= START TIMER PERTAMA =================
            startTimer();

        });
    </script>



    <script src="assets/js/auth.js"></script>
</body>

</html>