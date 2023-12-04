<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_program_studi=$_GET['id_program_studi'];

    //Menghapus data program studi
    $hapus_program_studi=mysqli_query($kon,"delete from program_studi where id_program_studi='$id_program_studi'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_program_studi) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=program-studi&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=program-studi&hapus=gagal");

    }

?>