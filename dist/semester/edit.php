<?php
session_start();
    if (isset($_POST['edit_semester'])) {
        
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
            $id_semester=input($_POST["id_semester"]);
            $semester=input($_POST["semester"]);


            $sql="update semester set
            semester='$semester'
            where id_semester=$id_semester";
        
            //Mengeksekusi query
            $edit_semester=mysqli_query($kon,$sql);

            if ($edit_semester) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=semester&edit=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=semester&edit=gagal");
            }
        } 
    }
?>

<?php 
    include '../../config/database.php';
    $id_semester=$_POST["id_semester"];
    $sql="select * from semester where id_semester=$id_semester limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>

<form action="dist/semester/edit.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <input name="id_semester" value="<?php echo $data['id_semester'];?>" type="hidden" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Semester:</label>
                <input type="text" name="semester" class="form-control"  value="<?php echo $data['semester'];?>" placeholder="Masukan Semester" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="edit_semester"  class="btn btn-warning">Update</button>
        </div>
    </div>
</form>
