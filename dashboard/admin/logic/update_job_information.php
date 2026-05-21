<?php
session_start();
require_once __DIR__ . '/../../koneksi.php';

/* =========================================
   VALIDASI
========================================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id             = $_POST['id'];
    $job_title      = $_POST['job_title'];
    $id_region      = $_POST['id_region'];
    $type_vacancy   = $_POST['type_vacancy'];
    $job_desc       = $_POST['job_desc'];
    $job_quota      = $_POST['job_quota'];
    $start_info     = $_POST['start_info'];
    $end_info       = $_POST['end_info'];
    $link_info      = $_POST['link_info'];

    /* =========================================
       UPDATE QUERY
    ========================================= */
    $update = mysqli_query($conn, "
        UPDATE job_vacancy
        SET
            job_title      = '$job_title',
            id_region      = '$id_region',
            type_vacancy   = '$type_vacancy',
            job_desc       = '$job_desc',
            job_quota      = '$job_quota',
            start_info     = '$start_info',
            end_info       = '$end_info',
            link_info      = '$link_info'
        WHERE id = '$id'
    ");

    if ($update) {

        echo "success";
    } else {

        echo mysqli_error($conn);
    }
}
