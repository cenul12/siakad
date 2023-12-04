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
        <li class="active">Pengelolaan Data Presensi</li>
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
                    <input type="hidden" name="page" value="khs"/>
                    <div class="col-sm-5">
                        <?php 
                            include 'config/database.php';
                            $kode_pengguna=$_SESSION["kode_pengguna"];
                            $sql="select * from pengguna p
                            inner join dosen d on d.kode_dosen=p.kode_pengguna
                            where p.kode_pengguna='$kode_pengguna' limit 1";
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
                                    <td>Semester</td>
                                    <td width="75%">
                                        <select class="form-control" name="semester" id="semester" required>
                                            <option value="">Pilih Semester</option>
                                            <?php
                                            include '../config/database.php';
                                            //Perintah sql untuk menampilkan semua data pada tabel semester
                                            $sql="select distinct s.id_semester,s.semester from semester s
                                            inner join jadwal j on j.id_semester=s.id_semester
                                            inner join dosen d on d.id_dosen=j.id_dosen
                                            where d.kode_dosen='$kode_pengguna'
                                            group by s.id_semester,s.semester
                                            order by s.id_semester asc";
                                            $hasil=mysqli_query($kon,$sql);
                                            while ($data = mysqli_fetch_array($hasil)) {
                                            ?>
                                            <option value="<?php echo $data['id_semester'];?>"><?php echo $data['semester'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Program Studi</th>
                                        <th>Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="data-khs">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    //Event pada combobox semester
    $('#semester').bind('change', function () {
        var semester=$("#semester").val();
        $.ajax({
            url: 'dist/presensi-dosen/data-presensi.php',
            method: 'POST',
            data:{semester:semester},
            success:function(data){
                $('#data-khs').html(data);    
                
            }
        }); 
    }); 
</script>


