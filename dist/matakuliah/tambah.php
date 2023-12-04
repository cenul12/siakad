<?php
session_start();
    if (isset($_POST['tambah_matakuliah'])) {
        
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

            $kode=strtoupper(input($_POST["kode"]));
            $nama_matakuliah=ucwords(input($_POST["nama_matakuliah"]));
            $status=input($_POST["status"]);
            $sks=input($_POST["sks"]);

            //Menambah matakuliah
            $sql="insert into matakuliah (kode_matakuliah,nama_matakuliah,status,sks) values
            ('$kode','$nama_matakuliah','$status','$sks')";

            $simpan_matakuliah=mysqli_query($kon,$sql);

            //Jika berhasil lakukan commit
            if ($simpan_matakuliah) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=matakuliah&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=matakuliah&add=gagal");
            }
        }
    }
?>


<form action="dist/matakuliah/tambah.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Kode Matakuliah:</label>
                <input type="text" name="kode" id="kode"  onkeypress="return event.charCode != 32" class="form-control" placeholder="Masukan Kode Matakuliah" required>
                <span id="info_kode"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Nama Matakuliah:</label>
                <input type="text" name="nama_matakuliah" class="form-control" placeholder="Masukan Nama Matakuliah" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Satatus:</label>
                <select class="form-control" name="status" required>
                    <option value="W">Wajib</option>
                    <option value="P">Pilihan</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Jumlah SKS:</label>
                <input type="text" name="sks" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" placeholder="Masukan Jumlah SKS" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="tambah_matakuliah" id="Submit" class="btn btn-primary">Tambah</button>
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

