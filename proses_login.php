<?php
session_start();
require_once "dashboard/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    header("Location: /?error=1");
    exit;
}

/* ======================
   AMBIL USER + ROLE + STATUS
====================== */

$stmt = $conn->prepare("
SELECT 
    u.id,
    u.email,
    u.password,
    u.user_type,
    u.id_client_type,
    u.role_id,
    u.region_id,
    r.role_name,
    up.status

FROM users u
LEFT JOIN roles r ON r.id = u.role_id
LEFT JOIN user_profile up ON up.user_id = u.id
WHERE u.email = ?
LIMIT 1
");

$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if (!$user = $res->fetch_assoc()) {
    header("Location: /?error=1");
    exit;
}

/* ======================
   CEK PASSWORD
====================== */

if (!password_verify($password, $user['password'])) {
    header("Location: /?error=1");
    exit;
}

/* ======================
   AUTO INACTIVE CHECK DULU (SEMUA CLIENT)
====================== */

if ($user['user_type'] === 'client') {

    $uid = $user['id'];

    // PERSONAL
    $conn->query("
    UPDATE client_personal_profile c
    JOIN users u ON u.id=c.user_id
    SET c.status='inactive', c.inactive_reason='auto'
    WHERE c.user_id=$uid
    AND c.status='active'
    AND u.last_seen < DATE_SUB(NOW(), INTERVAL 2 MONTH)
    ");

    // KORPORAT
    $conn->query("
    UPDATE client_korporat_profile c
    JOIN users u ON u.id=c.user_id
    SET c.status='inactive', c.inactive_reason='auto'
    WHERE c.user_id=$uid
    AND c.status='active'
    AND u.last_seen < DATE_SUB(NOW(), INTERVAL 2 MONTH)
    ");

    // CEK STATUS FINAL (PERSONAL DULU)
    $sp = $conn->query("
        SELECT status, inactive_reason
        FROM client_personal_profile
        WHERE user_id=$uid
        LIMIT 1
    ")->fetch_assoc();

    if (!$sp) {
        $sp = $conn->query("
            SELECT status, inactive_reason
            FROM client_korporat_profile
            WHERE user_id=$uid
            LIMIT 1
        ")->fetch_assoc();
    }

    if (($sp['status'] ?? 'inactive') !== 'active') {

        if (($sp['inactive_reason'] ?? '') === 'auto') {
            header("Location: /?client_auto_block=1");
        } else {
            header("Location: /?client_admin_block=1");
        }
        exit;
    }
}


/* ======================
   CEK STATUS — INTERNAL USER
====================== */

if ($user['user_type'] === 'internal') {
    if (($user['status'] ?? 'inactive') !== 'active') {
        header("Location: /?blocked=1");
        exit;
    }
}

/* ======================
   LOGIN VALID — SET SESSION
====================== */

$user_id   = $user['id'];
$user_type = $user['user_type'];

$_SESSION['login']      = true;
$_SESSION['user_id']    = $user_id;
$_SESSION['email']      = $user['email'];
$_SESSION['user_type']  = $user_type;
$_SESSION['region_id']  = $user['region_id'];

/* ======================
   INTERNAL USER (ROLES)
====================== */

if ($user_type === 'internal') {
    $_SESSION['role'] = $user['role_name'];
    $_SESSION['nama'] = $user['role_name'];
}

/* ======================
   CLIENT USER
====================== */

if ($user_type === 'client') {

    $client_type = $user['id_client_type'];
    $_SESSION['role'] = 'client';
    $_SESSION['client_type'] = $client_type;

    if ($client_type === 'personal') {

        $p = $conn->prepare("
            SELECT full_name 
            FROM client_personal_profile
            WHERE user_id=?
            LIMIT 1
        ");
        $p->bind_param("i", $user_id);
        $p->execute();
        $r = $p->get_result()->fetch_assoc();
        $_SESSION['nama'] = $r['full_name'] ?? 'Client';
        $p->close();
    }

    if ($client_type === 'korporat') {

        $p = $conn->prepare("
            SELECT company_name
            FROM client_korporat_profile
            WHERE user_id=?
            LIMIT 1
        ");
        $p->bind_param("i", $user_id);
        $p->execute();
        $r = $p->get_result()->fetch_assoc();
        $_SESSION['nama'] = $r['company_name'] ?? 'Client';
        $p->close();
    }
}

/* ======================
   UPDATE ONLINE STATUS
====================== */

$up = $conn->prepare("
UPDATE users 
SET last_seen = NOW(),
    is_online = 1
WHERE id = ?
");
$up->bind_param("i", $user_id);
$up->execute();
$up->close();

/* ======================
   REDIRECT
====================== */

if ($user_type === 'client') {
    header("Location: dashboard/klien/index.php");
    exit;
}

header("Location: dashboard/admin/");
exit;
