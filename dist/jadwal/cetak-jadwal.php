
<?php
    //Mengambil plugin fpdf
    require('../../src/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm','Letter');

    //Mengambil profil aplikasi
    include '../../config/database.php';
    $query = mysqli_query($kon, "select * from profil_aplikasi limit 1");    
    $row = mysqli_fetch_array($query);
    $ketua_akademik=$row['ketua_bid_akademik'];


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


    
    $sql="select * from jadwal j inner join semester s on s.id_semester=j.id_semester";

    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 

    
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'JADWAL AKADEMIK',0,1,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(0,7,'Semester : '.$data['semester'],0,1,'C');

    $pdf->SetTitle("Jadwal Akademik Semester ".$data['semester']."");

    if (isset($_GET['id_program_studi'])){
        $id_program_studi=addslashes(trim($_GET['id_program_studi']));
        $program_studi=mysqli_query($kon,"select program_studi from program_studi where id_program_studi='$id_program_studi'");
        $get = mysqli_fetch_array($program_studi); 
        $pdf->Cell(0,2,'Program Studi : '.$get['program_studi'],0,1,'C');
    }else if (isset($_GET['id_dosen'])){
        $id_dosen=addslashes(trim($_GET['id_dosen']));
        $dosen=mysqli_query($kon,"select nama_dosen from dosen where id_dosen='$id_dosen'");
        $get = mysqli_fetch_array($dosen); 
        $pdf->Cell(0,2,'Dosen : '.$get['nama_dosen'],0,1,'C');
  
    }else if (isset($_GET['hari'])){
        $hari=addslashes(trim($_GET['hari']));
        $nama_hari="";
        $hari=mysqli_query($kon,"select hari from jadwal where hari='$hari'");
        $get = mysqli_fetch_array($hari);
        $jum=mysqli_num_rows($hari);

  

        if ($jum!=0){
            switch ($hari):
                case 1 : $nama_hari='Senin'; break;
                case 2 : $nama_hari='Selasa'; break;
                case 3 : $nama_hari='Rabu'; break;
                case 4 : $nama_hari='Kamis'; break;
                case 5 : $nama_hari='Jumaat'; break;
                case 6 : $nama_hari='Sabtu'; break;
                case 7 : $nama_hari='Minggu'; break;
            endswitch;
            $pdf->Cell(0,2,'Hari : '.$nama_hari,0,1,'C');
        }
    }


   

    $pdf->Cell(0,5,'',0,1,'C');

    //Membuat header tabel
    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(60,6,'Matakuliah',1,0,'C');
    $pdf->Cell(52,6,'Program Studi',1,0,'C');
    $pdf->Cell(20,6,'Ruangan',1,0,'C');
    $pdf->Cell(18,6,'Hari',1,0,'C');
    $pdf->Cell(19,6,'Mulai',1,0,'C');
    $pdf->Cell(19,6,'Selesai',1,1,'C');

    $pdf->SetFont('Arial','',8);
    $no=0;
    $kondisi="";
    $id_semester=addslashes(trim($_GET['id_semester']));

    if (isset($_GET['id_program_studi'])){
        $id_program_studi=addslashes(trim($_GET['id_program_studi']));
        $kondisi="and p.id_program_studi=$id_program_studi";
    }else if (isset($_GET['id_dosen'])){
        $id_dosen=addslashes(trim($_GET['id_dosen']));
        $kondisi="and d.id_dosen=$id_dosen";
    }else if (isset($_GET['hari'])){
        $hari=addslashes(trim($_GET['hari']));
        $kondisi="and j.hari=$hari";
    }else {
        $kondisi="";
    }

    $sql="select * from jadwal j
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
    inner join dosen d on d.id_dosen=j.id_dosen
    inner join ruangan r on r.id_ruangan=j.id_ruangan
    inner join program_studi p on p.id_program_studi=j.id_program_studi
    where j.id_semester=$id_semester $kondisi
    order by hari, jam_mulai asc";

    $nama_hari="";

    $hasil = mysqli_query($kon,$sql);
    $jumlah_matkul = mysqli_num_rows($hasil);
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

        $pdf->Cell(8,6,$no,1,0,'C');
        $pdf->Cell(60,6,substr($data['nama_matakuliah'],0,33),1,0);
        $pdf->Cell(52,6,$data['program_studi'],1,0);
        $pdf->Cell(20,6,$data['nama_ruangan'],1,0,'C');
        $pdf->Cell(18,6,$nama_hari,1,0,'C');
        $pdf->Cell(19,6, date('H:i', strtotime($data["jam_mulai"]))." WIB",1,0,'C');
        $pdf->Cell(19,6,date('H:i', strtotime($data["jam_selesai"]))." WIB",1,1,'C');
      
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
    $pdf->Cell(340,0,'Ketua Bidang Akademik',0,1,'C');

    $pdf->Cell(340,50,$ketua_akademik,0,1,'C');
  
    $pdf->Output();



?>