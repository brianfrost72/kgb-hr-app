<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

/* =========================================
   DELETE JOB VACANCY
========================================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];

    /* =========================================
       VALIDASI
    ========================================= */
    if (empty($id)) {

        echo "ID lowongan tidak ditemukan!";
        exit;
    }

    /* =========================================
       DELETE QUERY
    ========================================= */
    $delete = mysqli_query($conn, "
        DELETE FROM job_vacancy
        WHERE id = '$id'
    ");

    /* =========================================
       RESPONSE
    ========================================= */
    if ($delete) {

        echo "success";
    } else {

        echo mysqli_error($conn);
    }
}
