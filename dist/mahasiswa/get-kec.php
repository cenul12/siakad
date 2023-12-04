<?php
    include '../../config/database.php';
    $id_kab=$_POST['id_kab'];
    $results = array();
    $query = mysqli_query($kon, "SELECT * FROM kecamatan where id_kab='$id_kab'");
    while ($data = mysqli_fetch_array($query)):
        $results[] = $data;
    endwhile;
    echo json_encode($results);
?>

