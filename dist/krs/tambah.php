<?php
    include '../../config/database.php';

    $id_semester=addslashes(trim($_POST['id_semester']));
    $kode_mahasiswa=addslashes(trim($_POST['kode_mahasiswa']));
    $id_program_studi=addslashes(trim($_POST['id_program_studi']));

    $id_jadwal="";
    //Mengambil data jadwal yang telah telah diambil oleh mahasiswa, agar tidak lagi ditampilkan pada saat mahasiswa kembali ingin memilih matakuliah
    $hasil=mysqli_query($kon,"select * from krs where kode_mahasiswa='$kode_mahasiswa'");
    while ($data = mysqli_fetch_array($hasil)):
        $id=$data["id_jadwal"];
        $id_jadwal .= "'$id'". ",";
    endwhile;

    $id_jadwal = substr($id_jadwal,0,-1);
    $jumlah = mysqli_num_rows($hasil);
    
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Dosen</th>
                <th>Matakuliah</th>
                <th>Status</th>
                <th>SKS</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // include database
            include '../../config/database.php';

            if ($jumlah>0){
                //Menapilkan hanya jadwal yang belum dipilih
                $sql="select * from jadwal j 
                inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
                inner join dosen d on d.id_dosen=j.id_dosen
                inner join ruangan r on r.id_ruangan=j.id_ruangan
                where j.id_jadwal not in ($id_jadwal) and j.id_program_studi=$id_program_studi and j.id_semester=$id_semester
                order by hari, jam_mulai asc";
            }else {
                //Menampilkan seluruh jadwal
                $sql="select * from jadwal j 
                inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
                inner join dosen d on d.id_dosen=j.id_dosen
                inner join ruangan r on r.id_ruangan=j.id_ruangan
                where j.id_program_studi=$id_program_studi and j.id_semester=$id_semester
                order by hari, jam_mulai asc";
            }
           
            $hasil=mysqli_query($kon,$sql);
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
            <td><?php echo $data['nama_dosen']; ?></td>
            <td><?php echo $data['nama_matakuliah']; ?></td>
            <td><?php echo $data['status']; ?></td>
            <td><?php echo $data['sks']; ?></td>
            <td><?php echo $nama_hari; ?></td>
            <td><?php echo date("H:i",strtotime($data["jam_mulai"])); ?> WIB</td>
            <td><?php echo date("H:i",strtotime($data["jam_selesai"])); ?> WIB</td>
            <td>
            <a href="dist/krs/pilih.php?kode_mahasiswa=<?php echo $kode_mahasiswa; ?>&id_jadwal=<?php echo $data['id_jadwal']; ?>&id_semester=<?php echo $id_semester; ?>" class="btn-pilih-krs btn btn-default btn-circle" ><i class="fa fa-mouse-pointer"></i> Pilih</a>
            </td>
        </tr>
        <!-- bagian akhir (penutup) while -->
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

