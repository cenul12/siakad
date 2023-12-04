<?php
  
    include '../../config/database.php';
    //Mengambil nilai kiriman form
    $id_krs = $_POST["id_krs"];
    $presensi = $_POST["presensi"];
    $pertemuan = $_POST["pertemuan"];

    $field="";
    //Memilih kolom (field) berdasarkan pertemuan yang dipilih
    switch ($pertemuan){
        case '1' : $field="per1"; break;
        case '2' : $field="per2"; break;
        case '3' : $field="per3"; break;
        case '4' : $field="per4"; break;
        case '5' : $field="per5"; break;
        case '6' : $field="per6"; break;
        case '7' : $field="per7"; break;
        case '8' : $field="per8"; break;
        case '9' : $field="per9"; break;
        case '10' : $field="per10"; break;
        case '11' : $field="per11"; break;
        case '12' : $field="per12"; break;
        case '13' : $field="per13"; break;
        case '14' : $field="per14"; break;
    }

    //Menyimpan presensi menggunakan perulangan karena lebih dari satu
    for ($i=0; $i < count($id_krs) ; $i++){
    
        $sql="update presensi set
        $field='$presensi[$i]'
        where id_krs='$id_krs[$i]'";

        mysqli_query($kon,$sql);

    }
?>