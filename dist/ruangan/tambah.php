<?php
session_start();
    if (isset($_POST['tambah_ruangan'])) {
        
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

            $kode_ruangan=strtoupper(input($_POST["kode"]));
            $nama_ruangan=ucwords(input($_POST["nama_ruangan"]));
            $kapasitas=input($_POST["kapasitas"]);

            $sql="insert into ruangan (kode_ruangan,nama_ruangan,kapasitas) values
            ('$kode_ruangan','$nama_ruangan','$kapasitas')";

            $simpan_ruangan=mysqli_query($kon,$sql);

            if ($simpan_ruangan) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=ruangan&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=ruangan&add=gagal");
            }
        }
    }
?>

<form action="dist/ruangan/tambah.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Kode Ruangan:</label>
                <input type="text" name="kode" id="kode"  onkeypress="return event.charCode != 32" class="form-control" placeholder="Masukan Kode Ruangan" required>
                <span id="info_kode"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Nama Ruangan:</label>
                <input type="text" name="nama_ruangan" class="form-control" placeholder="Masukan Nama Ruangan" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Kapasitas:</label>
                <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" name="kapasitas" class="form-control" placeholder="Masukan Jumlah Kapasitas" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="tambah_ruangan" id="Submit" class="btn btn-primary">Tambah</button>
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

