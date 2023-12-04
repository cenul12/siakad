<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_semester=$_GET['id_semester'];

    //Menghapus data semester dan detail semester
    $hapus_semester=mysqli_query($kon,"delete from semester where id_semester='$id_semester'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_semester) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=semester&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=semester&hapus=gagal");

    }

?>