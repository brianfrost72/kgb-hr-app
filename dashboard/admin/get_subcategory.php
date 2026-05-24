<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

$idCategory = intval($_POST['id_postcategory']);

$query = mysqli_query($conn, "
    SELECT *
    FROM post_subcategory
    WHERE id_postcategory = '$idCategory'
    ORDER BY name_subcategory ASC
");

?>

<option value="" selected disabled>
    Pilih sub kategori
</option>

<?php while ($sub = mysqli_fetch_assoc($query)) : ?>

    <option value="<?= $sub['id']; ?>">
        <?= $sub['name_subcategory']; ?>
    </option>

<?php endwhile; ?>