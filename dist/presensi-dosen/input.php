<form id="form_presensi">
<div class="row">
    <div class="col-sm-6">
    <?php 
        include '../../config/database.php';
        echo $id_jadwal=$_POST['id_jadwal'];

        $sql="select * from jadwal j
        inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
        inner join program_studi p on p.id_program_studi=j.id_program_studi 
        inner join semester s on s.id_semester=j.id_semester 
        where j.id_jadwal=$id_jadwal";

        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil); 
    ?>
       <input type="hidden" name="id_jadwal"  id="id_jadwal" value="<?php echo $id_jadwal; ?>" />
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
    <div class="col-sm-6">
        <select class="form-control" name="pertemuan" id="pertemuan" required>
            <option>Pilih Pertemuan</option>
            <?php
            for ($i=1; $i <=14 ; $i++){
            ?>
            <option value="<?php echo $i;?>" >Pertemuan Ke-<?php echo $i;?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<br>
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
                            <th width="50%">Nama</th>      
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody id="data-presensi">
       
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<button id="tombol_submit_presensi" class="btn btn-success btn-circle" ><i class="fa fa-mouse-pointer"></i> Submit</button>
<a href="dist/presensi/cetak.php?id_jadwal=<?php echo $id_jadwal;?>" target="blank" class="btn btn-primary btn-circle" ><i class="fa fa-print"></i> Cetak</a>
</form>

<script>
    //Event pada combobox pertemuan
    $('#pertemuan').bind('change', function () {
        var pertemuan=$("#pertemuan").val();
        var id_jadwal=$("#id_jadwal").val();
        $.ajax({
            url: 'dist/presensi-dosen/data.php',
            method: 'POST',
            data:{pertemuan:pertemuan,id_jadwal:id_jadwal},
            success:function(data){
                $('#data-presensi').html(data);    
                $('#pemberitahuan').html('')
            }
        });
    }); 
</script>


<script>

    //even untuk menyimpan data presensi yang telah diiunputkan
    $('#tombol_submit_presensi').on('click',function(){
        var pertemuan=$("#pertemuan").val();
        konfirmasi=confirm("Apakah Anda yakin ingin menyimpan presensi?")
        if (konfirmasi){
            loading();
            var data = $('#form_presensi').serialize();
            $.ajax({
                url: 'dist/presensi/submit.php',
                method: 'post',
                data: data,
                success:function(data){
                    $('#pemberitahuan').show(500);
                    $('#pemberitahuan').html("<div class='alert alert-success'>Presensi pada pertemuan "+pertemuan+" telah disimpan!</div>"); 
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




