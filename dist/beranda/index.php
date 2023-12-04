<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Beranda</li>
    </ol>
</div>
<!--/.row-->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Beranda
            </div>
            <div class="panel-body">
            <!--Menampilkan nama pengguna berdasarkan masing-masing level-->
            <?php if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):?>
                <h3>Selamat Datang  <?php echo  $_SESSION["nama_karyawan"]; ?></h3>
            <?php endif; ?>
            <?php if ($_SESSION['level']=='Dosen' or $_SESSION['level']=='dosen'):?>
                <h3>Selamat Datang  <?php echo  $_SESSION["nama_dosen"]; ?></h3>
            <?php endif; ?>
            <?php if ($_SESSION['level']=='Mahasiswa' or $_SESSION['level']=='mahasiswa'):?>
                <h3>Selamat Datang <?php echo  $_SESSION["nama_mahasiswa"]; ?></h3>
            <?php endif; ?>
            <?php 
                //Mengambil profil aplikasi
                include 'config/database.php';
                $query = mysqli_query($kon, "select * from profil_aplikasi limit 1");    
                $row = mysqli_fetch_array($query);
            ?>
            <p>Selamat Datang di Portal Akademik. Portal Akademik adalah sistem yang memungkinkan para civitas akademika <?php echo $row['nama_kampus'];?> untuk menerima informasi dengan lebih cepat melalui Internet. Sistem ini diharapkan dapat memberi kemudahan setiap civitas akademika untuk melakukan aktivitas-aktivitas akademik. Selamat menggunakan fasilitas ini.</p>
            </div>
        </div>
    </div>
</div>