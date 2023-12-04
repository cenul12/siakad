<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai dosen
    mysqli_query($kon,"START TRANSACTION");

    $id_dosen=$_GET['id_dosen'];
    $kode_dosen=$_GET['kode_dosen'];

    //Menghapus data dosen dan detail dosen
    $hapus_dosen=mysqli_query($kon,"delete from dosen where id_dosen='$id_dosen'");

    //Menghapus data dalam tabel pengguna
    $hapus_pengguna=mysqli_query($kon,"delete from pengguna where kode_pengguna='$kode_dosen'");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_dosen and $hapus_pengguna) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=dosen&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=dosen&hapus=gagal");

    }

?>