<?php 
  if ($_SESSION["level"]!='Dosen' and $_SESSION["level"]!='dosen'){
    echo"<br><div class='alert alert-danger'>Tidak memiliki hak akses</div>";
    exit;
  }
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Profil</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Profil
            <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">
                <?php 
                    include 'config/database.php';
                    $kode_pengguna=$_SESSION["kode_pengguna"];
                    $sql="select * from dosen where kode_dosen='$kode_pengguna' limit 1";
                    $hasil=mysqli_query($kon,$sql);
                    $data = mysqli_fetch_array($hasil); 
                ?>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>NIP</td>
                            <td width="75%">: <?php echo $data['nip'];?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td width="75%">: <?php echo $data['nama_dosen'];?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td width="75%">: <?php echo $data['jk'] == 1 ? 'Laki-laki' : 'Perempuan';?></td>
                        </tr>
                        <tr>
                            <td>email</td>
                            <td width="75%">: <?php echo $data['email'];?></td>
                        </tr>
                        <tr>
                            <td>No Telp</td>
                            <td width="75%">: <?php echo $data['no_telp'];?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td width="75%">: <?php echo $data['alamat'];?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div><!--/.row-->


