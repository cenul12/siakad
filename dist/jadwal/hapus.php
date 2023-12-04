<?php
//Memulai session
session_start();
    //Koneksi database
    include '../../config/database.php';

    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_jadwal=$_GET['id_jadwal'];
    $semester=$_GET['semester'];

    //Menghapus data jadwal dan detail jadwal
    $hapus_jadwal=mysqli_query($kon,"delete from jadwal where id_jadwal='$id_jadwal'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_jadwal) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=jadwal&semester=$semester&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=jadwal&semester=$semester&hapus=gagal");
    }

?>