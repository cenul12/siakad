<?php
session_start();
if (isset($_POST['ubah_aplikasi'])) {
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

        mysqli_query($kon,"START TRANSACTION");
        $id=$_POST["id"];
        $nama_kampus=input($_POST["nama_kampus"]);
        $nama_pimpinan=input($_POST["nama_pimpinan"]);
        $ketua_bid_akademik=input($_POST["ketua_bid_akademik"]);
        $no_telp=input($_POST["no_telp"]);
        $alamat=input($_POST["alamat"]);
        $website=input($_POST["website"]);
        $logo_sebelumnya=input($_POST["logo_sebelumnya"]);
        $logo = $_FILES['logo']['name'];
        $ekstensi_diperbolehkan	= array('png','jpg','jpeg');
        $x = explode('.', $logo);
        $ekstensi = strtolower(end($x));
        $ukuran	= $_FILES['logo']['size'];
        $file_tmp = $_FILES['logo']['tmp_name'];	

        if (!empty($logo)){
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){

                //Mengupload logo yang baru
                move_uploaded_file($file_tmp, 'logo/'.$logo);

                //Menghapus logo sebelumya
                unlink("logo/".$logo_sebelumnya);
                
                $sql="update profil_aplikasi set
                nama_kampus='$nama_kampus',
                nama_pimpinan='$nama_pimpinan',
                ketua_bid_akademik='$ketua_bid_akademik',
                no_telp='$no_telp',
                alamat='$alamat',
                website='$website',
                logo='$logo'
                where id=$id";
            }
        }else {
            $sql="update profil_aplikasi set
            nama_kampus='$nama_kampus',
            nama_pimpinan='$nama_pimpinan',
            ketua_bid_akademik='$ketua_bid_akademik',
            no_telp='$no_telp',
            alamat='$alamat',
            website='$website'
            where id=$id";
        }

        //Mengeksekusi query 
        $update_profil_aplikasi=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($update_profil_aplikasi) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=pengaturan-aplikasi&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=pengaturan-aplikasi&edit=gagal");
        }

    }

}
?>