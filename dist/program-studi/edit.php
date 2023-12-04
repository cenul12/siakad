<?php
session_start();
    if (isset($_POST['edit_program_studi'])) {
        
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
            $id_program_studi=input($_POST["id_program_studi"]);
            $program_studi=input($_POST["program_studi"]);
            $ketua=input($_POST["ketua"]);

            $sql="update program_studi set
            program_studi='$program_studi',
            ketua='$ketua'
            where id_program_studi=$id_program_studi";
        
            //Mengeksekusi query
            $edit_program_studi=mysqli_query($kon,$sql);

            if ($edit_program_studi) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=program-studi&edit=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=program-studi&edit=gagal");
            }
        } 
    }
?>

<?php 
    include '../../config/database.php';
    $id_program_studi=$_POST["id_program_studi"];
    $sql="select * from program_studi where id_program_studi=$id_program_studi limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>

<form action="dist/program-studi/edit.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <input name="id_program_studi" value="<?php echo $data['id_program_studi'];?>" type="hidden" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Nama Program Studi:</label>
                <input type="text" name="program_studi" class="form-control"  value="<?php echo $data['program_studi'];?>" placeholder="Masukan Nama Program Studi" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Nama Ketua:</label>
                <input type="text" name="ketua" class="form-control"  value="<?php echo $data['ketua'];?>" placeholder="Masukan Nama Ketua" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="edit_program_studi"  class="btn btn-warning">Update</button>
        </div>
    </div>
</form>
