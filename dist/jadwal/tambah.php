<?php
session_start();
    if (isset($_POST['tambah_jadwal'])) {
        
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

            $hari=input($_POST["hari"]);
     
            $jam_mulai=date("H:i",strtotime($_POST["jam_mulai"]));
            $jam_selesai=date("H:i",strtotime($_POST["jam_selesai"]));

            $jam_mulai1=date("H:i",strtotime($_POST["jam_mulai"]));
            $jam_selesai1=date("H:i",strtotime($_POST["jam_selesai"]));
            
            $id_matakuliah=input($_POST["id_matakuliah"]);
            $id_dosen=input($_POST["id_dosen"]);
            $id_ruangan=input($_POST["id_ruangan"]);
            $id_semester=input($_POST["id_semester"]);
            $id_program_studi=input($_POST["id_program_studi"]);

            $cek_ketersediaan_dosen[]=0;
            $cek_ketersediaan_ruangan[]=0;
          

            //Cek ketersediaan dosen
            while (strtotime($jam_mulai) <= strtotime($jam_selesai)) {
                
                $query = mysqli_query($kon, "select * from jadwal where '".$jam_mulai."' between jam_mulai and jam_selesai and hari='$hari' and id_semester='$id_semester' and id_dosen='$id_dosen'");
                $jam_mulai = date ("H:i", strtotime("+1 minute", strtotime($jam_mulai)));
                $cek_ketersediaan_dosen[] = mysqli_num_rows($query);
             
            }

          

            //Cek ketersediaan ruangan
            while (strtotime($jam_mulai1) <= strtotime($jam_selesai1)) {
                
                $query1 = mysqli_query($kon, "select * from jadwal where '".$jam_mulai1."' between jam_mulai and jam_selesai and hari='$hari' and id_semester='$id_semester' and id_ruangan='$id_ruangan'");
                $jam_mulai1 = date ("H:i", strtotime("+1 minute", strtotime($jam_mulai1)));
                $cek_ketersediaan_ruangan[] = mysqli_num_rows($query1);
              
            }

        

            if (in_array('1', $cek_ketersediaan_dosen) or in_array('1', $cek_ketersediaan_ruangan)){

                //Jika terjadi tabrakan maka proses tambah jadwal tidak dijalankan
                header("Location:../../index.php?page=jadwal&semester=$id_semester&program_studi=$id_program_studi&jadwal=tabrakan");
            }else {
                //Jika tidak ada jadwal yang bertabrakan maka data jadwal akan di simpan ke dalam database
                $sql="insert into jadwal (hari,jam_mulai,jam_selesai,id_matakuliah,id_dosen,id_ruangan,id_semester,id_program_studi) values
                ('$hari','".date("H:i",strtotime($_POST["jam_mulai"]))."','".date("H:i",strtotime($_POST["jam_selesai"]))."','$id_matakuliah','$id_dosen','$id_ruangan','$id_semester','$id_program_studi')";
    
                $simpan_jadwal=mysqli_query($kon,$sql);
    
                if ($simpan_jadwal) {
                    mysqli_query($kon,"COMMIT");
                    header("Location:../../index.php?page=jadwal&semester=$id_semester&add=berhasil");
                }
                else {
                    mysqli_query($kon,"ROLLBACK");
                    header("Location:../../index.php?page=jadwal&semester=$id_semester&add=gagal");
                }

               
            }

  


        }
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<?php 
    include '../../config/database.php';

    $id_semester=$_POST["id_semester"];
    $data = mysqli_fetch_array(mysqli_query($kon,"select * from semester where id_semester='$id_semester' limit 1")); 
    $semester=$data['semester'];
?>
<form action="dist/jadwal/tambah.php" method="post">

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Semester:</label>
                <input type="text" name="semester" class="form-control"  value="<?php echo $semester;?>" disabled>
                <input type="hidden" name="id_semester" class="form-control"  value="<?php echo $id_semester?>" >
            </div>
        </div>
        <div class="col-sm-6">
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
    </div>

    <div class="row">
        <div class="col-sm-6">
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
                            case 5 : $nama_hari="Jum'at"; break;
                            case 6 : $nama_hari='Sabtu'; break;
                            case 7 : $nama_hari='Minggu'; break;

                        endswitch;
                    ?>
                    <option  value="<?php echo $daftar_hari[$i];?>"><?php echo $nama_hari;?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jam Mulai:</label>
                        <input type="time" class="form-control" name="jam_mulai" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jam Selesai:</label>
                        <input type="time" class="form-control" name="jam_selesai" required>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Matakuliah:</label>
                <select class="form-control" name="id_matakuliah" required>
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel matakuliah
                    $sql="select * from matakuliah";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)) {
                        ?>
                    <option value="<?php echo $data['id_matakuliah'];?>"><?php echo $data['nama_matakuliah'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
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
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Ruangan:</label>
                <select class="form-control" name="id_ruangan" required>
                    <?php
                    include '../../config/database.php';
                    //Perintah sql untuk menampilkan semua data pada tabel ruangan
                    $sql="select * from ruangan";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)) {
                        ?>
                    <option value="<?php echo $data['id_ruangan'];?>"><?php echo $data['nama_ruangan'];?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button type="submit" name="tambah_jadwal" id="Submit" class="btn btn-primary">Tambah</button>
        </div>
    </div>
</form>

