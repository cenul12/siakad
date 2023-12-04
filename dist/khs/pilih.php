<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $id_jadwal=input($_GET["id_jadwal"]);
    $kode_mahasiswa=input($_GET["kode_mahasiswa"]);

    $sql="insert into krs (kode_mahasiswa,id_jadwal) values
    ('$kode_mahasiswa','$id_jadwal')";

    //Menghapus data jadwal dan detail jadwal
    $tambah_krs=mysqli_query($kon,$sql);

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($tambah_krs) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=krs&add=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=krs&add=gagal");
    }

?>