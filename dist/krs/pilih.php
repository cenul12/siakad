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

    $id_semester=input($_GET["id_semester"]);
    $id_jadwal=input($_GET["id_jadwal"]);
    $kode_mahasiswa=input($_GET["kode_mahasiswa"]);

    $hasil=mysqli_query($kon,"select * from jadwal where id_jadwal='$id_jadwal'");
    $data = mysqli_fetch_array($hasil);

    $hari=$data["hari"];
    $id_dosen=$data["id_dosen"];
     
    $jam_mulai=date("H:i",strtotime($data["jam_mulai"]));
    $jam_selesai=date("H:i",strtotime($data["jam_selesai"]));

    $cek[]=0;

    //Cek apakah jadwal bertabrakan dengan jadwal yang telah diilih sebelumnya
    while (strtotime($jam_mulai) <= strtotime($jam_selesai)) {
        
        $query = mysqli_query($kon, "select * from jadwal j inner join krs k on k.id_jadwal=j.id_jadwal where '".$jam_mulai."' between jam_mulai and jam_selesai and hari='$hari' and j.id_semester='$id_semester' and k.kode_mahasiswa='$kode_mahasiswa'");
        $jam_mulai = date ("H:i", strtotime("+1 hour", strtotime($jam_mulai)));
        $cek[] = mysqli_num_rows($query);
     
    }
   //Jika terjadi tabrakan maka proses tambah KRS tidak dijalankan
    if (in_array('1', $cek)){
     
        header("Location:../../index.php?page=krs&jadwal=tabrakan");
        exit;
    }

   
    //Menambah ke dalam tabel KRS
    $sql1="insert into krs (kode_mahasiswa,id_jadwal,id_semester) values
    ('$kode_mahasiswa','$id_jadwal','$id_semester')";

    $tambah_krs=mysqli_query($kon,$sql1);

    $hasil=mysqli_query($kon,"select id_krs from krs order by id_krs desc limit 1");
    $data = mysqli_fetch_array($hasil);
    $id_krs=$data['id_krs'];

    //Menambah ke tabel presensi
    $sql2="insert into presensi (id_krs) values
    ('$id_krs')";

    $buat_presensi=mysqli_query($kon,$sql2);

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($tambah_krs and $buat_presensi) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=krs&add=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=krs&add=gagal");
    }

?>