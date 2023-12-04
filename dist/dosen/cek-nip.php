<?php
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip=addslashes(trim($_POST['nip']));
    include "../../config/database.php";
    $hasil = mysqli_query ($kon,"select * from dosen where nip='".$nip."' limit 1");
    $jumlah = mysqli_num_rows($hasil);

    if (empty($nip)){
        echo "<p class='text-warning'>NIP tidak boleh kosong</p>";
        echo "<script>   document.getElementById('Submit').disabled = true; </script>";
   
    }else {
        if ($jumlah>=1){
            echo "<p class='text-danger'>NIP telah digunakan</p>";
            echo "<script>  $('#Submit').prop('disabled', true); </script>";
        }else {
            echo "<p class='text-success'>NIP Tersedia</p>";
            echo "<script>  $('#Submit').prop('disabled', false); </script>";
        }
    }
}
?>
