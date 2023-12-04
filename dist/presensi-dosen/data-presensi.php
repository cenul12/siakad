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
    inner join dosen d on d.id_dosen=j.id_dosen 
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
    inner join program_studi p on p.id_program_studi=j.id_program_studi 
    where k.id_semester='$id_semester' and d.kode_dosen='$kode_pengguna'
    group by p.id_program_studi,m.id_matakuliah";

    $hasil=mysqli_query($kon,$sql);
    $no=0;
    $jum=0;
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;
    $jum++;
?>
<tr>
    <td><?php echo $no; ?></td>
    <td><?php echo $data['program_studi']; ?></td>
    <td><?php echo $data['nama_matakuliah']; ?></td>
    <td><?php echo $data['sks']; ?></td>
    <td><?php echo $data['status']; ?></td>
    <td><button id_jadwal="<?php echo $data['id_jadwal'];?>" class="tombol_input_presensi btn btn-success btn-circle" ><i class="fa fa-mouse-pointer"></i> Input Presensi</button></td>
</tr>
<!-- bagian akhir (penutup) while -->
<?php 
    endwhile; 
?>

<script>
    // Event saat pengguna mengklik tombol input presensi
    $('.tombol_input_presensi').on('click',function(){
        var id_jadwal = $(this).attr("id_jadwal");
        $.ajax({
            url: 'dist/presensi-dosen/input.php',
            method: 'post',
            data: {id_jadwal:id_jadwal},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Input Presensi';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>