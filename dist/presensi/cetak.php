<?php
    //Mengambil plugin fpdf
    require('../../src/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('L', 'mm','Letter');

    //Mengambil profil aplikasi
    include '../../config/database.php';
    $query = mysqli_query($kon, "select * from profil_aplikasi limit 1");    
    $row = mysqli_fetch_array($query);
    $ketua_akademik=$row['ketua_bid_akademik'];

    //Membuat halaman pdf
    $pdf->AddPage();

    //Membut header
    $pdf->Image('../../dist/aplikasi/logo/'.$row['logo'],15,5,20,20);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_kampus']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');

    //Membuat line
    $pdf->SetLineWidth(1);
    $pdf->Line(10,31,270,31);
    $pdf->SetLineWidth(0);
    $pdf->Line(10,32,270,32);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,5,'',0,1,'C');
    $pdf->Cell(0,7,'DAFTAR PRESENSI',0,1,'C');

    $pdf->SetTitle("Daftar Presensi");

    $id_jadwal=addslashes(trim($_GET['id_jadwal']));

    $sql="select * from jadwal j 
    inner join matakuliah m on m.id_matakuliah=j.id_matakuliah 
    inner join dosen d on d.id_dosen=j.id_dosen
    inner join ruangan r on r.id_ruangan=j.id_ruangan
    inner join program_studi p on p.id_program_studi=j.id_program_studi
    where j.id_jadwal='$id_jadwal'";

    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil);
    $nama_hari='';
    //Menampilkan nama hari
    switch ($data['hari']):
        case 1 : $nama_hari='Senin'; break;
        case 2 : $nama_hari='Selasa'; break;
        case 3 : $nama_hari='Rabu'; break;
        case 4 : $nama_hari='Kamis'; break;
        case 5 : $nama_hari='Jumaat'; break;
        case 6 : $nama_hari='Sabtu'; break;
        case 7 : $nama_hari='Minggu'; break;

    endswitch;

    $hari=$nama_hari.", ". date("H:i",strtotime($data["jam_mulai"]))." - ".date("H:i",strtotime($data["jam_selesai"]));
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,6,'Program Studi ',0,0);
    $pdf->Cell(31,6,': '.$data['program_studi'],0,1);
    $pdf->Cell(30,6,'Matakuliah ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_matakuliah'],0,1);
    $pdf->Cell(30,6,'Dosen ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_dosen'],0,1);
    $pdf->Cell(30,6,'Ruangan ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_ruangan'],0,1);
    $pdf->Cell(30,6,'Hari/Jam ',0,0);
    $pdf->Cell(31,6,': '.$hari." WIB",0,1);

    //Membuat header tabel
    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(17,6,'NIM',1,0,'C');
    $pdf->Cell(53,6,'Nama',1,0,'C');
    $pdf->Cell(13,6,'1',1,0,'C');
    $pdf->Cell(13,6,'2',1,0,'C');
    $pdf->Cell(13,6,'3',1,0,'C');
    $pdf->Cell(13,6,'4',1,0,'C');
    $pdf->Cell(13,6,'5',1,0,'C');
    $pdf->Cell(13,6,'6',1,0,'C');
    $pdf->Cell(13,6,'7',1,0,'C');
    $pdf->Cell(13,6,'8',1,0,'C');
    $pdf->Cell(13,6,'9',1,0,'C');
    $pdf->Cell(13,6,'10',1,0,'C');
    $pdf->Cell(13,6,'11',1,0,'C');
    $pdf->Cell(13,6,'12',1,0,'C');
    $pdf->Cell(13,6,'13',1,0,'C');
    $pdf->Cell(13,6,'14',1,1,'C');

    $pdf->SetFont('Arial','',10);

    //Set nilai awal untuk variabel no dan ket
    $no=0;
    $ket1="";
    $ket2="";
    $ket3="";
    $ket4="";
    $ket5="";
    $ket6="";
    $ket7="";
    $ket8="";
    $ket9="";
    $ket10="";
    $ket11="";
    $ket12="";
    $ket13="";
    $ket14="";

    $id_jadwal=addslashes(trim($_GET['id_jadwal']));

    $sql="select * from presensi p
    inner join krs k on k.id_krs=p.id_krs
    inner join mahasiswa m on m.kode_mahasiswa=k.kode_mahasiswa
    inner join jadwal j on j.id_jadwal=k.id_jadwal
    where j.id_jadwal='$id_jadwal'
    ";

    $hasil = mysqli_query($kon,$sql);
  
    while ($data = mysqli_fetch_array($hasil)){

        $no++;

         // Kondisi untuk  menamilkan keterangan hadir, alpa ata izin
         if ($data['per1']==1){
           $ket1="Hadir";
        } else if ($data['per1']==2){
           $ket1="Alpa"; 
        } else if ($data['per1']==3){
            $ket1="Izin"; 
        }else {
         $ket1="";
        }
        

        if ($data['per2']==1){
           $ket2="Hadir";
        }else if ($data['per2']==2){
           $ket2="Alpa"; 
        } else if ($data['per2']==3){
            $ket2="Izin"; 
        }else {
         $ket2="";
        }
        

        if ($data['per3']==1){
           $ket3="Hadir";
        }else if ($data['per3']==2){
           $ket3="Alpa"; 
        } else if ($data['per3']==3){
            $ket3="Izin"; 
        } else {
         $ket3="";
        }
    
    
        if ($data['per4']==1){
           $ket4="Hadir";
        }else if ($data['per4']==2){
           $ket4="Alpa"; 
        } else if ($data['per4']==3){
            $ket4="Izin"; 
        } else {
         $ket4="";
        }
        

        if ($data['per5']==1){
           $ket5="Hadir";
        }else if ($data['per5']==2){
           $ket5="Alpa"; 
        } else if ($data['per5']==3){
            $ket5="Izin"; 
        } else {
         $ket5="";
        }
        

    
        if ($data['per6']==1){
           $ket6="Hadir";
        }else if ($data['per6']==2){
           $ket6="Alpa"; 
        } else if ($data['per6']==3){
            $ket6="Izin"; 
        } else {
         $ket6="";
        }

    
        if ($data['per7']==1){
           $ket7="Hadir";
        }else if ($data['per7']==2){
           $ket7="Alpa"; 
        } else if ($data['per7']==3){
            $ket7="Izin"; 
        } else {
         $ket7="";
        }

    
        if ($data['per8']==1){
           $ket8="Hadir";
        }else if ($data['per8']==2){
           $ket8="Alpa"; 
        } else if ($data['per8']==3){
            $ket8="Izin"; 
        } else {
         $ket8="";
        }


        if ($data['per9']==1){
           $ket9="Hadir";
        }else if ($data['per9']==2){
           $ket9="Alpa"; 
        } else if ($data['per9']==3){
            $ket9="Izin"; 
        } else {
         $ket9="";
        }
        
    
        if ($data['per10']==1){
           $ket10="Hadir";
        }else if ($data['per10']==2){
           $ket10="Alpa"; 
        } else if ($data['per10']==3){
            $ket10="Izin"; 
        } else {
         $ket10="";
        }
        

    
        if ($data['per11']==1){
           $ket11="Hadir";
        }else if ($data['per11']==2){
           $ket11="Alpa"; 
        } else if ($data['per11']==3){
            $ket11="Izin"; 
        } else {
         $ket11="";
        }
        
    
       if ($data['per12']==1){
           $ket12="Hadir";
        }else if ($data['per12']==2){
           $ket12="Alpa"; 
        } else if ($data['per12']==3){
            $ket12="Izin"; 
        } else {
         $ket12="";
        }
       
    
        if ($data['per13']==1){
           $ket13="Hadir";
        } else if ($data['per13']==2){
           $ket13="Alpa"; 
        } else if ($data['per13']==3){
            $ket13="Izin"; 
        } else {
         $ket13="";
        }
       

        if ($data['per14']==1){
           $ket14="Hadir";
        }else if ($data['per14']==2){
           $ket14="Alpa"; 
        }
        else if ($data['per14']==3){
            $ket14="Izin"; 
        } else {
         $ket14="";
        }

        //Menampilkan data
        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(17,6,$data['nim'],1,0);
        $pdf->Cell(53,6,$data['nama_mahasiswa'],1,0);
        $pdf->Cell(13,6,$ket1,1,0,'C');
        $pdf->Cell(13,6,$ket2,1,0,'C');
        $pdf->Cell(13,6,$ket3,1,0,'C');
        $pdf->Cell(13,6,$ket4,1,0,'C');
        $pdf->Cell(13,6,$ket5,1,0,'C');
        $pdf->Cell(13,6,$ket6,1,0,'C');
        $pdf->Cell(13,6,$ket7,1,0,'C');
        $pdf->Cell(13,6,$ket8,1,0,'C');
        $pdf->Cell(13,6,$ket9,1,0,'C');
        $pdf->Cell(13,6,$ket10,1,0,'C');
        $pdf->Cell(13,6,$ket11,1,0,'C');
        $pdf->Cell(13,6,$ket12,1,0,'C');
        $pdf->Cell(13,6,$ket13,1,0,'C');
        $pdf->Cell(13,6,$ket14,1,1,'C');
      
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

    $tanggal=date('Y-m-d');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,15,'',0,1,'C');
    $pdf->Cell(450,12,tanggal($tanggal),0,1,'C');
    $pdf->Cell(450,0,'Ketua Bidang Akademik',0,1,'C');
    $pdf->Cell(450,50,$ketua_akademik,0,1,'C');
    $pdf->Output();



?>