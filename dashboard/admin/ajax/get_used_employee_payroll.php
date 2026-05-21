<?php

require_once "../../koneksi.php";

$period = $_POST['period_date'] . "-01";

$data = [];

$query = mysqli_query($conn, "

    SELECT id_employee
    FROM employee_payment

    WHERE period_date = '$period'

");

while ($row = mysqli_fetch_assoc($query)) {

    $data[] = $row['id_employee'];
}

echo json_encode($data);
