
<?php
    // include database
    include '../../config/database.php';

    $id_jadwal=$_POST['id_jadwal'];
    $pertemuan=$_POST['pertemuan'];

    $sql="select * from krs k
    inner join jadwal j on j.id_jadwal=k.id_jadwal
    inner join presensi p on p.id_krs=k.id_krs
    left join mahasiswa m on m.kode_mahasiswa=k.kode_mahasiswa
    where k.id_jadwal=$id_jadwal";

    $hasil=mysqli_query($kon,$sql);
    $no=0;
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;
?>
<tr>
    <td><?php echo $no; ?></td>
    <td><?php echo $data['nim']; ?></td>
    <td><?php echo $data['nama_mahasiswa']; ?></td>
    <td>
        <div class="form-group">
            <input type="hidden" name="id_krs[]" value="<?php echo $data['id_krs'];?>" />
            <select class="form-control" name="presensi[]" required>
                <option value="">Pilih</option>
                <?php
                $ket="";
                $field="";
                $pilih="";
                $presensi = array("1", "2","3");
                $jum=count($presensi)-1;
                for ($i=0;$i<=$jum;$i++){

                    //Menampilkan keterangan
                    switch($presensi[$i]){
                        case 1 : $ket="Hadir";break;
                        case 2 : $ket="Alpa";break;
                        case 3 : $ket="Izin";break;
                    }

                    //Mengambil setiap data pertemuan dari 1 sampai 14
                    switch ($pertemuan){
                        case '1' : $field=$data['per1']; break;
                        case '2' : $field=$data['per2']; break;
                        case '3' : $field=$data['per3']; break;
                        case '4' : $field=$data['per4']; break;
                        case '5' : $field=$data['per5']; break;
                        case '6' : $field=$data['per6']; break;
                        case '7' : $field=$data['per7']; break;
                        case '8' : $field=$data['per8']; break;
                        case '9' : $field=$data['per9']; break;
                        case '10' : $field=$data['per10']; break;
                        case '11' : $field=$data['per11']; break;
                        case '12' : $field=$data['per12']; break;
                        case '13' : $field=$data['per13']; break;
                        case '14' : $field=$data['per14']; break;
                    }
            
                ?>
                <option <?php if ($presensi[$i]==$field) echo "selected"; ?>  value="<?php echo $presensi[$i];?>"><?php echo $ket;?></option>
            <?php } ?>
            </select>
        </div>
    </td>
</tr>
<!-- bagian akhir (penutup) while -->
<?php endwhile; ?>