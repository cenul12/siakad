<?php
  
    include '../../config/database.php';
    
    $id_krs =$_POST["id_krs"];
    $nilai = $_POST["nilai"];

    //Submit nilai pada tabel krs menggunaan perulangan karena nilai yang disubmit lebih dari 1
    for ($i=0; $i < count($id_krs) ; $i++){
    
        $sql="update krs set
        nilai='$nilai[$i]'
        where id_krs='$id_krs[$i]'";

        mysqli_query($kon,$sql);

    }
?>