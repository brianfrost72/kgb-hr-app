<?php

require_once __DIR__ . '/../../koneksi.php';

if(isset($_GET['id'])){

    $id = (int) $_GET['id'];

    $query = mysqli_query($conn, "
        SELECT post_img
        FROM post
        WHERE id = '$id'
    ");

    $data = mysqli_fetch_assoc($query);

    if($data){

        if(!empty($data['post_img'])){

            $filePath =
                __DIR__ .
                '/../assets/images/uploads/posts/' .
                $data['post_img'];

            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        mysqli_query($conn, "
            DELETE FROM post
            WHERE id = '$id'
        ");
    }
}

header('Location: ../manage_post');
exit;
?>