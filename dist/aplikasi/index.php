<?php 
  if ($_SESSION["level"]!='Karyawan' and $_SESSION["level"]!='karyawan'){
    echo"<br><div class='alert alert-danger'>Tidak memiliki hak akses</div>";
    exit;
  }
?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">Pengaturan Aplikasi</li>
    </ol>
</div>
<!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            Pengaturan Aplikasi
            </div>
            <div class="panel-body">
            <?php
                //Validasi untuk menampilkan pesan pemberitahuan saat user update pengaturan aplikasi
                if (isset($_GET['edit'])) {
                    if ($_GET['edit']=='berhasil'){
                        echo"<div class='alert alert-success'><strong>Berhasil!</strong> Pengaturan aplikasi telah diupdate</div>";
                    }else if ($_GET['edit']=='gagal'){
                        echo"<div class='alert alert-danger'><strong>Gagal!</strong> Pengaturan aplikasi gagal diupdate</div>";
                    }    
                }
            ?>

            <?php
                //Include database
                include 'config/database.php';
                //Mengambil data profil aplikasi
                $hasil=mysqli_query($kon,"select * from profil_aplikasi order by nama_kampus desc limit 1");
                $data = mysqli_fetch_array($hasil); 
            ?>
                <form action="dist/aplikasi/edit.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="hidden" class="form-control" value="<?php echo $data['id'];?>" name="id">  
                    </div>
                    <div class="form-group">
                        <label>Nama Kampus:</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_kampus'];?>" name="nama_kampus" placeholder="Masukan nama kampus" required>  
                    </div>
                    <div class="form-group">
                        <label>Nama Ketua (Pimpinan):</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_pimpinan'];?>" name="nama_pimpinan" placeholder="Masukan nama Ketua" required>  
                    </div>
                    <div class="form-group">
                        <label>Nama Ketua Bidang Akadmik:</label>
                        <input type="text" class="form-control" value="<?php echo $data['ketua_bid_akademik'];?>" name="ketua_bid_akademik" placeholder="Masukan nama Ketua" required>  
                    </div>
                    <div class="form-group">
                        <label>Alamat:</label>
                        <input type="text" class="form-control" value="<?php echo $data['alamat'];?>" placeholder="Masukan alamat kampus" name="alamat">  
                    </div>
                    <div class="form-group">
                        <label>No Telp:</label>
                        <input type="text" class="form-control" value="<?php echo $data['no_telp'];?>" placeholder="Masukan nomor telp" name="no_telp">  
                    </div>
                    <div class="form-group">
                        <label>Website:</label>
                        <input type="text" class="form-control" value="<?php echo $data['website'];?>" placeholder="Masukan alamat website" name="website">  
                    </div>
                    <div class="form-group">
                        <div id="msg"></div>
                        <label>Logo:</label>
                        <input type="file" name="logo" class="file" >
                            <div class="input-group my-3">
                                <input type="text" class="form-control" disabled placeholder="Upload Gambar" id="file">
                                <div class="input-group-append">
                                        <button type="button" id="pilih_logo" class="browse btn btn-dark">Pilih Logo</button>
                                </div>
                            </div>
                        <img src="dist/aplikasi/logo/<?php echo $data['logo'];?>" id="preview" width="10%" class="img-thumbnail">
                        <input type="hidden" name="logo_sebelumnya" value="<?php echo $data['logo'];?>" />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"name="ubah_aplikasi" >Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>
<script>

    $(document).on("click", "#pilih_logo", function() {
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