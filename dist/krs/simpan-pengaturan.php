<?php
    if (isset($_POST['simpan_pengaturan'])) {
        
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //Memulai transaksi
            mysqli_query($kon,"START TRANSACTION");
            $id=input($_POST["id"]);
            $status_krs=input($_POST["status_krs"]);

            //semester akan di set saat pengguna telah memilih status krs dengan status 1
            if (isset($_POST['id_semester']) && $status_krs==1) {
                $id_semester=input($_POST["id_semester"]);
            }else {
                $id_semester=0;
            }

            //Update data KRS
            $sql="update pengaturan_krs set
            status_krs='$status_krs',
            id_semester='$id_semester'";

            $simpan_pengaturan=mysqli_query($kon,$sql);

            //Jika berhasil lakukan commit jika gagal lakukan rollback
            if ($simpan_pengaturan) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=pengaturan-krs&setting=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=pengaturan-krs&setting=gagal");
            }
        }
    }
?>