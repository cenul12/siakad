<?php
    include '../../config/database.php';
    $id_prov=$_POST['id_prov'];
    $results = array();
    $query = mysqli_query($kon, "SELECT * FROM kabupaten where id_prov='$id_prov'");
    while ($data = mysqli_fetch_array($query)):
        $results[] = $data;
    endwhile;
    echo json_encode($results);
?>

