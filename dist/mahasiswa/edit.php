<?php
session_start();
    if (isset($_POST['edit_mahasiswa'])) {
        
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

            $id_mahasiswa=input($_POST["id_mahasiswa"]);
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
            $program_studi=input($_POST["program_studi"]);
            $dosen_pembimbing=input($_POST["dosen_pembimbing"]);
            
            $foto_saat_ini=$_POST['foto_saat_ini'];
            $foto_baru = $_FILES['foto_baru']['name'];
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $x = explode('.', $foto_baru);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['foto_baru']['size'];
            $file_tmp = $_FILES['foto_baru']['tmp_name'];


            if (!empty($foto_baru)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload foto baru
                    move_uploaded_file($file_tmp, 'foto/'.$foto_baru);
    
                    //Menghapus foto lama, foto yang dihapus selain foto default
                    if ($foto_saat_ini!='foto_default.png'){
                        unlink("foto/".$foto_saat_ini);
                    }
                    
                    $sql="update mahasiswa set
                    nama_mahasiswa='$nama_mahasiswa',
                    nik='$nik',
                    nim='$nim',
                    tempat_lahir='$tempat_lahir',
                    tanggal_lahir='$tanggal_lahir',
                    jk='$jk',
                    kewarganegaraan='$kewarganegaraan',
                    agama='$agama',
                    nama_ibu='$nama_ibu',
                    email='$email',
                    no_telp='$no_telp',
                    alamat='$alamat',
                    kode_pos='$kode_pos',
                    provinsi='$provinsi',
                    kabupaten='$kabupaten',
                    kecamatan='$kecamatan',
                    pendidikan='$pendidikan',
                    sekolah='$sekolah',
                    nilai_raport='$nilai_raport',
                    id_program_studi='$program_studi',
                    dosen_pembimbing='$dosen_pembimbing',
                    foto='$foto_baru'
                    where id_mahasiswa=$id_mahasiswa";
                }
            }else {
                $sql="update mahasiswa set
                nama_mahasiswa='$nama_mahasiswa',
                nik='$nik',
                nim='$nim',
                tempat_lahir='$tempat_lahir',
                tanggal_lahir='$tanggal_lahir',
                jk='$jk',
                kewarganegaraan='$kewarganegaraan',
                agama='$agama',
                nama_ibu='$nama_ibu',
                email='$email',
                no_telp='$no_telp',
                alamat='$alamat',
                kode_pos='$kode_pos',
                provinsi='$provinsi',
                kabupaten='$kabupaten',
                kecamatan='$kecamatan',
                pendidikan='$pendidikan',
                sekolah='$sekolah',
                nilai_raport='$nilai_raport',
                id_program_studi='$program_studi',
                dosen_pembimbing='$dosen_pembimbing'
                where id_mahasiswa=$id_mahasiswa";
            }

            //Mengeksekusi query 
            $edit_mahasiswa=mysqli_query($kon,$sql);

            if ($edit_mahasiswa) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=mahasiswa&edit=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=mahasiswa&edit=gagal");
            }
        } 
    }
?>

<?php 
    include '../../config/database.php';
    $id_mahasiswa=$_POST["id_mahasiswa"];
    $sql="select * from mahasiswa where id_mahasiswa=$id_mahasiswa limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>

<form action="dist/mahasiswa/edit.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <input type="hidden" name="id_mahasiswa" class="form-control" value="<?php echo $data['id_mahasiswa'];?>">
    </div>
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>NIM (Nomor Induk Mahasiswa):</label>
                <input type="text" id="nim" name="nim" value="<?php echo $data['nim'];?>" class="form-control" placeholder="Masukan NIM" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required>
                <span id="info_nim"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama_mahasiswa" class="form-control" value="<?php echo $data['nama_mahasiswa'];?>" placeholder="Masukan Nama Lengkap" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Nomor Identitas (NIK):</label>
                <input type="text" name="nik" class="form-control" value="<?php echo $data['nik'];?>" placeholder="Masukan Nomor NIK" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" class="form-control" value="<?php echo $data['tempat_lahir'];?>" placeholder="Masukan Tempat Lahir" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo $data['tanggal_lahir'];?>" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" <?php if (isset($data['jk']) && $data['jk']==1) echo "checked"; ?> class="form-check-input" name="jk" value="1" required>Laki-laki
                    </label>
                    <label class="form-check-label">
                        <input type="radio" <?php if (isset($data['jk']) && $data['jk']==2) echo "checked"; ?> class="form-check-input" name="jk" value="2" required>Perempuan
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Kewarganegaraan:</label>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" <?php if (isset($data['kewarganegaraan']) && $data['kewarganegaraan']=="WNI") echo "checked"; ?> class="form-check-input" name="kewarganegaraan" value="WNI" required>WNI
                    </label>
                    <label class="form-check-label">
                        <input type="radio" <?php if (isset($data['kewarganegaraan']) && $data['kewarganegaraan']=="WNA") echo "checked"; ?> class="form-check-input" name="kewarganegaraan" value="WNA" required>WNA
                    </label>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Agama:</label>
                <select class="form-control" name="agama" required>
                    <?php
                    $daftar_agama = array("Islam", "Kristen", "Katolik","Hindu","Budha","Lainnya");
                    $jum=count($daftar_agama)-1;
                    for ($i=0;$i<=$jum;$i++):
                    ?>
                    <option <?php if ($daftar_agama[$i]==$data['agama']) echo "selected"; ?> value="<?php echo $daftar_agama[$i];?>"><?php echo $daftar_agama[$i];?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Nama Ibu Kandung:</label>
                <input type="text" name="nama_ibu" class="form-control" value="<?php echo $data['nama_ibu'];?>" placeholder="Masukan Nama Ibu Kandung" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $data['email'];?>" placeholder="Masukan Email" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>No Telp:</label>
                <input type="text" name="no_telp" class="form-control" value="<?php echo $data['no_telp'];?>" placeholder="Masukan No Telp" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <label>Alamat:</label>
                <textarea class="form-control" name="alamat" rows="2" id="alamat"><?php echo $data['alamat'];?></textarea>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Kode Pos:</label>
                <input type="text" name="kode_pos" class="form-control" value="<?php echo $data['kode_pos'];?>" placeholder="Kode Pos"/>
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
                    while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                    <option <?php if ($data['provinsi']==$row['id_prov']) echo "selected"; ?> value="<?php echo $row['id_prov'];?>"><?php echo $row['nama'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Kabupaten:</label>
                <select class="form-control" name="kabupaten" id="kabupaten" required>
                    <!-- Kabupaten akan diload menggunakan ajax, dan ditampilkan disini -->
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel kabupaten
                    $sql="select * from kabupaten";
                    $hasil=mysqli_query($kon,$sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                    <option <?php if ($data['kabupaten']==$row['id_kab']) echo "selected"; ?> value="<?php echo $row['id_kab'];?>"><?php echo $row['nama'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Kecamatan:</label>
                <select class="form-control" name="kecamatan" id="kecamatan" required>
                    <!-- Kecamatan akan diload menggunakan ajax, dan ditampilkan disini -->
                    
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel kecamatan
                    $sql="select * from kecamatan";
                    $hasil=mysqli_query($kon,$sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                    <option <?php if ($data['kecamatan']==$row['id_kec']) echo "selected"; ?> value="<?php echo $row['id_kec'];?>"><?php echo $row['nama'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pendidikan Terakhir:</label>
                <select class="form-control" name="pendidikan" required>
                    <?php
                    $daftar_pendidikan = array("SMA-IPA", "SMA-IPS", "SMK-IPA","SMK-IPS");
                    $jum=count($daftar_pendidikan)-1;
                    for ($i=0;$i<=$jum;$i++):
                    ?>
                    <option <?php if ($daftar_pendidikan[$i]==$data['pendidikan']) echo "selected"; ?> value="<?php echo $daftar_pendidikan[$i];?>"><?php echo $daftar_pendidikan[$i];?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Nama Sekolah:</label>
                <input type="text" name="sekolah" class="form-control" value="<?php echo $data["sekolah"];?>" placeholder="Masukan Nama Sekolah" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Rata-rata Nilai Rapor Kelas 12:</label>
                <input type="text" name="nilai_raport" class="form-control" value="<?php echo $data['nilai_raport'];?>" placeholder="Masukan Rata-rata nilai raport">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Program Studi:</label>
                <select class="form-control" name="program_studi" required>
                <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel program_studi
                    $sql="select * from program_studi";
                    $hasil=mysqli_query($kon,$sql);
                    while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                    <option <?php if ($data['id_program_studi']==$row['id_program_studi']) echo "selected"; ?> value="<?php echo $row['id_program_studi'];?>"><?php echo $row['program_studi'];?></option>
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
                    while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                    <option <?php if ($data['dosen_pembimbing']==$row['id_dosen']) echo "selected"; ?> value="<?php echo $row['id_dosen'];?>"><?php echo $row['nama_dosen'];?></option>
                    <?php
                    }
                ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
        <label>Foto:</label><br>
            <img src="dist/mahasiswa/foto/<?php echo $data['foto'];?>" id="preview" width="90%" class="rounded" alt="Cinque Terre">
            <input type="hidden" name="foto_saat_ini" value="<?php echo $data['foto'];?>" class="form-control" />
        </div>
        <div class="col-sm-4">
            <div id="msg"></div>
            <label>Upload Foto Baru:</label>
            <input type="file" name="foto_baru" class="file" >
            <div class="input-group my-3">
                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                <div class="input-group-append">
                        <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <br>
            <button type="submit" name="edit_mahasiswa" id="Submit" class="btn btn-warning">Update</button>
        </div>
    </div>
</form>

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
