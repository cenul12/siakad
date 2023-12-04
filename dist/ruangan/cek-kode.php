<?php
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode=addslashes(trim($_POST['kode']));
    include "../../config/database.php";
    $hasil = mysqli_query ($kon,"select * from ruangan where kode_ruangan='".$kode."' limit 1");
    $jumlah = mysqli_num_rows($hasil);

    if (empty($kode)){
        echo "<p class='text-warning'>Kode ruangan tidak boleh kosong</p>";
        echo "<script>   document.getElementById('Submit').disabled = true; </script>";
   
    }else {
        if ($jumlah>=1){
            echo "<p class='text-danger'>kode ruangan telah digunakan</p>";
            echo "<script>  $('#Submit').prop('disabled', true); </script>";
        }else {
            echo "<p class='text-success'>kode ruangan Tersedia</p>";
            echo "<script>  $('#Submit').prop('disabled', false); </script>";
        }
    }
}
?>
