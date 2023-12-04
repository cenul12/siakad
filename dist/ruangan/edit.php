<?php
session_start();
    if (isset($_POST['edit_ruangan'])) {
        
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
            $id_ruangan=input($_POST["id_ruangan"]);
            $kode_ruangan=strtoupper(input($_POST["kode"]));
            $nama_ruangan=ucwords(input($_POST["nama_ruangan"]));
            $kapasitas=input($_POST["kapasitas"]);

            $sql="update ruangan set
            kode_ruangan='$kode_ruangan',
            nama_ruangan='$nama_ruangan',
            kapasitas='$kapasitas'
            where id_ruangan=$id_ruangan";
        
            //Mengeksekusi query
            $edit_ruangan=mysqli_query($kon,$sql);

            if ($edit_ruangan) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=ruangan&edit=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=ruangan&edit=gagal");
            }
        } 
    }
?>

<?php 
    include '../../config/database.php';
    $id_ruangan=$_POST["id_ruangan"];
    $sql="select * from ruangan where id_ruangan=$id_ruangan limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>

<form action="dist/ruangan/edit.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <input name="id_ruangan" value="<?php echo $data['id_ruangan'];?>" type="hidden" class="form-control">
            </div>
            <div class="form-group">
                <label>Kode Ruangan:</label>
                <input type="text" name="kode" id="kode" value="<?php echo $data['kode_ruangan'];?>"  onkeypress="return event.charCode != 32" class="form-control" placeholder="Masukan Kode Ruangan" required>
                <span id="info_kode"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Nama ruangan:</label>
                <input type="text" name="nama_ruangan" class="form-control"  value="<?php echo $data['nama_ruangan'];?>" placeholder="Masukan Nama Ruangan" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Jumlah Kapasitas:</label>
                <input type="text" name="kapasitas" class="form-control"   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" value="<?php echo $data['kapasitas'];?>" placeholder="Masukan Jumlah Kapasitas" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="edit_ruangan" id="Submit" class="btn btn-warning">Update</button>
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
          url: 'dist/ruangan/cek-kode.php',
          method: 'POST',
          data:{kode:kode},
          success:function(data){
            $("#info_kode").html(data);
          }
      }); 
    }
</script>

