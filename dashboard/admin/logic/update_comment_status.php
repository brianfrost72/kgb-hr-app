<?php
require_once __DIR__ . '/../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $allowedStatus = ['Aktif', 'Disembunyikan'];

    if (!in_array($status, $allowedStatus)) {
        exit('invalid');
    }

    $update = mysqli_query($conn, "
        UPDATE post_commenters
        SET status = '$status'
        WHERE id = '$id'
    ");

    if ($update) {
        echo 'success';
    } else {
        echo 'error';
    }
}
