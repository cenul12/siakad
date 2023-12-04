<?php
    //Memulai session
    session_start();
    include '../../config/database.php';
    //Mengambil kode pengguna dalam variabel session
    $kode_pengguna=$_SESSION['kode_pengguna'];

    //Jika ada kirim form dengan method post dengan nama semester
    if ($_POST['semester']!=''){
        $id_semester=$_POST['semester'];
    }else {
        $id_semester=0;
    }
    
    //Perintah sql
    $sql="select * from jadwal j
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
    inner join dosen d on d.id_dosen=j.id_dosen
    inner join ruangan r on r.id_ruangan=j.id_ruangan
    inner join program_studi p on p.id_program_studi=j.id_program_studi
    where j.id_semester=$id_semester and d.kode_dosen='$kode_pengguna'
    order by hari, jam_mulai asc";

    $hasil=mysqli_query($kon,$sql);
    $jumlah_matkul = mysqli_num_rows($hasil);
    $no=0;
    $nama_hari="";
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;
    switch ($data['hari']):
        case 1 : $nama_hari='Senin'; break;
        case 2 : $nama_hari='Selasa'; break;
        case 3 : $nama_hari='Rabu'; break;
        case 4 : $nama_hari='Kamis'; break;
        case 5 : $nama_hari="Jum'at"; break;
        case 6 : $nama_hari='Sabtu'; break;
        case 7 : $nama_hari='Minggu'; break;
    endswitch;
?>
<tr>
    <td><?php echo $no; ?></td>
    <td><?php echo $data['nama_matakuliah']; ?></td>
    <td><?php echo $data['program_studi']; ?></td>
    <td><?php echo $data['nama_ruangan']; ?></td>
    <td><?php echo $nama_hari; ?></td>
    <td><?php echo date('H:i', strtotime($data["jam_mulai"])); ?> WIB</td>
    <td><?php echo date('H:i', strtotime($data["jam_selesai"])); ?> WIB</td>
</tr>
<!-- bagian akhir (penutup) while -->
<?php 
    endwhile;
?>
