<div class="row">
    <div class="col-sm-6">
    <?php 
        include '../../config/database.php';
        $id_jadwal=$_POST['id_jadwal'];

        $sql="select * from jadwal j
        inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
        inner join program_studi p on p.id_program_studi=j.id_program_studi 
        inner join semester s on s.id_semester=j.id_semester 
        where j.id_jadwal=$id_jadwal";

        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil); 
    ?>
        <table class="table">
            <tbody>
                <tr>
                    <td>Kode</td>
                    <td>: <?php echo $data['kode_matakuliah'];?></td>
                </tr>
                <tr>
                    <td>Matakuliah</td>
                    <td>: <?php echo $data['nama_matakuliah'];?></td>
                </tr>
                <tr>
                    <td>SKS</td>
                    <td>: <?php echo $data['sks'];?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:  <?php echo $data['status'] == 'W' ? 'Wajib' : 'Pilihan';?> </td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>: <?php echo $data['program_studi'];?></td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>: <?php echo $data['semester'];?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div id="pemberitahuan"></div>

        <form id="form_nilai">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th width="50%">Mahasiswa</th>      
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // include database
                        include '../../config/database.php';

                        $sql="select * from krs k
                        inner join jadwal j on j.id_jadwal=k.id_jadwal
                        left join mahasiswa m on m.kode_mahasiswa=k.kode_mahasiswa
                        where k.id_jadwal=$id_jadwal";
                
                        $hasil=mysqli_query($kon,$sql);
                        $no=0;
                        //Menampilkan data dengan perulangan while
                        while ($data = mysqli_fetch_array($hasil)):
                        $no++;
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $data['nim']; ?></td>
                        <td><?php echo $data['nama_mahasiswa']; ?></td>
                        <td>
                            <div class="form-group">
                                <input type="hidden" name="id_krs[]" value="<?php echo $data['id_krs'];?>" />
                                <select class="form-control" name="nilai[]"  required>
                                    <option value="">Pilih</option>
                                    <?php
                                    $daftar_nilai = array("A", "B", "C","D","E");
                                    $jum=count($daftar_nilai)-1;
                                    for ($i=0;$i<=$jum;$i++):
                                    ?>
                                    <option <?php if ($daftar_nilai[$i]==$data['nilai']) echo "selected"; ?> value="<?php echo $daftar_nilai[$i];?>"><?php echo $daftar_nilai[$i];?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <!-- bagian akhir (penutup) while -->
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<button class="tombol_submit_nilai btn btn-success btn-circle" ><i class="fa fa-mouse-pointer"></i> Submit</button>
<a href="dist/nilai/cetak.php?id_jadwal=<?php echo $id_jadwal;?>" target="blank" class="btn btn-primary btn-circle" ><i class="fa fa-print"></i> Cetak</a>

<script>
    //Event saat user mengklik tombol submit nilai
    $('.tombol_submit_nilai').on('click',function(){

        konfirmasi=confirm("Apakah Anda yakin ingin menyimpan nilai?")
        if (konfirmasi){
            loading();
            var data = $('#form_nilai').serialize();
            $.ajax({
                url: 'dist/nilai/submit.php',
                method: 'post',
                data: data,
                success:function(data){
                    $('#pemberitahuan').show(500);
                    $('#pemberitahuan').html("<div class='alert alert-success'>Nilai telah disimpan!</div>");  
                    setTimeout(function(){
                        $('#pemberitahuan').hide(500);
                    },2000);  
                }
            });
        }else {
            return false;
        }
    });
</script>




