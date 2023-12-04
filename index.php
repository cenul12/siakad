<?php 
  session_start();
  if (!$_SESSION["kode_pengguna"]){
        header("Location:login.php");
  }else {

    include 'config/database.php';
    $kode_pengguna=$_SESSION["kode_pengguna"];
    $username=$_SESSION["username"];

    $hasil=mysqli_query($kon,"select username from pengguna where kode_pengguna='$kode_pengguna'");
    $data = mysqli_fetch_array($hasil); 
    $username_db=$data['username'];

    if ($username!=$username_db){
        session_unset();
        session_destroy();
        header("Location:login.php");
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
    <title><?php echo strtoupper($nama_kampus);?></title>
    <link href="template/css/bootstrap.min.css" rel="stylesheet">
    <link href="template/css/font-awesome.min.css" rel="stylesheet">
    <link href="template/css/datepicker3.css" rel="stylesheet">
    <link href="template/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <script src="template/js/jquery-2.2.3.min.js"></script>
    <script src="template/js/jquery-1.11.1.min.js"></script>
    <!--Custom Font-->
    <link href="src/font/font.css" rel="stylesheet" type="text/css">
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

    <style type="text/css">
        .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: #fff;
        }
        .preloader .loading {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        font: 14px arial;
        }
    </style>

    <script>
        $(document).ready(function(){
        $(".preloader").fadeOut();
        })
    </script>

</head>
<body>

<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span></button>
            <a class="navbar-brand" href="#">SIAKAD <?php echo $nama_kampus; ?></a>
        </div>
    </div><!-- /.container-fluid -->
</nav>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">

<?php if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):?>
    <div class="profile-sidebar">
        <div class="profile-userpic">
            <img src="dist/karyawan/foto/<?php echo $_SESSION['foto'];?>" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
        <?php echo substr($_SESSION['nama_karyawan'],0,20); ?>
            <div class="profile-usertitle-name"><?php echo $_SESSION["nip"]; ?></div>
            <div></div>
        </div>
        <div class="clear"></div>
    </div>
<?php endif; ?>

<?php if ($_SESSION['level']=='Dosen' or $_SESSION['level']=='dosen'):?>
    <div class="profile-sidebar">
        <div class="profile-userpic">
            <img src="dist/dosen/foto/<?php echo $_SESSION['foto'];?>" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
        <?php echo substr($_SESSION['nama_dosen'],0,20); ?>
            <div class="profile-usertitle-name"><?php echo $_SESSION["nip"]; ?></div>
            <div></div>
        </div>
        <div class="clear"></div>
    </div>
<?php endif; ?>


<?php if ($_SESSION['level']=='Mahasiswa' or $_SESSION['level']=='mahasiswa'):?>
    <div class="profile-sidebar">
        <div class="profile-userpic">
            <img src="dist/mahasiswa/foto/<?php echo $_SESSION['foto'];?>" class="img-responsive" alt="">
        </div>
        <div class="profile-usertitle">
        <?php echo substr($_SESSION['nama_mahasiswa'],0,20); ?>
            <div class="profile-usertitle-name"><?php echo $_SESSION["nim"]; ?></div>
            <div></div>
        </div>
        <div class="clear"></div>
    </div>
<?php endif; ?>

    <div class="divider"></div>

    <ul class="nav menu">
		<li><a href='index.php?page=beranda'><em class='fa fa-home'>&nbsp;</em>  Beranda</a></li>
        <?php if ($_SESSION["level"]=="Karyawan" or $_SESSION['level']=='karyawan'): ?>
            <li class="parent"><a data-toggle="collapse" href="#sub-item-1">
                        <em class="fa fa-database">&nbsp;</em> Master Data <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
                    </a>
                    <ul class="children collapse" id="sub-item-1">
                        <li><a class="" href="index.php?page=mahasiswa">
                                <span class="fa fa-id-card">&nbsp;</span> Mahasiswa
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=dosen">
                                <span class="fa fa-id-card-o">&nbsp;</span> Dosen
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=karyawan">
                                <span class="fa fa-vcard-o">&nbsp;</span> Karyawan
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=matakuliah">
                                <span class="fa fa-newspaper-o">&nbsp;</span> Mata Kuliah
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=program-studi">
                                <span class="fa fa-navicon">&nbsp;</span> Program Studi
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=ruangan">
                                <span class="fa fa-map">&nbsp;</span> Ruangan
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=semester">
                                <span class="fa fa-line-chart">&nbsp;</span> Semester
                            </a>
                        </li>
            
                    </ul>
                </li>

                <li class="parent"><a data-toggle="collapse" href="#sub-item-2">
                        <em class="fa fa-tasks">&nbsp;</em> Kelola Data <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="fa fa-plus"></em></span>
                    </a>
                    <ul class="children collapse" id="sub-item-2">
                        <li><a class="" href="index.php?page=jadwal">
                                <span class="fa fa-calendar">&nbsp;</span> Jadwal Perkuliahan
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=presensi">
                                <span class="fa fa-calendar-check-o">&nbsp;</span> Prensensi
                            </a>
                        </li>              
                    </ul>
                </li>

                <li class="parent"><a data-toggle="collapse" href="#sub-item-3">
                        <em class="fa fa-cog">&nbsp;</em> Pengaturan <span data-toggle="collapse" href="#sub-item-3" class="icon pull-right"><em class="fa fa-plus"></em></span>
                    </a>
                    <ul class="children collapse" id="sub-item-3">
                        <li><a class="" href="index.php?page=pengaturan-krs">
                                <span class="fa fa-hourglass-1">&nbsp;</span> KRS Mahasiswa
                            </a>
                        </li>
                        <li><a class="" href="index.php?page=pengaturan-aplikasi">
                                <span class="fa fa-institution">&nbsp;</span> Profil Kampus
                            </a>
                        </li>                
                    </ul>
                </li>

            <?php endif; ?>

            <?php if ($_SESSION["level"]=="Dosen" or $_SESSION["level"]=="dosen"): ?>
                <li><a href="index.php?page=profil-dosen"><em class="fa fa-user-circle-o">&nbsp;</em> Profil</a></li>
                <li><a href="index.php?page=jadwal-mengajar"><em class="fa fa-calendar">&nbsp;</em> Jadwal Mengajar</a></li>
                <li><a href="index.php?page=nilai"><em class="fa fa-check-square-o">&nbsp;</em> Pengelolaan Nilai</a></li>
                <li><a class="" href="index.php?page=presensi">
                        <span class="fa fa-calendar-check-o">&nbsp;</span> Prensensi
                    </a>
                </li>   
            <?php endif; ?>

            <?php if ($_SESSION["level"]=="Mahasiswa" or $_SESSION["level"]=="mahasiswa"): ?>
                <li><a href="index.php?page=profil"><em class="fa fa-user-circle-o">&nbsp;</em> Profil</a></li>
                <li><a href="index.php?page=krs"><em class="fa fa-hourglass-2">&nbsp;</em> Kartu Rencana Studi</a></li>
                <li><a href="index.php?page=khs"><em class="fa fa-paper-plane">&nbsp;</em> Kartu Hasil Studi</a></li>
                <li><a href="index.php?page=transkrip-nilai"><em class="fa fa-tags">&nbsp;</em> Transkrip Nilai</a></li>
                <li><a href="index.php?page=rekap-presensi"><em class="fa fa-cube">&nbsp;</em> Rekap Presensi</a></li>
            <?php endif; ?>
        
            <li><a href="logout.php" id="keluar"><em class="fa fa-sign-out">&nbsp;</em> Keluar</a></li>
    </ul>
</div>
<!--/.sidebar-->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

<div class="preloader">
    <div class="loading">
      <img src="src/img/loading.gif">
    </div>
  </div>


<?php 
          if(isset($_GET['page'])){
            $page = $_GET['page'];
        
            switch ($page) {
                case 'beranda':
                    include "dist/beranda/index.php";
                    break;
                case 'mahasiswa':
                    include "dist/mahasiswa/index.php";
                    break;
                case 'profil':
                    include "dist/mahasiswa/profil.php";
                    break;
                case 'dosen':
                    include "dist/dosen/index.php";
                    break;
                case 'profil-dosen':
                    include "dist/dosen/profil.php";
                    break;
                case 'jadwal-mengajar':
                    include "dist/dosen/jadwal-mengajar.php";
                    break;
                case 'karyawan':
                    include "dist/karyawan/index.php";
                    break;
                case 'matakuliah':
                    include "dist/matakuliah/index.php";
                    break;
                case 'program-studi':
                    include "dist/program-studi/index.php";
                    break;
                case 'ruangan':
                    include "dist/ruangan/index.php";
                    break;
                case 'semester':
                    include "dist/semester/index.php";
                    break;
                case 'jadwal':
                    include "dist/jadwal/index.php";
                    break;
                case 'krs':
                    include "dist/krs/index.php";
                    break;
                case 'pengaturan-krs':
                    include "dist/krs/pengaturan.php";
                    break;
                case 'khs':
                    include "dist/khs/index.php";
                    break;
                case 'transkrip-nilai':
                    include "dist/transkrip-nilai/index.php";
                    break;
                case 'nilai':
                    include "dist/nilai/index.php";
                    break;
                case 'presensi':
                    if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'){
                        include "dist/presensi/index.php";
                    }else if($_SESSION["level"]=='Dosen' or $_SESSION["level"]=='dosen'){
                        include "dist/presensi-dosen/index.php";
                    }
             
                    break;
                case 'rekap-presensi':
                    include "dist/presensi-mhs/index.php";
                    break;
                case 'pengaturan-aplikasi':
                    include "dist/aplikasi/index.php";
                    break;
              default:
                echo "<center><h3>Maaf. Halaman tidak di temukan !</h3></center>";
                break;
            }
          }
      ?>

    <div id='ajax-wait'>
        <img src="src/img/loading.gif">
    </div>
    <script>

    $(document).ready( function () {
        loading();
    });

    //Fungsi untuk efek loading
    function loading(){
        $( document ).ajaxStart(function() {
        $( "#ajax-wait" ).css({
            left: ( $( window ).width() - 32 ) / 2 + "px", // 32 = lebar gambar
            top: ( $( window ).height() - 32 ) / 2 + "px", // 32 = tinggi gambar
            display: "block"
        })
        })
        .ajaxComplete( function() {
            $( "#ajax-wait" ).fadeOut();
        });
    }
    </script>

    <style>
        #ajax-wait {
        display: none;
        position: fixed;
        z-index: 1999
        }
    </style>

    <!--/.row-->
</div>
<!--/.main-->

<script src="template/js/bootstrap.min.js"></script>
<script src="template/js/chart.min.js"></script>
<script src="template/js/chart-data.js"></script>
<script src="template/js/easypiechart.js"></script>
<script src="template/js/easypiechart-data.js"></script>
<script src="template/js/bootstrap-datepicker.js"></script>
<script src="template/js/custom.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

<script src="/assets/chart/chart.js"></script>

</body>
</html>

<script>
   // fungsi hapus jadwal
   $('#keluar').on('click',function(){
        konfirmasi=confirm("Apakah anda yakin ingin keluar?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>