<?php 


    if (isset($_POST['berdasarkan'])):

?>
<form action="dist/jadwal/cetak-jadwal.php" method="get" target="blank">
<input type="hidden" name="id_semester" class="form-control" value="<?php echo addslashes(trim($_POST["id_semester"]));?>">

<?php

        $berdasarkan=addslashes(trim($_POST['berdasarkan']));

        if ($berdasarkan=="Program Studi"):
?>
            <div class="form-group">
                <label>Program Studi:</label>
                <select class="form-control" name="id_program_studi" required>
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel program_studi
                    $sql="select * from program_studi";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)) {
                        ?>
                    <option value="<?php echo $data['id_program_studi'];?>"><?php echo $data['program_studi'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>

<?php
        endif;
        if ($berdasarkan=="Dosen"):
?>
            <div class="form-group">
                <label>Dosen:</label>
                <select class="form-control" name="id_dosen" required>
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel dosen
                    $sql="select * from dosen";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)) {
                        ?>
                    <option value="<?php echo $data['id_dosen'];?>"><?php echo $data['nama_dosen'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            
<?php
        endif;


        if ($berdasarkan=="Hari"):
            ?>
            <div class="form-group">
                <label>Hari:</label>
                <select class="form-control" name="hari" required>
                    <?php
                    $nama_hari="";
                    $daftar_hari = array(1,2,3,4,5,6,7);
                    $jum=count($daftar_hari)-1;
                    for ($i=0;$i<=$jum;$i++):

                        switch ( $daftar_hari[$i]):
                            case 1 : $nama_hari='Senin'; break;
                            case 2 : $nama_hari='Selasa'; break;
                            case 3 : $nama_hari='Rabu'; break;
                            case 4 : $nama_hari='Kamis'; break;
                            case 5 : $nama_hari='Jumaat'; break;
                            case 6 : $nama_hari='Sabtu'; break;
                            case 7 : $nama_hari='Minggu'; break;

                        endswitch;
                    ?>
                    <option  value="<?php echo $daftar_hari[$i];?>"><?php echo $nama_hari;?></option>
                    <?php endfor; ?>
                </select>
            </div>
  
    <?php
         endif;
?>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" >Cetak</button>
    </div>

</form>
<?php
        exit;
    endif;
?>


<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label>Cetak Berdasarkan:</label>
            <select class="form-control" name="berdasarkan" id="berdasarkan" required>
                <option value="">Pilih</option>
                <?php
                    $item = array("Semua","Program Studi", "Dosen", "Hari");
                    $jum=count($item)-1;
                    for ($i=0;$i<=$jum;$i++):
                    ?>
                    <option value="<?php echo $item[$i];?>"><?php echo $item[$i];?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
        <input type="hidden" name="id_semester"  id="id_semester" class="form-control" value="<?php echo $_POST["id_semester"];?>">
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <div id="tampil"> </div>
        </div>
    </div>
</div>

<script>
    $('#berdasarkan').on('change',function(){

        var berdasarkan = $("#berdasarkan").val();
        var id_semester = $("#id_semester").val();

        $.ajax({
            url: 'dist/jadwal/cetak.php',
            method: 'post',
            data: {berdasarkan:berdasarkan,id_semester:id_semester},
            success:function(data){
                $('#tampil').html(data);  
            }
        });

    });
</script>