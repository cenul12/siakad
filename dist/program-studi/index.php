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
        <li class="active">Program Studi</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Program Studi
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">

            <?php
                //Validasi untuk menampilkan pesan pemberitahuan saat user menambah Program Studi
                if (isset($_GET['add'])) {
                    if ($_GET['add']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Program Studi telah disimpan</div>";
                    }else if ($_GET['add']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Program Studi gagal disimpan</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus Program Studi
                if (isset($_GET['edit'])) {
                    if ($_GET['edit']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Program Studi telah diupdate</div>";
                    }else if ($_GET['edit']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Program Studi gagal diupdate</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus Program Studi
                if (isset($_GET['hapus'])) {
                    if ($_GET['hapus']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Program Studi telah dihapus</div>";
                    }else if ($_GET['hapus']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Program Studi gagal dihapus</div>";
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
                                <th>Program Studi</th>
                                <th>Ketua</th>
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

                            $sql="select * from program_studi order by id_program_studi asc limit $posisi,$batas";
                            $hasil=mysqli_query($kon,$sql);
                            $no=$posisi+1;
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                        
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['program_studi']; ?></td>
                            <td><?php echo $data['ketua']; ?></td>
                            <td>
                                <button id_program_studi="<?php echo $data['id_program_studi'];?>" class="tombol_edit btn btn-warning btn-circle" ><i class="fa fa-edit"></i></button>
                                <a href="dist/program-studi/hapus.php?id_program_studi=<?php echo $data['id_program_studi']; ?>" class="tombol_hapus btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
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
                $query2 = mysqli_query($kon, "select * from program_studi");        
                $jmldata    = mysqli_num_rows($query2);
                $jmlhalaman = ceil($jmldata/$batas);
                ?>
                <div class="text-center">
                    <ul class="pagination">
                        <?php
                        for($i=1;$i<=$jmlhalaman;$i++) {
                            if ($i != $halaman) {
                                echo "<li class='page-item'><a class='page-link' href='index.php?page=program-studi&halaman=$i'>$i</a></li>";
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
    // Tambah Program Studi
    $('#tombol_tambah').on('click',function(){
        $.ajax({
            url: 'dist/program-studi/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Program Studi';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>


<script>
    // Edit Program Studi
    $('.tombol_edit').on('click',function(){
        var id_program_studi = $(this).attr("id_program_studi");
        $.ajax({
            url: 'dist/program-studi/edit.php',
            method: 'post',
            data: {id_program_studi:id_program_studi},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit Program Studi';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
   // fungsi hapus Program Studi
   $('.tombol_hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus Program Studi ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
