<?php
session_start();
    if (isset($_POST['tambah_semester'])) {
        
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

            $semester=input($_POST["semester"]);
   

            $sql="insert into semester (semester) values
            ('$semester')";

            $simpan_semester=mysqli_query($kon,$sql);

            if ($simpan_semester) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=semester&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=semester&add=gagal");
            }
        }
    }
?>

<form action="dist/semester/tambah.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Semester:</label>
                <input type="text" name="semester" class="form-control" placeholder="Masukan Semester" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="tambah_semester" id="Submit" class="btn btn-primary">Tambah</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>

