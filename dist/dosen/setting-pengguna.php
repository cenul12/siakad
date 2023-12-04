<?php
session_start();
    if (isset($_POST['submit'])) {
        
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

            $kode_dosen=input($_POST["kode_dosen"]);
            $username=input($_POST["username"]);
            $level="Dosen";

            //Mengambil password
            $ambil_password=mysqli_query($kon,"select password from pengguna where kode_pengguna='$kode_dosen' limit 1");
            $data = mysqli_fetch_array($ambil_password);
            if ($data['password']==$_POST["password"]){
                $password=input($_POST["password"]);
            }else {
                $password=md5(input($_POST["password"]));
            }
            
            $sql="update pengguna set
           username='$username',
           password='$password',
           level='$level'
           where kode_pengguna='$kode_dosen'";

            //Menyimpan ke tabel pengguna
            $setting_pengguna=mysqli_query($kon,$sql);

            if ($setting_pengguna) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=dosen&pengguna=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=dosen&pengguna=gagal");
            }

        }
       
    }
?>

<form action="dist/dosen/setting-pengguna.php" method="post">
<?php
    include '../../config/database.php';
    $kode_pengguna=$_POST['kode_dosen'];
    $query = mysqli_query($kon, "SELECT * FROM pengguna where kode_pengguna='$kode_pengguna'");
    $data = mysqli_fetch_array($query);
    $username=$data['username'];
    $password=$data['password'];
?>

    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <input name="kode_dosen" type="hidden" id="kode_dosen" class="form-control" value="<?php echo $_POST['kode_dosen'];?>"/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>Username:</label>
                <input name="username" type="text" id="username" class="form-control" value="<?php echo $username; ?>" placeholder="Buat username" required>
                <div id="info_username"> </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Password:</label>
                <input name="password" type="password" class="form-control" value="<?php echo $password; ?>" placeholder="Buat password" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>

<script>
    $("#username").bind('keyup', function () {

        var username = $('#username').val();

        $.ajax({
            url: 'dist/pengguna/cek-username.php',
            method: 'POST',
            data:{username:username},
            success:function(data){
                $('#info_username').show();
                $('#info_username').html(data);
            }
        }); 
    });
</script>

