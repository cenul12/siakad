
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
    $pdf->Image('../../dist/aplikasi/logo/'.$row['logo'],15,5,20,20);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_kampus']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');

    $pdf->SetLineWidth(1);
    $pdf->Line(10,31,206,31);
    $pdf->SetLineWidth(0);
    $pdf->Line(10,32,206,32);

    $kode_pengguna=$_SESSION["kode_pengguna"];
    $id_semester=addslashes(trim($_GET['id_semester']));
    $sql="select * from pengguna p
    left join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
    inner join program_studi t on t.id_program_studi=m.id_program_studi
    inner join krs k on k.kode_mahasiswa=m.kode_mahasiswa
    inner join semester s on s.id_semester=k.id_semester
    where m.kode_mahasiswa='$kode_pengguna' and s.id_semester='$id_semester' limit 1";

    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
    $program_studi=$data['program_studi'];
    $ketua=$data['ketua'];
    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'KARTU HASIL STUDI',0,1,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,7,'Semester : '.$data['semester'],0,1,'C');

    $pdf->SetTitle("Kartu Hasil Studi Semester ".$data['semester']."");

   
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
    $pdf->Cell(23,6,'Kode',1,0,'C');
    $pdf->Cell(82,6,'Matakuliah',1,0,'C');
    $pdf->Cell(20,6,'Kredit (K)',1,0,'C');
    $pdf->Cell(22,6,'Nilai',1,0,'C');
    $pdf->Cell(22,6,'Bobot (B)',1,0,'C');
    $pdf->Cell(19,6,'K x B',1,1,'C');

    $pdf->SetFont('Arial','',8);
    $no=0;
    $skor=0;
    $jum_sks=0;
    $jum_skor=0;
    $tot=0;
    $jumlah_total=0;
    $ips=0;
    $jumlah_matkul=0;
    $kode_pengguna=$_SESSION["kode_pengguna"];
    $id_semester=addslashes(trim($_GET['id_semester']));
    //Mengambil data dari database
    $hasil = mysqli_query($kon, "select * from krs k inner join semester s on s.id_semester=k.id_semester inner join jadwal j on k.id_jadwal=j.id_jadwal inner join matakuliah m on m.id_matakuliah=j.id_matakuliah inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa where a.kode_mahasiswa='$kode_pengguna' and s.id_semester='$id_semester'");
    //$jumlah_matkul = mysqli_num_rows($hasil);
    while ($data = mysqli_fetch_array($hasil)){
        $no++;
    
        if ($data['nilai']!=''){
            $jumlah_matkul++;

            switch($data['nilai']){
                case 'A' : $skor=4; break;
                case 'B' : $skor=3; break;
                case 'C' : $skor=2; break;
                case 'D' : $skor=1; break;
                case 'E' : $skor=0; break;
    
            }
    
            $jum_skor+=$skor;
            $jum_sks+=$data['sks'];
            $tot=$data['sks']*$skor;
            $jumlah_total+=$tot;
        }
      
        if ($jumlah_matkul!=0){
            $ips=$jum_skor/$jumlah_matkul;
        }else {
            $ips=0;
        }

        //Menampilkan data
        $pdf->Cell(8,6,$no,1,0,'C');
        $pdf->Cell(23,6,$data['kode_matakuliah'],1,0,'C');
        $pdf->Cell(82,6,$data['nama_matakuliah'],1,0);
 
        
        if ($data['nilai']!=''){
            $pdf->Cell(20,6,$data['sks'],1,0,'C');
            $pdf->Cell(22,6,$data['nilai'],1,0,'C');
            $pdf->Cell(22,6, $skor,1,0,'C');
            $pdf->Cell(19,6,$tot,1,1,'C');
        }else{
            $pdf->Cell(20,6,'',1,0,'C');
            $pdf->Cell(22,6,'',1,0,'C');
            $pdf->Cell(22,6,'',1,0,'C');
            $pdf->Cell(19,6,'',1,1,'C');
        }
     
    }


    //Menampilkan total
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(113,6,'Total',1,0,'C');
    $pdf->Cell(20,6,$jum_sks,1,0,'C');
    $pdf->Cell(22,6,'',1,0,'C');
    $pdf->Cell(22,6,'',1,0,'C');
    $pdf->Cell(19,6,$jumlah_total,1,1,'C');
    $pdf->Cell(0,6,'',0,1);
    $pdf->Cell(0,6,'IP Semester (IPS) : '.number_format($ips,2),0,1);



    $jum_skor=0;
    $ipk=0;
    $hasil = mysqli_query($kon, "select * from krs k inner join semester s on s.id_semester=k.id_semester inner join jadwal j on k.id_jadwal=j.id_jadwal inner join matakuliah m on m.id_matakuliah=j.id_matakuliah inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa where a.kode_mahasiswa='$kode_pengguna' and nilai!='' ");
    $jumlah_matkul = mysqli_num_rows($hasil);
    while ($data = mysqli_fetch_array($hasil)){
        $no++;
        $jum_sks+=$data['sks'];

        switch($data['nilai']){
            case 'A' : $skor=4; break;
            case 'B' : $skor=3; break;
            case 'C' : $skor=2; break;
            case 'D' : $skor=1; break;
            case 'E' : $skor=0; break;
        }
        $jum_skor+=$skor;
        $ipk=$jum_skor/$jumlah_matkul;
    }

    $pdf->Cell(0,6,'IP Kumulatif (IPK) : '.number_format($ipk,2),0,1);


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
    $pdf->Cell(340,0,'Ketua Program Studi',0,1,'C');
    $pdf->Cell(340,7,'',0,1,'C');
    $pdf->Cell(340,0,$program_studi,0,1,'C');

    $pdf->Cell(340,50,$ketua,0,1,'C');
    $pdf->Output();



?>