<?php
    session_start();

    if (!$_SESSION["kode_pengguna"]){
        header("Location:../../login.php");
    }
    //Mengambil plugin fpdf
    require('../../src/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm','Letter');

    //Mengambil profil aplikasi
    include '../../config/database.php';
    $query = mysqli_query($kon, "select * from profil_aplikasi limit 1");    
    $row = mysqli_fetch_array($query);


    //Membuat halaman pdf
    $pdf->AddPage();
    //Membuat header
    $pdf->Image('../../dist/aplikasi/logo/'.$row['logo'],15,5,20,20);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_kampus']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');

    //Membuat line (garis)
    $pdf->SetLineWidth(1);
    $pdf->Line(10,31,206,31);
    $pdf->SetLineWidth(0);
    $pdf->Line(10,32,206,32);

    $kode_mahasiswa=$_SESSION["kode_pengguna"];
    $id_semester=addslashes(trim($_GET['id_semester']));

    $sql="select * from pengguna p
    left join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
    left join dosen d on d.id_dosen=m.dosen_pembimbing
    inner join program_studi t on t.id_program_studi=m.id_program_studi
    inner join krs k on k.kode_mahasiswa=m.kode_mahasiswa
    inner join semester s on s.id_semester=k.id_semester
    where m.kode_mahasiswa='$kode_mahasiswa' and s.id_semester='$id_semester' limit 1";

    //Eksekusi query
    $hasil=mysqli_query($kon,$sql);
    //Menghitung jumlah row
    $jumlah = mysqli_num_rows($hasil);
    $data = mysqli_fetch_array($hasil); 

    //Jika query tidak menghasilkan row, lakukan exit
    if ($jumlah==0){
        exit;
    }
    
    //Membuat konten
    $pdf->SetFont('Arial','B',13);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'Kartu Rencana Studi',0,1,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,7,'Semester : '.$data['semester'],0,1,'C');

    $pdf->SetTitle("Kartu Rencana Studi Semester ".$data['semester']."");

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(35,6,'NIM ',0,0);
    $pdf->Cell(31,6,': '.$data['nim'],0,1);
    $pdf->Cell(35,6,'Nama ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_mahasiswa'],0,1);
    $pdf->Cell(35,6,'Program Studi ',0,0);
    $pdf->Cell(31,6,': '.$data['program_studi'],0,1);
    $pdf->Cell(35,6,'Dosen Pembimbing ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_dosen'],0,1);
  
    $nama_mahasiswa=$data['nama_mahasiswa'];
    $program_studi=$data['program_studi'];
    $ketua=$data['ketua'];

    //Membuat header tabel
    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(62,6,'Dosen',1,0,'C');
    $pdf->Cell(62,6,'Matakuliah',1,0,'C');
    $pdf->Cell(13,6,'Status',1,0,'C');
    $pdf->Cell(10,6,'SKS',1,0,'C');
    $pdf->Cell(14,6,'Hari',1,0,'C');
    $pdf->Cell(28,6,'Jam',1,1,'C');

    $pdf->SetFont('Arial','',8);

    $no=0;
    $nama_hari="";

    $sql="select * from krs k
    inner join jadwal j on k.id_jadwal=j.id_jadwal 
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
    inner join dosen d on d.id_dosen=j.id_dosen
    inner join ruangan r on r.id_ruangan=j.id_ruangan
    inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa
    where k.id_semester='$id_semester' and a.kode_mahasiswa='$kode_mahasiswa'
    order by hari, jam_mulai asc";

    $hasil = mysqli_query($kon,$sql);
    while ($data = mysqli_fetch_array($hasil)){
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
      
        //Menampilkan data
        $pdf->Cell(8,6,$no,1,0,'C');
        $pdf->Cell(62,6,substr($data['nama_dosen'],0,38),1,0);
        $pdf->Cell(62,6,substr($data['nama_matakuliah'],0,38),1,0);
        $pdf->Cell(13,6,$data['status'],1,0,'C');
        $pdf->Cell(10,6,$data['sks'],1,0,'C');
        $pdf->Cell(14,6,$nama_hari,1,0,'C');
        $pdf->Cell(28,6,date('H:i', strtotime($data["jam_mulai"])).' - '.date('H:i', strtotime($data["jam_selesai"])).' WIB',1,1,'C');
    }

    //Membuat format peulisan tanggal
    function tanggal($tanggal)
    {
        $bulan = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }

    //Menampilkan keterangan tambahan
    $tanggal=date('Y-m-d');
    $pdf->SetFont('Arial','',8);


    $pdf->Cell(340,15,'',0,1,'C');
    $pdf->Cell(340,12,tanggal($tanggal),0,1,'C');
    $pdf->Cell(50,0,'Mahasiswa',0,0,'C');
    $pdf->Cell(240,0,'Ketua Program Studi',0,1,'C');
    $pdf->Cell(340,7,'',0,1,'C');
    $pdf->Cell(340,0,$program_studi,0,1,'C');

    $pdf->Cell(50,50,$nama_mahasiswa,0,0,'C');
    $pdf->Cell(240,50,$ketua,0,1,'C');


    $pdf->Output();

?>

