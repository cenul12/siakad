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
        <li class="active">Jadwal Perkuliahan</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Jadwal Perkuliahan
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">

                <div class="row">
                    <form action="#" method="get">
                    <input type="hidden" name="page" value="jadwal"/>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <select class="form-control" name="semester" required>
                                    <option value="">Pilih Semester</option>
                                    <?php
                                    include 'config/database.php';
                                    $ket="";
                                    //Perintah sql untuk menampilkan semua data pada tabel semester
                                    $hasil=mysqli_query($kon,"select * from semester");
                                    while ($data = mysqli_fetch_array($hasil)) {
                                        if (isset($_GET['semester'])) {
                                            $semester = addslashes(trim($_GET['semester']));
                            
                                            if ($semester==$data['id_semester'])
                                            {
                                                $ket="selected";
                                            }else {
                                                $ket="";
                                            }
                                        }
                                    ?>
                                    <option <?php echo $ket; ?> value="<?php echo $data['id_semester'];?>"  ><?php echo $data['semester'];?></option>
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
                    </form>
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
                if (isset($_GET['semester'])):
                ?>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" id_semester="<?php echo addslashes(trim($_GET['semester'])); ?>"  id="tombol_tambah">Tambah</button>
                </div>
                <?php
                    //Validasi untuk menampilkan pesan pemberitahuan saat user menambah jadwal
                    if (isset($_GET['add'])) {
                        if ($_GET['add']=='berhasil'){
                            echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data jadwal telah disimpan</div>";
                        }else if ($_GET['add']=='gagal'){
                            echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data jadwal gagal disimpan</div>";
                        }    
                    }

                    //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus jadwal
                    if (isset($_GET['hapus'])) {
                        if ($_GET['hapus']=='berhasil'){
                            echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data jadwal telah dihapus</div>";
                        }else if ($_GET['hapus']=='gagal'){
                            echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data jadwal gagal dihapus</div>";
                        }    
                    }

                    //Validasi untuk menampilkan pesan pemberitahuan saat jadwal bertabrakan
                    if (isset($_GET['jadwal'])) {
                        if ($_GET['jadwal']=='tabrakan'){
                            echo"<div class='alert alert-warning'><strong>Gagal!</strong> Pengaturan jadwal bertabrakan dengan jadwal yang lain.</div>";
                        }    
                    }
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Program Studi</th>
                                <th>Matakuliah</th>
                                <th>Dosen</th>
                                <th>Ruangan</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // include database
                            include 'config/database.php';

                            //Mengambil nilai semester
                            $id_semester=addslashes(trim($_GET['semester']));

                            $sql="select * from jadwal j
                            inner join program_studi p on p.id_program_studi=j.id_program_studi
                            inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
                            inner join dosen d on d.id_dosen=j.id_dosen
                            inner join ruangan r on r.id_ruangan=j.id_ruangan
                            where id_semester='$id_semester'
                            order by j.id_program_studi,hari,jam_mulai,j.id_ruangan asc";
                            $hasil=mysqli_query($kon,$sql);
                            $no=0;
                            $nama_hari="";
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                            $no++;
                            //Menentukan nama hari
                            switch ($data['hari']):
                                case 1 : $nama_hari='Senin'; break;
                                case 2 : $nama_hari='Selasa'; break;
                                case 3 : $nama_hari='Rabu'; break;
                                case 4 : $nama_hari='Kamis'; break;
                                case 5 : $nama_hari="Jum'at"; break;
                                case 6 : $nama_hari='Sabtu'; break;
                                case 7 : $nama_hari='Minggu'; break;
                            endswitch;
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['program_studi']; ?></td>
                            <td><?php echo $data['nama_matakuliah']; ?></td>
                            <td><?php echo $data['nama_dosen']; ?></td>
                            <td><?php echo $data['nama_ruangan']; ?></td>
                            <td><?php echo $nama_hari; ?></td>
                            <td><?php echo date("H:i",strtotime($data["jam_mulai"])); ?> WIB</td>
                            <td><?php echo date("H:i",strtotime($data["jam_selesai"])); ?> WIB</td>
                            <td>
                                <a href="dist/jadwal/hapus.php?id_jadwal=<?php echo $data['id_jadwal']; ?>&semester=<?php echo $id_semester;?>" class="btn-hapus-jadwal btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <!-- bagian akhir (penutup) while -->
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" id_semester="<?php echo addslashes(trim($_GET['semester'])); ?>"  id="tombol_cetak">Cetak</button>
                </div>

                <?php endif; ?>
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
    // Tambah jadwal
    $('#tombol_tambah').on('click',function(){
        var id_semester = $(this).attr("id_semester");

        $.ajax({
            url: 'dist/jadwal/tambah.php',
            method: 'post',
            data: {id_semester:id_semester},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Jadwal';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
    // Tambah jadwal
    $('#tombol_cetak').on('click',function(){
        var id_semester = $(this).attr("id_semester");

        $.ajax({
            url: 'dist/jadwal/cetak.php',
            method: 'post',
            data: {id_semester:id_semester},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Cetak Jadwal';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>


<script>
   // fungsi hapus jadwal
   $('.btn-hapus-jadwal').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus jadwal ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
