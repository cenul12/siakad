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
        <li class="active">Pengelolaan Presensi</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Pengelolaan Presensi
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">

                <div class="row">
                    <form action="#" method="get">
                    <input type="hidden" name="page" value="presensi"/>
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
                        <div class="col-sm-3">
                            <div class="form-group">
                                <select class="form-control" name="program_studi" required>
                                    <option value="">Pilih Program Studi</option>
                                    <?php
                                    include 'config/database.php';
                                    $ket="";
                                    //Perintah sql untuk menampilkan semua data pada tabel semester
                                    $hasil=mysqli_query($kon,"select * from program_studi");
                                    while ($data1 = mysqli_fetch_array($hasil)) {
                                        if (isset($_GET['program_studi'])) {
                                            $program_studi = addslashes(trim($_GET['program_studi']));
                            
                                            if ($program_studi==$data1['id_program_studi'])
                                            {
                                                $ket="selected";
                                            }else {
                                                $ket="";
                                            }
                                        }
                                    ?>
                                    <option <?php  echo $ket; ?> value="<?php echo $data1['id_program_studi'];?>" ><?php echo $data1['program_studi'];?></option>
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

                    include 'config/database.php';
                    $id_semester=addslashes(trim($_GET["semester"]));
                    $data1 = mysqli_fetch_array(mysqli_query($kon,"select * from semester where id_semester='$id_semester' limit 1")); 
                    $semester=$data1['semester'];


                    $id_program_studi=addslashes(trim($_GET["program_studi"]));
                    $data2 = mysqli_fetch_array(mysqli_query($kon,"select * from program_studi where id_program_studi='$id_program_studi' limit 1")); 
                    $program_studi=$data2['program_studi'];
                ?>

                <table class="table">
                    <tbody>
                        <tr>
                            <td>Semester</td>
                            <td width="75%">: <?php echo $semester;?></td>
                        </tr>
                        <tr>
                            <td>Program Studi</td>
                            <td width="75%">: <?php echo $program_studi; ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Matakuliah</th>
                                <th>Dosen</th>
                                <th>Ruangan</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                                <th>Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // include database
                            include 'config/database.php';
                        
                            $sql="select * from jadwal j 
                            inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
                            inner join dosen d on d.id_dosen=j.id_dosen
                            inner join ruangan r on r.id_ruangan=j.id_ruangan
                            where id_semester='$id_semester' and id_program_studi='$id_program_studi'
                            ";
                            $hasil=mysqli_query($kon,$sql);
                            $no=0;
                            $nama_hari="";
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                            $no++;
                            switch ($data['hari']):
                                case 1 : $nama_hari='Senin'; break;
                                case 2 : $nama_hari='Selasa'; break;
                                case 3 : $nama_hari='Rabu'; break;
                                case 4 : $nama_hari='Kamis'; break;
                                case 5 : $nama_hari='Jumaat'; break;
                                case 6 : $nama_hari='Sabtu'; break;
                                case 7 : $nama_hari='Minggu'; break;
                
                            endswitch;
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>

                            <td><?php echo $data['nama_matakuliah']; ?></td>
                            <td><?php echo $data['nama_dosen']; ?></td>
                            <td><?php echo $data['nama_ruangan']; ?></td>
                            <td><?php echo $nama_hari; ?></td>
                            <td><?php echo date("H:i",strtotime($data["jam_mulai"])); ?>- <?php echo date("H:i",strtotime($data["jam_selesai"])); ?> WIB</td>
                            <td>
                                <button id_jadwal="<?php echo $data['id_jadwal'];?>" class="tombol_input btn btn-primary btn-circle" ><i class="fa fa-edit"></i> Input Presensi</button>
                            </td>
                            <td>
                            <a href="dist/presensi/cetak.php?id_jadwal=<?php echo $data['id_jadwal']; ?>" target="blank" class="btn btn-success btn-circle" ><i class="fa fa-print"></i> Cetak Presensi </a>
                            </td>
                        </tr>
                        <!-- bagian akhir (penutup) while -->
                        <?php endwhile; ?>
                        </tbody>
                    </table>
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
    //Even untuk input presensi
    $('.tombol_input').on('click',function(){
        var id_jadwal = $(this).attr("id_jadwal");
        $.ajax({
            url: 'dist/presensi/input.php',
            method: 'post',
            data: {id_jadwal:id_jadwal},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Input Presensi';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

