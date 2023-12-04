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

    //Nama ketua bidang akademik
    $ketua_akademik=$row['ketua_bid_akademik'];

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
    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'TRANSKRIP AKADEMIK',0,1,'C');

    $pdf->SetTitle("Transkrip Akademik");

    $kode_pengguna=$_SESSION["kode_pengguna"];

    //Menampilkan detail data mahasiswa berdasarkan kode mahasiswa
    $sql="select * from pengguna p
    left join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
    inner join program_studi s on s.id_program_studi=m.id_program_studi
    where p.kode_pengguna='$kode_pengguna' limit 1";

    $hasil=mysqli_query($kon,$sql);

    $cek = mysqli_num_rows($hasil);

    if ($cek<=0){
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(0,7,'Data tidak ditemukan',0,1,'C');
        exit;
        $pdf->Output();
    }

    $data = mysqli_fetch_array($hasil); 

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(30,6,'NIM ',0,0);
    $pdf->Cell(31,6,': '.$data['nim'],0,1);
    $pdf->Cell(30,6,'Nama ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_mahasiswa'],0,1);
    $pdf->Cell(30,6,'Program Studi ',0,0);
    $pdf->Cell(31,6,': '.$data['program_studi'],0,1);
  
    //Membuat header tabel
    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(33,6,'Semester',1,0,'C');
    $pdf->Cell(72,6,'Matakuliah',1,0,'C');
    $pdf->Cell(20,6,'Kredit (K)',1,0,'C');
    $pdf->Cell(22,6,'Nilai Huruf',1,0,'C');
    $pdf->Cell(22,6,'Nilai Angka',1,0,'C');
    $pdf->Cell(19,6,'K x N',1,1,'C');

    $pdf->SetFont('Arial','',8);
    $no=0;
    $skor=0;
    $jum_sks=0;
    $jum_skor=0;
    $tot=0;
    $jumlah_total=0;
    $ipk=0;
    $kode_pengguna=$_SESSION["kode_pengguna"];
    //Mengambil data dari database
    $hasil = mysqli_query($kon, "select * from krs k inner join semester s on s.id_semester=k.id_semester inner join jadwal j on k.id_jadwal=j.id_jadwal inner join matakuliah m on m.id_matakuliah=j.id_matakuliah inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa where a.kode_mahasiswa='$kode_pengguna' and nilai!=''");
    $jumlah_matkul = mysqli_num_rows($hasil);
    while ($data = mysqli_fetch_array($hasil)){
        $no++;

        $jum_sks+=$data['sks'];

        //Membuat skor berdasarkan nilai huruf
        switch($data['nilai']){
            case 'A' : $skor=4; break;
            case 'B' : $skor=3; break;
            case 'C' : $skor=2; break;
            case 'D' : $skor=1; break;
            case 'E' : $skor=0; break;
       
        }

        $tot=$data['sks']*$skor;
        $jumlah_total+=$tot;
        $jum_skor+=$skor;
        $ipk=$jum_skor/$jumlah_matkul;

        //Menampilkan data
        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(33,6,$data['semester'],1,0);
        $pdf->Cell(72,6,$data['nama_matakuliah'],1,0);
        $pdf->Cell(20,6,$data['sks'],1,0,'C');
        $pdf->Cell(22,6,$data['nilai'],1,0,'C');
        $pdf->Cell(22,6,$skor,1,0,'C');
        $pdf->Cell(19,6,$tot,1,1,'C');
        
      
    }

    //Menampilkan IPK
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(113,6,'',1,0,'C');
    $pdf->Cell(20,6,$jum_sks,1,0,'C');
    $pdf->Cell(22,6,'',1,0,'C');
    $pdf->Cell(22,6,'',1,0,'C');
    $pdf->Cell(19,6,$jumlah_total,1,1,'C');
    $pdf->Cell(0,6,'IP Kumulatif : '.number_format($ipk,2),1,1);

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

    $tanggal=date('Y-m-d');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(340,15,'',0,1,'C');
    $pdf->Cell(340,12,tanggal($tanggal),0,1,'C');
    $pdf->Cell(340,0,'Ketua Bidang Akademik',0,1,'C');
    $pdf->Cell(340,50,$ketua_akademik,0,1,'C');
    $pdf->Output();



?>