<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Kartu Rencana Studi</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Kartu Rencana Studi
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">

            <?php
                //Validasi untuk menampilkan pesan pemberitahuan saat user menambah krs
                if (isset($_GET['add'])) {
                    if ($_GET['add']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data KRS telah disimpan</div>";
                    }else if ($_GET['add']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data KRS gagal disimpan</div>";
                    }    
                }

                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus krs
                if (isset($_GET['hapus'])) {
                    if ($_GET['hapus']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data KRS telah dihapus</div>";
                    }else if ($_GET['hapus']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data KRS gagal dihapus</div>";
                    }    
                }
                
                //Validasi untuk menampilkan pesan pemberitahuan saat user menghapus krs
                if (isset($_GET['jadwal'])) {
                    if ($_GET['jadwal']=='tabrakan'){
                        echo"<div class='alert alert-danger'><strong>Jadwal Bertabrakan!</strong> Tidak boleh memilih matakuliah dengan hari dan jam yang sama.</div>";
                    }    
                }

            ?>
                <div class="row">
                    <div class="col-sm-12">
                        <p>Kartu Rencana Studi merupakan fasilitas pengisian KRS secara online. Fasilitas KRS Online ini hanya dapat digunakan pada saat masa KRS atau masa revisi KRS. Mahasiswa dapat memilih matakuliah yang ingin diambil bersesuaian dengan jatah sks yang dimiliki dan matakuliah yang ditawarkan. Setelah melakukan pengisian KRS mahasiswa dapat mencetak KRS tersebut agar dapat ditandatangani oleh dosen pembimbingnya masing-masing.</p>
                    </div>
                    <input type="hidden" name="page" value="krs"/>
                    <div class="col-sm-5">
                        <?php 
                            include 'config/database.php';
                            //Mengambil kode pengguna dalam session
                            $kode_pengguna=$_SESSION["kode_pengguna"];
                            //Menampilkan data mahasiswa berdasarkan kode
                            $sql="select * from pengguna p
                            inner join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
                            inner join program_studi s on s.id_program_studi=m.id_program_studi
                            where p.kode_pengguna='$kode_pengguna' limit 1";
                            $hasil=mysqli_query($kon,$sql);
                            $data = mysqli_fetch_array($hasil); 
                            $id_program_studi=$data['id_program_studi'];
                        ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>NIM</td>
                                    <td width="75%">: <?php echo $data['nim'];?></td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td width="75%">: <?php echo $data['nama_mahasiswa'];?></td>
                                </tr>
                                <tr>
                                    <td>Program Studi</td>
                                    <td width="75%">: <?php echo $data['program_studi'];?></td>
                                </tr>
                                <tr>
                                    <td>Semester</td>
                                    <?php 
                                        include 'config/database.php';
                                        //Menampilkan data semester
                                        $hasil=mysqli_query($kon,"select * from pengaturan_krs p inner join semester s on p.id_semester=s.id_semester limit 1");
                                        $data = mysqli_fetch_array($hasil);
                                        $jum = mysqli_num_rows($hasil);
                                        if ($jum!=0){
                                            $id_semester=$data['id_semester'];
                                        }else {
                                            $id_semester=0;
                                        }
                                    ?>
                                    <td width="75%">: <?php if ($jum!=0) echo $data['semester'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        if ($jum==0){
                            echo"<div class='alert alert-danger'>Bukan periode KRS!</div>";
                        }else {
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Dosen</th>
                                        <th>Matakuliah</th>
                                        <th>Status</th>
                                        <th>SKS</th>
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

                                    $kode_pengguna=$_SESSION['kode_pengguna'];
                                    //Menampilkan data KRS 
                                    $sql="select * from krs k
                                    inner join jadwal j on k.id_jadwal=j.id_jadwal 
                                    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
                                    inner join dosen d on d.id_dosen=j.id_dosen
                                    inner join ruangan r on r.id_ruangan=j.id_ruangan
                                    inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa
                                    where k.id_semester=$id_semester and a.kode_mahasiswa='$kode_pengguna'
                                    order by hari, jam_mulai asc";
                                    $hasil=mysqli_query($kon,$sql);
                                    $no=0;
                                    $nama_hari="";
                                    //Menampilkan data dengan perulangan while
                                    while ($data = mysqli_fetch_array($hasil)):
                                    $no++;
                                    //Menapilkan nama hari
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
                                    <td><?php echo $data['nama_dosen']; ?></td>
                                    <td><?php echo $data['nama_matakuliah']; ?></td>
                                    <td><?php echo $data['status']; ?></td>
                                    <td><?php echo $data['sks']; ?></td>
                                    <td><?php echo $nama_hari; ?></td>
                                    <td><?php echo date("H:i",strtotime($data["jam_mulai"])); ?> WIB</td>
                                    <td><?php echo date("H:i",strtotime($data["jam_selesai"])); ?> WIB</td>
                                    <td>
                                        <a href="dist/krs/hapus.php?id_krs=<?php echo $data['id_krs']; ?>" class="btn-hapus-krs btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- bagian akhir (penutup) while -->
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="button" <?php if ($jum==0) echo 'disabled';?> class="btn btn-success" kode_mahasiswa="<?php echo $kode_pengguna; ?>" id_semester="<?php echo $id_semester; ?>" id_program_studi="<?php echo $id_program_studi; ?>" id="tombol_tambah">Tambah</button>
                                    <a href="dist/krs/cetak.php?id_semester=<?php echo $id_semester; ?>"  target="blank" class="btn btn-primary btn-circle" ><i class="fa fa-print"></i> Cetak</a>
                                </div>
                            </div>
                        </div>
                        <?php 
                        } 
                    ?>
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
    // Tambah krs
    $('#tombol_tambah').on('click',function(){
        var id_semester = $(this).attr("id_semester");
        var kode_mahasiswa = $(this).attr("kode_mahasiswa");
        var id_program_studi = $(this).attr("id_program_studi");
        $.ajax({
            url: 'dist/krs/tambah.php',
            method: 'post',
            data: {id_semester:id_semester,kode_mahasiswa:kode_mahasiswa,id_program_studi:id_program_studi},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah KRS';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>

<script>
   // fungsi hapus krs
   $('.btn-hapus-krs').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus krs ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
