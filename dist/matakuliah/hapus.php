<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_matakuliah=$_GET['id_matakuliah'];

    //Menghapus data matakuliah dan detail matakuliah
    $hapus_matakuliah=mysqli_query($kon,"delete from matakuliah where id_matakuliah='$id_matakuliah'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_matakuliah) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=matakuliah&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=matakuliah&hapus=gagal");
    }

?>