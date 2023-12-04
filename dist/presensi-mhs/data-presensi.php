<?php
    session_start();
    include '../../config/database.php';
    $kode_pengguna=$_SESSION['kode_pengguna'];

    if ($_POST['semester']!=''){
        $id_semester=$_POST['semester'];
    }else {
        $id_semester=0;
    }
    
    
    $sql="select * from krs k
    inner join jadwal j on k.id_jadwal=j.id_jadwal
    inner join presensi p on p.id_krs=k.id_krs 
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
    inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa
    where k.id_semester=$id_semester and a.kode_mahasiswa='$kode_pengguna'";
    
    $hasil=mysqli_query($kon,$sql);

    $no=0;
    $jum_pertemuan=0;
    $hadir=0;
    $absen=0;
    $izin=0;
    $persentase=0;
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;

    if ($data['per1']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per2']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per3']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per4']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per5']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per6']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per7']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per8']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per9']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per10']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per11']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per12']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per13']!='0'){
        $jum_pertemuan++; 
    }
    if ($data['per14']!='0'){
        $jum_pertemuan++; 
    }
     
    
    if ($data['per1']=='1'){
        $hadir++; 
    }
    if ($data['per2']=='1'){
        $hadir++; 
    }
    if ($data['per3']=='1'){
        $hadir++; 
    }
    if ($data['per4']=='1'){
        $hadir++; 
    }
    if ($data['per5']=='1'){
        $hadir++; 
    }
    if ($data['per6']=='1'){
        $hadir++; 
    }
    if ($data['per7']=='1'){
        $hadir++; 
    }
    if ($data['per8']=='1'){
        $hadir++; 
    }
    if ($data['per9']=='1'){
        $hadir++; 
    }
    if ($data['per10']=='1'){
        $hadir++; 
    }
    if ($data['per11']=='1'){
        $hadir++; 
    }
    if ($data['per12']=='1'){
        $hadir++; 
    }
    if ($data['per13']=='1'){
        $hadir++; 
    }
    if ($data['per14']=='1'){
        $hadir++; 
    }
     


    if ($data['per1']=='2'){
        $absen++; 
    }
    if ($data['per2']=='2'){
        $absen++; 
    }
    if ($data['per3']=='2'){
        $absen++; 
    }
    if ($data['per4']=='2'){
        $absen++; 
    }
    if ($data['per5']=='2'){
        $absen++; 
    }
    if ($data['per6']=='2'){
        $absen++; 
    }
    if ($data['per7']=='2'){
        $absen++; 
    }
    if ($data['per8']=='2'){
        $absen++; 
    }
    if ($data['per9']=='2'){
        $absen++; 
    }
    if ($data['per10']=='2'){
        $absen++; 
    }
    if ($data['per11']=='2'){
        $absen++; 
    }
    if ($data['per12']=='2'){
        $absen++; 
    }
    if ($data['per13']=='2'){
        $absen++; 
    }
    if ($data['per14']=='2'){
        $absen++; 
    }
     
    
    if ($data['per1']=='3'){
        $izin++; 
    }
    if ($data['per2']=='3'){
        $izin++; 
    }
    if ($data['per3']=='3'){
        $izin++; 
    }
    if ($data['per4']=='3'){
        $izin++; 
    }
    if ($data['per5']=='3'){
        $izin++; 
    }
    if ($data['per6']=='3'){
        $izin++; 
    }
    if ($data['per7']=='3'){
        $izin++; 
    }
    if ($data['per8']=='3'){
        $izin++; 
    }
    if ($data['per9']=='3'){
        $izin++; 
    }
    if ($data['per10']=='3'){
        $izin++; 
    }
    if ($data['per11']=='3'){
        $izin++; 
    }
    if ($data['per12']=='3'){
        $izin++; 
    }
    if ($data['per13']=='3'){
        $izin++; 
    }
    if ($data['per14']=='3'){
        $izin++; 
    }
?>
<tr>
    <td><?php echo $no; ?></td>
    <td><?php echo $data['nama_matakuliah']; ?></td>
    <td> <?php echo $jum_pertemuan; ?> </td>
    <td> <?php echo $hadir; ?> </td>
    <td> <?php echo $absen; ?> </td>
    <td> <?php echo $izin; ?> </td>
    <td> 
    <?php

    if ($jum_pertemuan!=0){
        $persentase=((($hadir+$izin)/$jum_pertemuan)*100);
    }else {
        $persentase=0;
    }
    echo number_format($persentase,2); 
    ?> %
    </td>
</tr>
<?php
    $jum_pertemuan=0;
    $hadir=0;
    $absen=0;
    $izin=0;
    endwhile;
?>
