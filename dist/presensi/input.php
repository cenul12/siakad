<form id="form_presensi">
<div class="row">
    <div class="col-sm-12">
        <?php
            include '../../config/database.php';
            $id_jadwal=addslashes(trim($_POST['id_jadwal']));
            $hasil=mysqli_query($kon,"select id_krs from krs where id_jadwal='$id_jadwal'");
            $jumlah = mysqli_num_rows($hasil);

            if ($jumlah<1){
                echo"<div class='alert alert-warning'>Belum ada mahasiswa yang mengambil matakuliah ini.</div>";
                echo "<script> document.getElementById('tombol_submit_presensi').disabled = true; </script>";
                echo "<script> document.getElementById('pertemuan').disabled = true; </script>";
            }
        ?>
    </div>
    <div class="col-sm-6">
    <?php 
        include '../../config/database.php';
        $id_jadwal=addslashes(trim($_POST['id_jadwal']));
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
                    <td>Matakuliah</td>
                    <td>: <?php echo $data['nama_matakuliah'];?></td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>: <?php echo $data['program_studi'];?></td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>: <?php echo $data['semester'];?></td>
                </tr>
                <tr>
                    <td>Pertemuan</td>
                    <td>
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
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div id="pemberitahuan"></div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th width="50%">Mahasiswa</th>      
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody id="data-presensi">
                                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<button class="btn btn-success btn-circle" id="tombol_submit_presensi" ><i class="fa fa-mouse-pointer"></i> Submit</button>

<script>
    //Event pada combobox pertemuan
    $('#pertemuan').bind('change', function () {
        var pertemuan=$("#pertemuan").val();
        var id_jadwal=$("#id_jadwal").val();
        $.ajax({
            url: 'dist/presensi/data-presensi.php',
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
                    $('#pemberitahuan').html("<div class='alert alert-success'>Presensi pada pertemuan "+pertemuan+" telah disimpan!</div>");  
                }
            });
        }else {
            return false;
        }
    });
</script>






