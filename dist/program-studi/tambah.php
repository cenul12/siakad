<?php
session_start();
    if (isset($_POST['tambah_program_studi'])) {
        
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

            $program_studi=input($_POST["program_studi"]);
            $ketua=input($_POST["ketua"]);
   

            $sql="insert into program_studi (program_studi,ketua) values
            ('$program_studi','$ketua')";

            $simpan_program_studi=mysqli_query($kon,$sql);

            if ($simpan_program_studi) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=program-studi&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=program-studi&add=gagal");
            }
        }
    }
?>

<form action="dist/program-studi/tambah.php" method="post">

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Program Studi:</label>
                <input type="text" name="program_studi" class="form-control" placeholder="Masukan Nama Program Studi" required>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Ketua:</label>
                <input type="text" name="ketua" class="form-control" placeholder="Masukan Nama Ketua" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="tambah_program_studi" id="Submit" class="btn btn-primary">Tambah</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>

