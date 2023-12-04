<?php
session_start();
    if (isset($_POST['edit_dosen'])) {
        
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
            $id_dosen=input($_POST["id_dosen"]);
            $nama=strtoupper(input($_POST["nama"]));
            $nip=input($_POST["nip"]);
            $jk=input($_POST["jk"]);
            $email=input($_POST["email"]);
            $no_telp=input($_POST["no_telp"]);
            $alamat=input($_POST["alamat"]);
          

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
                    
                    $sql="update dosen set
                    nama_dosen='$nama',
                    nip='$nip',
                    jk='$jk',
                    email='$email',
                    no_telp='$no_telp',
                    alamat='$alamat',
                    foto='$foto_baru'
                    where id_dosen=$id_dosen";
                }
            }else {
                $sql="update dosen set
                nama_dosen='$nama',
                nip='$nip',
                jk='$jk',
                email='$email',
                no_telp='$no_telp',
                alamat='$alamat'
                where id_dosen=$id_dosen";
            }

            //Mengeksekusi query 
            $edit_dosen=mysqli_query($kon,$sql);

            if ($edit_dosen) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=dosen&edit=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=dosen&edit=gagal");
            }
        }
       
    }
?>

<?php 
    include '../../config/database.php';
    $id_dosen=$_POST["id_dosen"];
    $sql="select * from dosen where id_dosen=$id_dosen limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>

<form action="dist/dosen/edit.php" method="post" enctype="multipart/form-data">

    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <input type="hidden" name="id_dosen" class="form-control" value="<?php echo $data['id_dosen'];?>">
            </div>
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $data['nama_dosen'];?>" placeholder="Masukan Nama Lengkap" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Nomor Induk Pegawai (NIP):</label>
                <input type="text" id="nip" name="nip" class="form-control"  value="<?php echo $data['nip']; ?>" placeholder="Masukan Nomor Induk Pegawai" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required>
                <span id="info_nip"></span>
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
        <div class="col-sm-7">
            <div class="form-group">
                <label>Alamat:</label>
                <textarea class="form-control" name="alamat" rows="4" id="alamat"><?php echo $data['alamat'];?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
        <label>Foto:</label><br>
            <img src="dist/dosen/foto/<?php echo $data['foto'];?>" id="preview" width="90%" class="rounded" alt="Cinque Terre">
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
            <div class="form-group">
                <br>
                <button type="submit" name="edit_dosen" id="Submit" class="btn btn-warning">Update</button>
            </div>
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
          url: 'dist/dosen/cek-nip.php',
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
