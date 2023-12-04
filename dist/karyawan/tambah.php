<?php
session_start();
    if (isset($_POST['tambah_karyawan'])) {
        
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

            $nama=strtoupper(input($_POST["nama"]));
            $nip=input($_POST["nip"]);
            $jk=input($_POST["jk"]);
            $email=input($_POST["email"]);
            $no_telp=input($_POST["no_telp"]);
            $alamat=input($_POST["alamat"]);
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $foto = $_FILES['foto']['name'];
            $x = explode('.', $foto);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['foto']['size'];
            $file_tmp = $_FILES['foto']['tmp_name'];

            include '../../config/database.php';
            $query = mysqli_query($kon, "SELECT max(id_karyawan) as id_terbesar FROM karyawan");
            $ambil= mysqli_fetch_array($query);
            $id_karyawan = $ambil['id_terbesar'];
            $id_karyawan++;
            //Membuat kode karyawan
            $huruf = "K";
            $kode_karyawan = $huruf . sprintf("%03s", $id_karyawan);
      
            if (!empty($foto)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload gambar
                    move_uploaded_file($file_tmp, 'foto/'.$foto);
                    //Sql jika menggunakan foto
                    $sql="insert into karyawan (kode_karyawan,nama_karyawan,nip,jk,email,no_telp,alamat,foto) values
                    ('$kode_karyawan','$nama','$nip','$jk','$email','$no_telp','$alamat','$foto')";
                }
            }else {
                //Sql jika tidak menggunakan foto, maka akan memakai gambar_default.png
                $foto="foto_default.png";
                $sql="insert into karyawan (kode_karyawan,nama_karyawan,nip,jk,email,no_telp,alamat,foto) values
                ('$kode_karyawan','$nama','$nip','$jk','$email','$no_telp','$alamat','$foto')";
            }

                  
            if (!empty($foto)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload gambar
                    move_uploaded_file($file_tmp, 'foto/'.$foto);
                    //Sql jika menggunakan foto
                    $sql="insert into karyawan (kode_karyawan,nama_karyawan,nip,jk,email,no_telp,alamat,foto) values
                    ('$kode_karyawan','$nama','$nip','$jk','$email','$no_telp','$alamat','$foto')";
                }
            }else {
                //Sql jika tidak menggunakan foto, maka akan memakai gambar_default.png
                $foto="foto_default.png";
                $sql="insert into karyawan (kode_karyawan,nama_karyawan,nip,jk,email,no_telp,alamat,foto) values
                ('$kode_karyawan','$nama','$nip','$jk','$email','$no_telp','$alamat','$foto')";
            }

            //Menyimpan ke tabel karyawan
            $simpan_karyawan=mysqli_query($kon,$sql);
            
            $sql="insert into pengguna (kode_pengguna) values
            ('$kode_karyawan')";

            //Menyimpan ke tabel pengguna
            $simpan_pengguna=mysqli_query($kon,$sql);

            if ($simpan_karyawan and $simpan_pengguna) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=karyawan&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=karyawan&add=gagal");
            }
        }
    }
?>

<form action="dist/karyawan/tambah.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukan Nama Lengkap" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Nomor Induk Pegawai (NIP):</label>
                <input type="text" id="nip" name="nip" class="form-control" placeholder="Masukan Nomor Induk Pegawai" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required>
                <span id="info_nip"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Masukan Email" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>No Telp:</label>
                <input type="text" name="no_telp" class="form-control" placeholder="Masukan No Telp" required>
            </div>
        </div>
        <div class="col-sm-5">
        <div class="form-group">
                <label>Jenis Kelamin:</label>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="jk" value="1" required>Laki-laki
                    </label>
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="jk" value="2" required>Perempuan
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>Alamat:</label>
                <textarea class="form-control" name="alamat" rows="4" id="alamat"></textarea>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <div id="msg"></div>
                <label>Foto:</label>
                <input type="file" name="foto" class="file" >
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload Foto" id="file">
                        <div class="input-group-append">
                                <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih</button>
                        </div>
                    </div>
                <img src="src/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="tambah_karyawan" id="Submit" class="btn btn-primary">Daftar</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>

<script>

    $('#nip').bind('keyup', function () {
        cek_nip();
    });

    function cek_nip(){
        var nip=$("#nip").val();
        $.ajax({
          url: 'dist/karyawan/cek-nip.php',
          method: 'POST',
          data:{nip:nip},
          success:function(data){
            $("#info_nip").html(data);
          }
      }); 
    }
</script>

<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>

<script>
    $(document).on("click", "#pilih_foto", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
    });
    $('input[type="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("preview").src = e.target.result;
    };
    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
    });

</script>
