<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai mahasiswa
    mysqli_query($kon,"START TRANSACTION");

    $id_mahasiswa=$_GET['id_mahasiswa'];
    $kode_mahasiswa=$_GET['kode_mahasiswa'];

    //Menghapus data dalam tabel mahasiswa
    $hapus_mahasiswa=mysqli_query($kon,"delete from mahasiswa where id_mahasiswa='$id_mahasiswa'");

    //Menghapus data dalam tabel pengguna
    $hapus_pengguna=mysqli_query($kon,"delete from pengguna where kode_pengguna='$kode_mahasiswa'");

    //Menghapus data dalam tabel KRS
    $hapus_krs=mysqli_query($kon,"delete from krs where kode_mahasiswa='$kode_mahasiswa'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_mahasiswa and $hapus_pengguna and $hapus_krs) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=mahasiswa&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=mahasiswa&hapus=gagal");

    }

?>