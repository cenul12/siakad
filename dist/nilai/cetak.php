
<?php
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

    //Membuat garis (line)
    $pdf->SetLineWidth(1);
    $pdf->Line(10,31,206,31);
    $pdf->SetLineWidth(0);
    $pdf->Line(10,32,206,32);

 
    $id_jadwal=addslashes(trim($_GET['id_jadwal']));
    //Mendampilkan data dari tabel jadwal dan direlasikan dengan beberapa tabel lainnya
    $sql="select * from jadwal j
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
    inner join program_studi p on p.id_program_studi=j.id_program_studi 
    inner join semester s on s.id_semester=j.id_semester 
    inner join dosen d on d.id_dosen=j.id_dosen
    where j.id_jadwal='$id_jadwal'";

    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
    $dosen_pengammpuh=$data['nama_dosen'];
    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'DAFTAR NILAI',0,1,'C');

    $pdf->SetTitle("Daftar Nilai ".$data['nama_matakuliah']." - ".$data['program_studi']." Semester ".$data['semester']."");

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(30,6,'Kode ',0,0);
    $pdf->Cell(31,6,': '.$data['kode_matakuliah'],0,1);
    $pdf->Cell(30,6,'Matakuliah ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_matakuliah'],0,1);
    $pdf->Cell(30,6,'SKS ',0,0);
    $pdf->Cell(31,6,': '.$data['sks'],0,1);
    $pdf->Cell(30,6,'Status ',0,0);
    $pdf->Cell(31,6,': '.$data['status'],0,1);
    $pdf->Cell(30,6,'Program Studi ',0,0);
    $pdf->Cell(31,6,': '.$data['program_studi'],0,1);
    $pdf->Cell(30,6,'Semester ',0,0);
    $pdf->Cell(31,6,': '.$data['semester'],0,1);

    //Membuat header tabel
    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(30,6,'NIM',1,0,'C');
    $pdf->Cell(118,6,'Nama',1,0,'C');
    $pdf->Cell(20,6,'Nilai',1,0,'C');
    $pdf->Cell(20,6,'Skor',1,1,'C');

    $pdf->SetFont('Arial','',8);
    $no=0;
    $skor=0;
    //Menampilkan data dari tabel KRS
    $sql="select * from krs k
    inner join jadwal j on j.id_jadwal=k.id_jadwal
    left join mahasiswa m on m.kode_mahasiswa=k.kode_mahasiswa
    where k.id_jadwal='$id_jadwal'";

    $hasil = mysqli_query($kon,$sql);
    while ($data = mysqli_fetch_array($hasil)){
        $no++;
        //Membuat skor berdasarkan nilai
        switch($data['nilai']){
            case 'A' : $skor=4; break;
            case 'B' : $skor=3; break;
            case 'C' : $skor=2; break;
            case 'D' : $skor=1; break;
            case 'E' : $skor=0; break;
        }
        $pdf->Cell(8,6,$no,1,0,'C');
        $pdf->Cell(30,6,$data['nim'],1,0);
        $pdf->Cell(118,6,$data['nama_mahasiswa'],1,0);
        $pdf->Cell(20,6,$data['nilai'],1,0,'C');
        $pdf->Cell(20,6,$skor,1,1,'C');

    }

    //Membuat format tanggal
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

    //Membuat tambahan keterangan
    $tanggal=date('Y-m-d');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(340,15,'',0,1,'C');
    $pdf->Cell(340,12,tanggal($tanggal),0,1,'C');
    $pdf->Cell(340,0,'Dosen Pengampuh',0,1,'C');
    $pdf->Cell(340,50,$dosen_pengammpuh,0,1,'C');
  
    $pdf->Output();



?>