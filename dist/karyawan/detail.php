<?php 
    include '../../config/database.php';
    $id_karyawan=$_POST["id_karyawan"];
    $sql="select * from karyawan where id_karyawan=$id_karyawan limit 1";
    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>
<table class="table">
    <tbody>
        <tr>
            <td>NIP</td>
            <td width="75%">: <?php echo $data['nip'];?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td width="75%">: <?php echo $data['nama_karyawan'];?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td width="75%">: <?php echo $data['jk'] == 1 ? 'Laki-laki' : 'Perempuan';?></td>
        </tr>
        <tr>
            <td>email</td>
            <td width="75%">: <?php echo $data['email'];?></td>
        </tr>
        <tr>
            <td>No Telp</td>
            <td width="75%">: <?php echo $data['no_telp'];?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td width="75%">: <?php echo $data['alamat'];?></td>
        </tr>
    </tbody>
</table>