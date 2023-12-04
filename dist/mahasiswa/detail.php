<?php 
    include '../../config/database.php';
    $id_mahasiswa=addslashes(trim($_POST["id_mahasiswa"]));

    //Menampilkan detail data mahasiswa, join dengan beberapa tabel lain
    $sql="select m.*,p.nama as nama_provinsi,k.nama as nama_kab, t.nama as nama_kec, s.*,d.nama_dosen from mahasiswa m
    inner join program_studi s on s.id_program_studi=m.id_program_studi
    inner join dosen d on d.id_dosen=m.dosen_pembimbing
    inner join provinsi p on p.id_prov=m.provinsi
    inner join kabupaten k on k.id_kab=m.kabupaten
    inner join kecamatan t on t.id_kec=m.kecamatan
    where id_mahasiswa=$id_mahasiswa limit 1";

    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
?>
<table class="table">
    <tbody>
        <tr>
            <td>NIM</td>
            <td width="75%">: <?php echo $data['nim'];?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td width="75%">: <?php echo $data['nama_mahasiswa'];?></td>
        </tr>
        <tr>
            <td>Tempat Lahir</td>
            <td width="75%">: <?php echo $data['tempat_lahir'];?></td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td width="75%">: <?php echo date('d/m/Y', strtotime($data["tanggal_lahir"]));?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td width="75%">: <?php echo $data['jk'] == 1 ? 'Laki-laki' : 'Perempuan'; ?></td>
        </tr>
        <tr>
            <td>Kewarganegaraan</td>
            <td width="75%">: <?php echo $data['kewarganegaraan'];?></td>
        </tr>
        <tr>
            <td>Agama</td>
            <td width="75%">: <?php echo $data['agama'];?></td>
        </tr>
        <tr>
            <td>Nama Ibu</td>
            <td width="75%">: <?php echo $data['nama_ibu'];?></td>
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
        <tr>
            <td>Kode POS</td>
            <td width="75%">: <?php echo $data['kode_pos'];?></td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td width="75%">: <?php echo $data['nama_provinsi'];?></td>
        </tr>
        <tr>
            <td>Kabupaten</td>
            <td width="75%">: <?php echo $data['nama_kab'];?></td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td width="75%">: <?php echo $data['nama_kec'];?></td>
        </tr>
        <tr>
            <td>Pendidikan</td>
            <td width="75%">: <?php echo $data['pendidikan'];?></td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td width="75%">: <?php echo $data['sekolah'];?></td>
        </tr>
        <tr>
            <td>Rata-rata nilai raport</td>
            <td width="75%">: <?php echo $data['nilai_raport'];?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td width="75%">: <?php echo $data['program_studi'];?></td>
        </tr>
        <tr>
            <td>Dosen Pembimbing Akademik</td>
            <td width="75%">: <?php echo $data['nama_dosen'];?></td>
        </tr>
    </tbody>
</table>