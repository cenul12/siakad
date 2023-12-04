<?php
session_start();
    if (isset($_POST['tambah_mahasiswa'])) {
        
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

            $nama_mahasiswa=strtoupper(input($_POST["nama_mahasiswa"]));
            $nik=input($_POST["nik"]);
            $nim=input($_POST["nim"]);
            $tempat_lahir=ucwords(input($_POST["tempat_lahir"]));
            $tanggal_lahir=input($_POST["tanggal_lahir"]);
            $jk=input($_POST["jk"]);
            $kewarganegaraan=input($_POST["kewarganegaraan"]);
            $agama=input($_POST["agama"]);
            $nama_ibu=ucwords(input($_POST["nama_ibu"]));
            $email=input($_POST["email"]);
            $no_telp=input($_POST["no_telp"]);
            $alamat=input($_POST["alamat"]);
            $kode_pos=input($_POST["kode_pos"]);
            $provinsi=input($_POST["provinsi"]);
            $kabupaten=input($_POST["kabupaten"]);
            $kecamatan=input($_POST["kecamatan"]);
            $pendidikan=input($_POST["pendidikan"]);
            $sekolah=strtoupper(input($_POST["sekolah"]));
            $nilai_raport=input($_POST["nilai_raport"]);
            $id_program_studi=input($_POST["id_program_studi"]);
            $dosen_pembimbing=input($_POST["dosen_pembimbing"]);

            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $foto = $_FILES['foto']['name'];
            $x = explode('.', $foto);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['foto']['size'];
            $file_tmp = $_FILES['foto']['tmp_name'];
     
            include '../../config/database.php';
            $query = mysqli_query($kon, "SELECT max(id_mahasiswa) as id_terbesar FROM mahasiswa");
            $ambil = mysqli_fetch_array($query);
            $id_mhs = $ambil['id_terbesar'];
            $id_mhs++;


            //Membuat kode mahasiswa
            $huruf = "M";
            $kode_mahasiswa = $huruf . sprintf("%03s", $id_mhs);



            if (!empty($foto)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload gambar
                    move_uploaded_file($file_tmp, 'foto/'.$foto);
                    //Sql jika menggunakan foto
                    $sql="insert into mahasiswa (kode_mahasiswa,nama_mahasiswa,nik,nim,tempat_lahir,tanggal_lahir,jk,kewarganegaraan,agama,nama_ibu,email,no_telp,alamat,kode_pos,provinsi,kabupaten,kecamatan,pendidikan,sekolah,nilai_raport,id_program_studi,dosen_pembimbing,foto) values
                    ('$kode_mahasiswa','$nama_mahasiswa','$nik','$nim','$tempat_lahir','$tanggal_lahir','$jk','$kewarganegaraan','$agama','$nama_ibu','$email','$no_telp','$alamat','$kode_pos','$provinsi','$kabupaten','$kecamatan','$pendidikan','$sekolah','$nilai_raport','$id_program_studi','$dosen_pembimbing','$foto')";
                }
            }else {
                //Sql jika tidak menggunakan foto, maka akan memakai gambar_default.png
                $foto="foto_default.png";
                $sql="insert into mahasiswa (kode_mahasiswa,nama_mahasiswa,nik,nim,tempat_lahir,tanggal_lahir,jk,kewarganegaraan,agama,nama_ibu,email,no_telp,alamat,kode_pos,provinsi,kabupaten,kecamatan,pendidikan,sekolah,nilai_raport,id_program_studi,dosen_pembimbing,foto) values
                ('$kode_mahasiswa','$nama_mahasiswa','$nik','$nim','$tempat_lahir','$tanggal_lahir','$jk','$kewarganegaraan','$agama','$nama_ibu','$email','$no_telp','$alamat','$kode_pos','$provinsi','$kabupaten','$kecamatan','$pendidikan','$sekolah','$nilai_raport','$id_program_studi','$dosen_pembimbing','$foto')";
            }
    

            //Menyimpan ke tabel mahasiswa
            $simpan_mahasiswa=mysqli_query($kon,$sql);
  
            $sql="insert into pengguna (kode_pengguna) values
            ('$kode_mahasiswa')";

            //Menyimpan ke tabel pengguna
            $simpan_pengguna=mysqli_query($kon,$sql);

            if ($simpan_mahasiswa and $simpan_pengguna) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=mahasiswa&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=mahasiswa&add=gagal");
            }
        } 
    }
?>

<form action="dist/mahasiswa/tambah.php" method="post"  enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>NIM (Nomor Induk Mahasiswa):</label>
                <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukan NIM" required>
                <span id="info_nim"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama_mahasiswa" class="form-control" placeholder="Masukan Nama Lengkap" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Nomor Identitas (NIK):</label>
                <input type="text" name="nik" class="form-control" placeholder="Masukan Nomor NIK" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" class="form-control" placeholder="Masukan Tempat Lahir" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select class="form-control" name="jk" required>
                    <option>Pilih</option>
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Kewarganegaraan:</label>
                <select class="form-control" name="kewarganegaraan" required>
                    <option>Pilih</option>
                    <option value="WNI">Warga Negara Indonesia</option>
                    <option value="WNA">Warga Negara Asing</option>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Agama:</label>
                <select class="form-control" name="agama" required>
                    <option>Pilih</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Budha">Budha</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Nama Ibu Kandung:</label>
                <input type="text" name="nama_ibu" class="form-control" placeholder="Masukan Nama Ibu Kandung" required>
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
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <label>Alamat:</label>
                <textarea class="form-control" name="alamat" rows="2" id="alamat"></textarea>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Kode Pos:</label>
                <input type="text" name="kode_pos" class="form-control" placeholder="Kode Pos">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Provinsi:</label>
                <select class="form-control" name="provinsi" id="provinsi" required>
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel provinsi
                    $sql="select * from provinsi";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)) {
                        ?>
                    <option value="<?php echo $data['id_prov'];?>"><?php echo $data['nama'];?></option>
                    <?php
                        }
                ?>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Kabupaten/Kota:</label>
                <select class="form-control" id="kabupaten" name="kabupaten" required>
                    <!-- Kabupaten akan diload menggunakan ajax, dan ditampilkan disini -->
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Kecamatan:</label>
                <select class="form-control" name="kecamatan" id="kecamatan" required>
                    <!-- Kecamatan akan diload menggunakan ajax, dan ditampilkan disini -->
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pendidikan Terakhir:</label>
                <select class="form-control" name="pendidikan" required>
                    <option value="SMA-IPA">SMA - IPA</option>
                    <option value="SMA-IPS">SMA - IPS</option>
                    <option value="SMK-IPA">SMK - IPA</option>
                    <option value="SMK-IPS">SMK - IPS</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Nama Sekolah:</label>
                <input type="text" name="sekolah" class="form-control" placeholder="Masukan Nama Sekolah" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Rata-rata Nilai Rapor Kelas 12:</label>
                <input type="text" name="nilai_raport" class="form-control" placeholder="Masukan Rata-rata nilai raport">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
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
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Dosen Pembimbing:</label>
                <select class="form-control" name="dosen_pembimbing" required>
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
        </div>
    </div>

    <div class="row">
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
            <button type="submit" name="tambah_mahasiswa" id="Submit" class="btn btn-primary">Daftar</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>



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


<script>
    $('#nim').bind('keyup', function () {
        var nim=$("#nim").val();
        $.ajax({
          url: 'dist/mahasiswa/cek-nim.php',
          method: 'POST',
          data:{nim:nim},
          success:function(data){
            $("#info_nim").html(data);
          }
      }); 
    });
</script>


<script>

    $(document).ready( function () {
        get_kab();
        get_kec();
    });

    $("#provinsi").change(function() {
        get_kab();
        get_kec();
    });

    $("#kabupaten").change(function() {
        get_kec();

    });


    function get_kab(){
        var id_prov = $("#provinsi").val();
        $.ajax({
            url : "dist/mahasiswa/get-kab.php",
            method : "POST",
            data : {id_prov:id_prov},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value='+data[i].id_kab+'>'+data[i].nama+'</option>';
                }
                $('#kabupaten').html(html);

            }
        });
    }

    function get_kec(){
        var id_kab = $("#kabupaten").val();
        $.ajax({
            url : "dist/mahasiswa/get-kec.php",
            method : "POST",
            data : {id_kab:id_kab},
            async : false,
            dataType : 'json',
            success: function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value='+data[i].id_kec+'>'+data[i].nama+'</option>';
                }
                $('#kecamatan').html(html);

            }
        });
    }

</script>
