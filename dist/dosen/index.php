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
        <li class="active">Dosen</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Dosen
            <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">
            <?php
                //Validasi untuk menampilkan pesan pemberitahuan saat user menambah dosen
                if (isset($_GET['add'])) {
                    if ($_GET['add']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Dosen telah disimpan</div>";
                    }else if ($_GET['add']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Dosen gagal disimpan</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user mengedit dosen
                if (isset($_GET['edit'])) {
                    if ($_GET['edit']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Dosen telah diupdate</div>";
                    }else if ($_GET['edit']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Dosen gagal diupdate</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus dosen
                if (isset($_GET['pengguna'])) {
                    if ($_GET['pengguna']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Setting akun pengguna berhasil</div>";
                    }else if ($_GET['pengguna']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Setting akun pengguna gagal</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus dosen
                if (isset($_GET['hapus'])) {
                    if ($_GET['hapus']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Dosen telah dihapus</div>";
                    }else if ($_GET['hapus']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Dosen gagal dihapus</div>";
                    }    
                }
            ?>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" id="tombol_tambah">Tambah Data</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Email</th>
                                <th>No Telp</th>
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


                            // Mengambil data dosen dari tabel dosen
                            $sql="select * from dosen order by id_dosen asc limit $posisi,$batas";
                            $hasil=mysqli_query($kon,$sql);
                            $no=$posisi+1;
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                            
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['nip']; ?></td>
                            <td><?php echo $data['nama_dosen']; ?></td>
                            <td><?php echo $data['jk'] == 1 ? 'Laki-laki' : 'Perempuan';?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['no_telp']; ?></td>
                            <td>
                                <button id_dosen="<?php echo $data['id_dosen'];?>" class="tombol_detail btn btn-success btn-circle" ><i class="fa fa-mouse-pointer"></i></button>
                                <button kode_dosen="<?php echo $data['kode_dosen'];?>" class="tombol_setting_pengguna btn btn-primary btn-circle" ><i class="fa fa-user"></i></button>
                                <button id_dosen="<?php echo $data['id_dosen'];?>" class="tombol_edit btn btn-warning btn-circle" ><i class="fa fa-edit"></i></button>
                                <a href="dist/dosen/hapus.php?id_dosen=<?php echo $data['id_dosen']; ?>&kode_dosen=<?php echo $data['kode_dosen']; ?>" class="btn-hapus-dosen btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
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
                $query2 = mysqli_query($kon, "select * from dosen");        
                $jmldata    = mysqli_num_rows($query2);
                $jmlhalaman = ceil($jmldata/$batas);
                ?>
                <div class="text-center">
                    <ul class="pagination">
                        <?php
                        for($i=1;$i<=$jmlhalaman;$i++) {
                            if ($i != $halaman) {
                                echo "<li class='page-item'><a class='page-link' href='index.php?page=dosen&halaman=$i'>$i</a></li>";
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
    // Tambah dosen
    $('#tombol_tambah').on('click',function(){
        $.ajax({
            url: 'dist/dosen/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Dosen';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
    // Tambah dosen
    $('.tombol_detail').on('click',function(){
        var id_dosen = $(this).attr("id_dosen");
        $.ajax({
            url: 'dist/dosen/detail.php',
            method: 'post',
            data: {id_dosen:id_dosen},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Detail Dosen';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
    // Tambah dosen
    $('.tombol_setting_pengguna').on('click',function(){
        var kode_dosen = $(this).attr("kode_dosen");
        $.ajax({
            url: 'dist/dosen/setting-pengguna.php',
            method: 'post',
            data: {kode_dosen:kode_dosen},
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
    // Tambah dosen
    $('.tombol_edit').on('click',function(){
        var id_dosen = $(this).attr("id_dosen");
        $.ajax({
            url: 'dist/dosen/edit.php',
            method: 'post',
            data: {id_dosen:id_dosen},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit Dosen';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
   // fungsi hapus dosen
   $('.btn-hapus-dosen').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus dosen ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
