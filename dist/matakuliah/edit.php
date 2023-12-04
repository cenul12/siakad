<?php
session_start();
    if (isset($_POST['edit_matakuliah'])) {
        
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Memulai transaksi
            mysqli_query($kon,"START TRANSACTION");
            $id_matakuliah=input($_POST["id_matakuliah"]);
            $kode_matakuliah=strtoupper(input($_POST["kode_matakuliah"]));
            $nama_matakuliah=ucwords(input($_POST["nama_matakuliah"]));
            $status=input($_POST["status"]);
            $sks=input($_POST["sks"]);

            $sql="update matakuliah set
            kode_matakuliah='$kode_matakuliah',
            nama_matakuliah='$nama_matakuliah',
            status='$status',
            sks='$sks'
            where id_matakuliah=$id_matakuliah";
        
            //Mengeksekusi query
            $edit_matakuliah=mysqli_query($kon,$sql);

            if ($edit_matakuliah) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=matakuliah&edit=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=matakuliah&edit=gagal");
            }
        } 
    }
?>

<?php 
    include '../../config/database.php';
    $id_matakuliah=$_POST["id_matakuliah"];
    $sql="select * from matakuliah where id_matakuliah=$id_matakuliah limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>

<form action="dist/matakuliah/edit.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <input name="id_matakuliah" id="id_matakuliah" value="<?php echo $data['id_matakuliah'];?>" type="hidden" class="form-control">
            </div>
            <div class="form-group">
                <label>Kode Matakuliah:</label>
                <input name="kode_matakuliah" id="kode" onkeypress="return event.charCode != 32" value="<?php echo $data['kode_matakuliah'];?>" type="text" class="form-control" placeholder="Masukan Kode Matakuliah" required>
                <span id="info_kode"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Nama Matakuliah:</label>
                <input type="text" name="nama_matakuliah" class="form-control"  value="<?php echo $data['nama_matakuliah'];?>" placeholder="Masukan Nama Matakuliah" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Satatus:</label>
                <select class="form-control" name="status" required>
                <?php
                    $status = array("W", "P");
                    $jum=count($status)-1;
                    for ($i=0;$i<=$jum;$i++):
                    ?>
                    <option <?php if ($status[$i]==$data['status']) echo "selected"; ?> value="<?php echo $status[$i];?>"><?php echo $status[$i];?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Jumlah SKS:</label>
                <input type="text" name="sks" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="<?php echo $data['sks'];?>" placeholder="Masukan Jumlah SKS" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="edit_matakuliah"  id="Submit"  class="btn btn-warning">Update</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>
<script>

    $('#kode').bind('keyup', function () {
        cek_kode();
    });

    function cek_kode(){
        var kode=$("#kode").val();
        $.ajax({
          url: 'dist/matakuliah/cek-kode.php',
          method: 'POST',
          data:{kode:kode},
          success:function(data){
            $("#info_kode").html(data);
          }
      }); 
    }
</script>

