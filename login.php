<?php 
    session_start();
    //Jika terdetesi ada variabel id_pengguna dalam session maka langsung arahkan ke halaman dashboard
    if  (isset($_SESSION["id_pengguna"])){
        session_unset();
        session_destroy();
    }
    
    $pesan="";
    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
        include "config/database.php";
        //Mengambil uername dan password
        $username = input($_POST["username"]);
        $password = input(md5($_POST["password"]));

    //Query untuk cek pada tabel pengguna yang dijoinkan dengan tabel karyawan
        $tabel_karyawan= "select * from pengguna p
        inner join karyawan k on k.kode_karyawan=p.kode_pengguna
        where username='".$username."' and password='".$password."' limit 1";

        $cek_tabel_karyawan = mysqli_query ($kon,$tabel_karyawan);
        $karyawan = mysqli_num_rows($cek_tabel_karyawan);

        //Query untuk cek pada tabel pengguna yang dijoinkan dengan tabel dosen
        $tabel_dosen= "select * from pengguna p
        inner join dosen d on d.kode_dosen=p.kode_pengguna
        where username='".$username."' and password='".$password."' limit 1";

        $cek_tabel_dosen = mysqli_query ($kon,$tabel_dosen);
        $dosen = mysqli_num_rows($cek_tabel_dosen);

        //Query untuk cek pada tabel pengguna yang dijoinkan dengan tabel mahasiswa
        $tabel_mahasiswa= "select * from pengguna p
        inner join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
        where username='".$username."' and password='".$password."' limit 1";

        $cek_tabel_mahasiswa = mysqli_query ($kon,$tabel_mahasiswa);
        $mahasiswa = mysqli_num_rows($cek_tabel_mahasiswa);

        //Kondisi jika pengguna merupakan karyawan
        if ($karyawan>0){
            $row = mysqli_fetch_assoc($cek_tabel_karyawan);
            //menyimpan data karyawan dalam session
            $_SESSION["id_pengguna"]=$row["id_pengguna"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["nama_karyawan"]=$row["nama_karyawan"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["foto"]=$row["foto"];
            $_SESSION["nip"]=$row["nip"];

            header("Location:index.php?page=beranda");
        }
        //Kondisi jika pengguna merupakan dosen
        else if ($dosen>0){

            $row = mysqli_fetch_assoc($cek_tabel_dosen);
            //menyimpan data dosen dalam session
            $_SESSION["id_pengguna"]=$row["id_pengguna"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["nama_dosen"]=$row["nama_dosen"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["foto"]=$row["foto"];
            $_SESSION["nip"]=$row["nip"];

            header("Location:index.php?page=beranda");
        }
        //Kondisi jika pengguna merupakan mahasiswa
        else if ($mahasiswa>0){

            $row = mysqli_fetch_assoc($cek_tabel_mahasiswa);
            //menyimpan data mahasiswa dalam session
            $_SESSION["id_pengguna"]=$row["id_pengguna"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["nama_mahasiswa"]=$row["nama_mahasiswa"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["foto"]=$row["foto"];
            $_SESSION["nim"]=$row["nim"];

            header("Location:index.php?page=beranda");
        } else {
            $pesan="<div class='alert alert-danger'><strong>Error!</strong> Username dan password salah.</div>";
        }
	}
?>


<?php
    //Mengambil profil aplikasi
    include 'config/database.php';
    $query = mysqli_query($kon, "select * from profil_aplikasi limit 1");    
    $row = mysqli_fetch_array($query);
    $nama_kampus=$row['nama_kampus'];
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo strtoupper($nama_kampus); ?></title>
    <link href="template/css/bootstrap.min.css" rel="stylesheet">
    <link href="template/css/font-awesome.min.css" rel="stylesheet">
    <link href="template/css/datepicker3.css" rel="stylesheet">
    <link href="template/css/styles.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <script src="template/js/jquery-2.2.3.min.js"></script>
    <script src="template/js/jquery-1.11.1.min.js"></script>
    <!--Custom Font-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <style>
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('loading.gif') center no-repeat #fff;
        }

    </style>
</head>
<body>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading text-center" ><?php echo strtoupper($nama_kampus); ?></div>
                <div class="panel-body">
                <?php 	if ($_SERVER["REQUEST_METHOD"] == "POST") echo $pesan; ?>
                    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                        <fieldset>
                                <div class="form-group text-center mb-4 mt-3">
                                <img src="dist/aplikasi/logo/<?php echo $row['logo'];?>" width="50%">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username" name="username" type="text" autofocus="">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" value="Login" name="Submit" class="btn btn-primary" />
                            </div>
                          

                        </fieldset>
                    </form>
                </div>
            </div>
        </div><!-- /.col-->
    </div><!-- /.row -->

    <script src="template/js/bootstrap.min.js"></script>
    <script src="template/js/custom.js"></script>
</body>
</html>