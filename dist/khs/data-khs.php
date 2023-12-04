<?php
    session_start();
    include '../../config/database.php';
    $kode_pengguna=$_SESSION['kode_pengguna'];

    if ($_POST['semester']!=''){
        $id_semester=$_POST['semester'];
    }else {
        $id_semester=0;
    }
    
    $skor=0;
    $jum_skor=0;
    $jum_sks=0;
    $sql="select * from krs k
    inner join jadwal j on k.id_jadwal=j.id_jadwal 
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
    inner join dosen d on d.id_dosen=j.id_dosen
    inner join ruangan r on r.id_ruangan=j.id_ruangan
    inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa
    where k.id_semester=$id_semester and a.kode_mahasiswa='$kode_pengguna'";
    $hasil=mysqli_query($kon,$sql);
    $jumlah_matkul = mysqli_num_rows($hasil);
    $no=0;

    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;
    switch($data['nilai']){
        case 'A' : $skor=4; break;
        case 'B' : $skor=3; break;
        case 'C' : $skor=2; break;
        case 'D' : $skor=1; break;
        case 'E' : $skor=0; break;
      
    }
    $jum_skor+=$skor;
    $jum_sks+=$data['sks'];
?>
<tr>
    <td><?php echo $no; ?>
       
    </td>
    <td><?php echo $data['nama_matakuliah']; ?></td>
    <td><?php echo $data['status']; ?></td>
    <td><?php echo $data['sks']; ?></td>
    <td><?php echo $data['nilai']; ?></td>
</tr>
<!-- bagian akhir (penutup) while -->
<?php 
    endwhile;

    if ($jumlah_matkul!=0){
        $ips=$jum_skor/$jumlah_matkul;
    }else {
        $ips=0;
    }
?>
<input type="hidden" value="<?php echo $jum_sks;?>" id="get_jumlah_sks"/>
<input type="hidden" value="<?php echo $jumlah_matkul; ?>" id="get_jumlah_matkul"/>
<input type="hidden" value="<?php echo number_format($ips,2);?>" id="get_ips"/>