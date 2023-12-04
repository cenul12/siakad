<?php
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode=addslashes(trim($_POST['kode']));
    include "../../config/database.php";
    $hasil = mysqli_query ($kon,"select * from matakuliah where kode_matakuliah='".$kode."' limit 1");
    $jumlah = mysqli_num_rows($hasil);

    if (empty($kode)){
        echo "<p class='text-warning'>Kode matakuliah tidak boleh kosong</p>";
        echo "<script>   document.getElementById('Submit').disabled = true; </script>";
   
    }else {
        if ($jumlah>=1){
            echo "<p class='text-danger'>kode matakuliah telah digunakan</p>";
            echo "<script>  $('#Submit').prop('disabled', true); </script>";
        }else {
            echo "<p class='text-success'>kode matakuliah Tersedia</p>";
            echo "<script>  $('#Submit').prop('disabled', false); </script>";
        }
    }
}
?>
