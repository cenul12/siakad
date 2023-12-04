<?php 
  if ($_SESSION["level"]!='Karyawan' and $_SESSION["level"]!='karyawan'){
    echo"<br><div class='alert alert-danger'>Tidak memiliki hak akses</div>";
    exit;
  }
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Mahasiswa</li>
    </ol>
</div><!--/.row-->


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Mahasiswa
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">
            <form action="#" method="get">
                <div class="row">
                
                    <input type="hidden" name="page" value="mahasiswa"/>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <select class="form-control" name="program_studi">
                                    <option value="">Pilih Program Studi</option>
                                    <?php
                                    include 'config/database.php';
                                    $ket="";
                                    //Perintah sql untuk menampilkan semua data pada tabel program_studi
                                    $hasil=mysqli_query($kon,"select * from program_studi");
                                    while ($data = mysqli_fetch_array($hasil)) {
                                        if (isset($_GET['program_studi'])) {
                                            $program_studi = addslashes(trim($_GET['program_studi']));
                            
                                            if ($program_studi==$data['id_program_studi'])
                                            {
                                                $ket="selected";
                                            }else {
                                                $ket="";
                                            }
                                        }
                                    ?>
                                    <option <?php echo $ket; ?> value="<?php echo $data['id_program_studi'];?>"  ><?php echo $data['program_studi'];?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Pilih</button>
                            </div>
                        </div>

                   
                </div>
    
            </div>
        </div>
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
            <?php
                //Validasi untuk menampilkan pesan pemberitahuan saat user menambah Mahasiswa
                if (isset($_GET['add'])) {
                    if ($_GET['add']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Mahasiswa telah disimpan</div>";
                    }else if ($_GET['add']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Mahasiswa gagal disimpan</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user setting username dan password 
                if (isset($_GET['pengguna'])) {
                    if ($_GET['pengguna']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Setting akun pengguna berhasil</div>";
                    }else if ($_GET['pengguna']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Setting akun pengguna gagal</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user mengedit Mahasiswa
                if (isset($_GET['edit'])) {
                    if ($_GET['edit']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Mahasiswa telah diupdate</div>";
                    }else if ($_GET['edit']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Mahasiswa gagal diupdate</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus Mahasiswa
                if (isset($_GET['hapus'])) {
                    if ($_GET['hapus']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Mahasiswa telah dihapus</div>";
                    }else if ($_GET['hapus']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Mahasiswa gagal dihapus</div>";
                    }    
                }
            ?>
        
                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="tombol_tambah">Tambah Data</button>
                </div>
             
                    <input type="hidden" name="page" value="mahasiswa"/>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <input type="text" name="cari" value="<?php if (isset($_GET['cari'])) echo addslashes(trim($_GET['cari'])); ?>" class="form-control input-sm" placeholder="Masukan NIM atau nama">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Cari</button>
                        </div>
                    </div>
                </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>No Telp</th>
                                <th>Program Studi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
        
                        <tbody>
                        <?php
                            // include database
                            include 'config/database.php';

                            $batas   = 10;
                            $halaman = @$_GET['halaman'];
                            if(empty($halaman)){
                                $posisi  = 0;
                                $halaman = 1;
                            }
                            else{
                                $posisi  = ($halaman-1) * $batas;
                            }


                            if (isset($_GET['program_studi']) and isset($_GET['cari'])){
                                if ($_GET['program_studi']!='' and $_GET['cari']!='') {
                                    $id_program_studi=addslashes(trim($_GET["program_studi"]));
                                    $cari=addslashes(trim($_GET["cari"]));
                              
                                    $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi where p.id_program_studi='$id_program_studi' and (nim like '%".addslashes(trim($_GET['cari']))."%' or nama_mahasiswa like '%".addslashes(trim($_GET['cari']))."%') order by id_mahasiswa asc limit $posisi,$batas";
                                }else if($_GET['program_studi']!='' and $_GET['cari']==''){
                                    $id_program_studi=addslashes(trim($_GET["program_studi"]));
                                    $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi where p.id_program_studi='$id_program_studi' order by id_mahasiswa asc limit $posisi,$batas";
                                }else if($_GET['program_studi']=='' and $_GET['cari']!='') {
                                    $cari=addslashes(trim($_GET["cari"]));
                                    $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi where nim like '%".addslashes(trim($_GET['cari']))."%' or nama_mahasiswa like '%".addslashes(trim($_GET['cari']))."%' order by id_mahasiswa asc limit $posisi,$batas";
                                }  else {
                                    $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi order by id_mahasiswa asc limit $posisi,$batas";
                                }
                            }else {
                                $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi order by id_mahasiswa asc limit $posisi,$batas";
                            }
                            
             
                            
                            $hasil=mysqli_query($kon,$sql);
                            $no=$posisi+1;
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                      
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['nim']; ?></td>
                            <td><?php echo $data['nama_mahasiswa']; ?></td>
                            <td><?php echo $data['jk'] == 1 ? 'Laki-laki' : 'Perempuan';?></td>
                            <td><?php echo $data['no_telp']; ?></td>
                            <td><?php echo $data['program_studi']; ?></td>
                            <td>
                                <button id_mahasiswa="<?php echo $data['id_mahasiswa'];?>" class="tombol_detail btn btn-success btn-circle" ><i class="fa fa-mouse-pointer"></i></button>
                                <button kode_mahasiswa="<?php echo $data['kode_mahasiswa'];?>" class="tombol_setting_pengguna btn btn-primary btn-circle" ><i class="fa fa-user"></i></button>
                                <button id_mahasiswa="<?php echo $data['id_mahasiswa'];?>" class="tombol_edit btn btn-warning btn-circle" ><i class="fa fa-edit"></i></button>
                                <a href="dist/mahasiswa/hapus.php?id_mahasiswa=<?php echo $data['id_mahasiswa']; ?>&kode_mahasiswa=<?php echo $data['kode_mahasiswa']; ?>" class="btn-hapus-mahasiswa btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <!-- bagian akhir (penutup) while -->
                        <?php 
                          $no++;
                          endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php

                if (isset($_GET['program_studi']) and isset($_GET['cari'])){
                    if ($_GET['program_studi']!='' and $_GET['cari']!='') {
                        $id_program_studi=addslashes(trim($_GET["program_studi"]));
                        $cari=addslashes(trim($_GET["cari"]));
                        $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi where p.id_program_studi='$id_program_studi' and (nim like '%".addslashes(trim($_GET['cari']))."%' or nama_mahasiswa like '%".addslashes(trim($_GET['cari']))."%') order by id_mahasiswa asc";
                    }else if($_GET['program_studi']!='' and $_GET['cari']==''){
                        $id_program_studi=addslashes(trim($_GET["program_studi"]));
                        $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi where p.id_program_studi='$id_program_studi' order by id_mahasiswa asc";
                    }else if($_GET['program_studi']=='' and $_GET['cari']!='') {
                        $cari=addslashes(trim($_GET["cari"]));
                        $sql="select * from mahasiswa m inner join program_studi p on p.id_program_studi=m.id_program_studi where nim like '%".addslashes(trim($_GET['cari']))."%' or nama_mahasiswa like '%".addslashes(trim($_GET['cari']))."%' order by id_mahasiswa asc";
                    }  else {
                        $sql="select * from mahasiswa";
                    }
                }else {
                    $sql="select * from mahasiswa";
                }

                $hasil=mysqli_query($kon,$sql);         
                $jmldata    = mysqli_num_rows($hasil);
                $jmlhalaman = ceil($jmldata/$batas);
                ?>
                <div class="text-center">
                    <ul class="pagination">
                        <?php
                        for($i=1;$i<=$jmlhalaman;$i++) {
                            if ($i != $halaman) {
                                if (isset($_GET['program_studi']) and isset($_GET['cari'])){
                                    if ($_GET['program_studi']!='' and $_GET['cari']!='') {
                                        $id_program_studi=addslashes(trim($_GET["program_studi"]));
                                        $cari=addslashes(trim($_GET["cari"]));
                                        echo "<li class='page-item'><a class='page-link' href='index.php?page=mahasiswa&program_studi=$id_program_studi&cari=$cari&halaman=$i'>$i</a></li>";
                                    }else if($_GET['program_studi']!='' and $_GET['cari']==''){
                                        $id_program_studi=addslashes(trim($_GET["program_studi"]));
                                        echo "<li class='page-item'><a class='page-link' href='index.php?page=mahasiswa&program_studi=$id_program_studi&cari=&halaman=$i'>$i</a></li>";
                                    }else if($_GET['program_studi']=='' and $_GET['cari']!='') {
                                        $cari=addslashes(trim($_GET["cari"]));
                                   
                                        echo "<li class='page-item'><a class='page-link' href='index.php?page=mahasiswa&cari=$cari&program_studi=&halaman=$i'>$i</a></li>";
                                    }  else {
                                        echo "<li class='page-item'><a class='page-link' href='index.php?page=mahasiswa&halaman=$i'>$i</a></li>";
                                    }
                                }else {
                                    echo "<li class='page-item'><a class='page-link' href='index.php?page=mahasiswa&halaman=$i'>$i</a></li>";
                                }
                               
                            } else {
                                echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div><!--/.row-->


<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title" id="judul"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div id="tampil_data">
                 <!-- Data akan di load menggunakan AJAX -->                   
            </div>  
        </div>
  
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>

<script>
    // Tambah mahasiswa
    $('#tombol_tambah').on('click',function(){
        $.ajax({
            url: 'dist/mahasiswa/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Mahasiswa';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
    // Detail mahasiswa
    $('.tombol_detail').on('click',function(){
        var id_mahasiswa = $(this).attr("id_mahasiswa");
        $.ajax({
            url: 'dist/mahasiswa/detail.php',
            method: 'post',
            data: {id_mahasiswa:id_mahasiswa},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Detail Mahasiswa';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
    // Setting pengguna
    $('.tombol_setting_pengguna').on('click',function(){
        var kode_mahasiswa = $(this).attr("kode_mahasiswa");
        $.ajax({
            url: 'dist/mahasiswa/setting-pengguna.php',
            method: 'post',
            data: {kode_mahasiswa:kode_mahasiswa},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Setting Akun';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
    // Edit mahasiswa
    $('.tombol_edit').on('click',function(){
        var id_mahasiswa = $(this).attr("id_mahasiswa");
        $.ajax({
            url: 'dist/mahasiswa/edit.php',
            method: 'post',
            data: {id_mahasiswa:id_mahasiswa},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit Mahasiswa';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
   // fungsi hapus mahasiswa
   $('.btn-hapus-mahasiswa').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus mahasiswa ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
