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
    inner join mahasiswa m on m.kode_mahasiswa=p.kode_pengguna
    inner join dosen d on d.id_dosen=m.dosen_pembimbing
    inner join program_studi t on t.id_program_studi=m.id_program_studi
    inner join krs k on k.kode_mahasiswa=m.kode_mahasiswa
    inner join semester s on s.id_semester=k.id_semester
    where p.kode_pengguna='$kode_pengguna' and s.id_semester='$id_semester' limit 1";

    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 
    $program_studi=$data['program_studi'];
    $dosen_pembimbing=$data['nama_dosen'];
    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'REKAP PRESENSI',0,1,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,7,'Semester : '.$data['semester'],0,1,'C');

    $pdf->SetTitle("Rekap Presensi Semester ".$data['semester']."");

   
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
    $pdf->Cell(18,6,'Kode',1,0,'C');
    $pdf->Cell(82,6,'Matakuliah',1,0,'C');
    $pdf->Cell(28,6,'Jumlah Pertemuan',1,0,'C');
    $pdf->Cell(14,6,'Hadir',1,0,'C');
    $pdf->Cell(14,6,'Alpa',1,0,'C');
    $pdf->Cell(14,6,'Izin',1,0,'C');
    $pdf->Cell(20,6,'Hadir (%)',1,1,'C');

    $pdf->SetFont('Arial','',8);
    $no=0;
    $jum_pertemuan=0;
    $hadir=0;
    $absen=0;
    $izin=0;
    $persentase=0;

    $kode_pengguna=$_SESSION["kode_pengguna"];
    $id_semester=addslashes(trim($_GET['id_semester']));
 
    
    $sql="select * from krs k
    inner join jadwal j on k.id_jadwal=j.id_jadwal
    inner join presensi p on p.id_krs=k.id_krs 
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah
    inner join mahasiswa a on a.kode_mahasiswa=k.kode_mahasiswa
    where k.id_semester='$id_semester' and a.kode_mahasiswa='$kode_pengguna'";
    
    $hasil=mysqli_query($kon,$sql);

    $jumlah_matkul = mysqli_num_rows($hasil);
    while ($data = mysqli_fetch_array($hasil)){
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

        //Menampilkan data
        $pdf->Cell(8,6,$no,1,0,'C');
        $pdf->Cell(18,6,$data['kode_matakuliah'],1,0,'C');
        $pdf->Cell(82,6,$data['nama_matakuliah'],1,0);
        $pdf->Cell(28,6,$jum_pertemuan,1,0,'C');
        $pdf->Cell(14,6,$hadir,1,0,'C');
        $pdf->Cell(14,6,$absen,1,0,'C');
        $pdf->Cell(14,6, $izin,1,0,'C');

        if ($jum_pertemuan!=0){
            $persentase=((($hadir+$izin)/$jum_pertemuan)*100);
        }else {
            $persentase=0;
        }

        $pdf->Cell(20,6,number_format($persentase,2).' %',1,1,'C');

        $jum_pertemuan=0;
        $hadir=0;
        $absen=0;
        $izin=0;
      
    }

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
    $pdf->Cell(340,0,'Dosen Pembimbing',0,1,'C');

    $pdf->Cell(340,50,$dosen_pembimbing,0,1,'C');

    $pdf->Output();

?>