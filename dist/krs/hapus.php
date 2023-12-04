<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_krs=$_GET['id_krs'];
  
    //Menghapus data krs dan dan prsensi berdasarkan id_krs
    $hapus_krs=mysqli_query($kon,"delete from krs where id_krs='$id_krs'");
    $hapus_presensi=mysqli_query($kon,"delete from presensi where id_krs='$id_krs'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_krs and $hapus_presensi) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=krs&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=krs&hapus=gagal");
    }

?>