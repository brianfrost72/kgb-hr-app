<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id     = $_POST['id'];
    $status = $_POST['status'];

    $update = mysqli_query($conn, "
        UPDATE job_vacancy
        SET status = '$status'
        WHERE id = '$id'
    ");

    if ($update) {

        echo "success";
    } else {

        echo mysqli_error($conn);
    }
}
