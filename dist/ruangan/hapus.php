<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_ruangan=$_GET['id_ruangan'];

    //Menghapus data ruangan 
    $hapus_ruangan=mysqli_query($kon,"delete from ruangan where id_ruangan='$id_ruangan'");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_ruangan) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=ruangan&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=ruangan&hapus=gagal");

    }

?>