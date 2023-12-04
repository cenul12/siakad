<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Transkrip Nilai</li>
    </ol>
</div>
<!--/.row-->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Transkrip Nilai
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">
                <p>Transkrip Nilai berisi informasi nilai hasil studi mahasiswa mulai dari semester awal sampai dengan semester terakhir mahasiswa. Transkrip ini dapat dicetak dalam bentuk transkrip satu halaman.</p>
                <div class="row">
                    <input type="hidden" name="page" value="khs"/>
                    <div class="col-sm-5">
                        <?php 
                            include 'config/database.php';
                            $kode_pengguna=$_SESSION["kode_pengguna"];
                            $sql="select * from pengguna p
                            left join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
                            inner join program_studi s on s.id_program_studi=m.id_program_studi
                            where p.kode_pengguna='$kode_pengguna' limit 1";
                            $hasil=mysqli_query($kon,$sql);
                            $data = mysqli_fetch_array($hasil); 
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
                                        <th>Semester</th>
                                        <th>Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    // include database
                                    include 'config/database.php';
                                    $kode_pengguna=$_SESSION['kode_pengguna'];
                                    $skor=0;
                                    $jum_sks=0;
                                    $jum_skor=0;
                                    $ipk=0;
                                    $sql="select * from krs k
                                    inner join semester s on s.id_semester=k.id_semester
                                    inner join jadwal j on k.id_jadwal=j.id_jadwal 
                                    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
                                    inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa
                                    where a.kode_mahasiswa='$kode_pengguna' and nilai!='' ";
                                    $hasil=mysqli_query($kon,$sql);
                                    $jumlah_matkul = mysqli_num_rows($hasil);
                                    $no=0;
                                    //Menampilkan data dengan perulangan while
                                    while ($data = mysqli_fetch_array($hasil)):
                                    $no++;

                                    $jum_sks+=$data['sks'];

                                    switch($data['nilai']){
                                        case 'A' : $skor=4; break;
                                        case 'B' : $skor=3; break;
                                        case 'C' : $skor=2; break;
                                        case 'D' : $skor=1; break;
                                        case 'E' : $skor=0; break;
                                    }

                                    $jum_skor+=$skor;
                                    $ipk=$jum_skor/$jumlah_matkul;


                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $data['semester']; ?></td>
                                    <td><?php echo $data['nama_matakuliah']; ?></td>
                                    <td><?php echo $data['sks']; ?></td>
                                    <td><?php echo $data['nilai']; ?></td>
                                </tr>
                                <!-- bagian akhir (penutup) while -->
                                <?php 
                                    endwhile; 
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-5">
                            <table class="table table-borderles">
                                <tbody>
                                    <tr>
                                        <td>Jumlah SKS diambil</td>
                                        <td>: <?php echo $jum_sks;?></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah mata kuliah diambil</td>
                                        <td>: <?php echo  $jumlah_matkul; ?></td>
                                    </tr>
                                    <tr>
                                        <td>IP Kumulatif</td>
                                        <td>: <?php echo number_format($ipk,2);?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                    <a href="dist/transkrip-nilai/cetak.php" target="blank" class="btn btn-primary btn-circle" ><i class="fa fa-print"></i> Cetak</a>
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

