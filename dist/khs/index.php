<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Kartu Hasil Studi</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Kartu Hasil Studi
                <span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>Kartu Hasil Studi merupakan fasilitas yang dapat digunakan untuk melihat hasil studi mahasiswa persemester. Selain dapat dilihat secara online, hasil studi ini juga dapat dicetak.</p>
                    </div>
                    <input type="hidden" name="page" value="khs"/>
                    <div class="col-sm-5">
                        <?php 
                            include 'config/database.php';
                            //Mengambil kode pengguna (mahasiswa) dari variabel session
                            $kode_pengguna=$_SESSION["kode_pengguna"];
                            $sql="select * from pengguna p
                            inner join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
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
                                <tr>
                                    <td>Semester</td>
                                    <td width="75%">
                                        <select class="form-control" name="semester" id="semester" required>
                                            <option value="">Pilih Semester</option>
                                            <?php
                                            include '../config/database.php';
                                            //Perintah sql untuk menampilkan data semester yang direlasikan dengan tabel krs mahasiswa
                                            $sql="select distinct s.id_semester,s.semester from semester s
                                            inner join krs k on k.id_semester=s.id_semester
                                            where k.kode_mahasiswa='$kode_pengguna'
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
                                        <th>Matakuliah</th>
                                        <th>Status</th>
                                        <th>SKS</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody id="data-khs">
                                    <!--/.Data KHS akan di load menggunakan AJAX-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <table class="table table-borderles">
                            <tbody>
                                <tr>
                                    <td>Jumlah SKS diambil</td>
                                    <td>: <span id="jumlah_sks"></span></td>
                                </tr>
                                <tr>
                                    <td>Jumlah mata kuliah diambil</td>
                                    <td>: <span id="jumlah_matkul"></span></td>
                                </tr>
                                <tr>
                                    <td>IP Semester</td>
                                    <td>: <span id="ips"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                    <span id='tombol_cetak'></span>
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
    //Event pada combobox semester saat nilai combobox berubah event ini yang akan dijalankan
    $('#semester').bind('change', function () {
        if ($("#semester").val()==''){
            $('#tombol_cetak').hide(); 
        }else {
            $('#tombol_cetak').show(); 
        }

        var semester=$("#semester").val();

        $.ajax({
            url: 'dist/khs/data-khs.php',
            method: 'POST',
            data:{semester:semester},
            success:function(data){
                $('#data-khs').html(data);
                var get_jumlah_sks=$("#get_jumlah_sks").val();  
                var get_jumlah_matkul=$("#get_jumlah_matkul").val();  
                var get_ips=$("#get_ips").val();
                $('#jumlah_sks').text(get_jumlah_sks);
                $('#jumlah_matkul').text(get_jumlah_matkul);
                $('#ips').text(get_ips);
                $('#tombol_cetak').html("<a href='dist/khs/cetak.php?id_semester="+semester+"' target='blank' class='btn btn-primary btn-circle' ><i class='fa fa-print'></i> Cetak</a>");
            }
        }); 
    }); 
</script>
