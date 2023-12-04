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
        <li class="active">Ruangan</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Ruangan
            <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">

            <?php
                //Validasi untuk menampilkan pesan pemberitahuan saat user menambah ruangan
                if (isset($_GET['add'])) {
                    if ($_GET['add']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data ruangan telah disimpan</div>";
                    }else if ($_GET['add']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data ruangan gagal disimpan</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus ruangan
                if (isset($_GET['edit'])) {
                    if ($_GET['edit']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data ruangan telah diupdate</div>";
                    }else if ($_GET['edit']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data ruangan gagal diupdate</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus ruangan
                if (isset($_GET['hapus'])) {
                    if ($_GET['hapus']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data ruangan telah dihapus</div>";
                    }else if ($_GET['hapus']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data ruangan gagal dihapus</div>";
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
                                <th>Kode</th>
                                <th>Nama Ruangan</th>
                                <th>Jumlah Kapasitas</th>
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

                            $sql="select * from ruangan order by id_ruangan asc limit $posisi,$batas";
                            $hasil=mysqli_query($kon,$sql);
                            $no=$posisi+1;
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                        
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['kode_ruangan']; ?></td>
                            <td><?php echo $data['nama_ruangan']; ?></td>
                            <td><?php echo $data['kapasitas']; ?></td>
                            <td>
                                
                                <button id_ruangan="<?php echo $data['id_ruangan'];?>" class="tombol_edit btn btn-warning btn-circle" ><i class="fa fa-edit"></i></button>
                                <a href="dist/ruangan/hapus.php?id_ruangan=<?php echo $data['id_ruangan']; ?>" class="btn-hapus-ruangan btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
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
                $query2 = mysqli_query($kon, "select * from ruangan");        
                $jmldata    = mysqli_num_rows($query2);
                $jmlhalaman = ceil($jmldata/$batas);
                ?>
                <div class="text-center">
                    <ul class="pagination">
                        <?php
                        for($i=1;$i<=$jmlhalaman;$i++) {
                            if ($i != $halaman) {
                                echo "<li class='page-item'><a class='page-link' href='index.php?page=ruangan&halaman=$i'>$i</a></li>";
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
    // Tambah ruangan
    $('#tombol_tambah').on('click',function(){
        $.ajax({
            url: 'dist/ruangan/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah ruangan';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>


<script>
    // Edit ruangan
    $('.tombol_edit').on('click',function(){
        var id_ruangan = $(this).attr("id_ruangan");
        $.ajax({
            url: 'dist/ruangan/edit.php',
            method: 'post',
            data: {id_ruangan:id_ruangan},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit ruangan';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
   // fungsi hapus ruangan
   $('.btn-hapus-ruangan').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus ruangan ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
