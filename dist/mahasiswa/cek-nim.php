<?php
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim=addslashes(trim($_POST['nim']));
    include "../../config/database.php";
    $hasil = mysqli_query ($kon,"select * from mahasiswa where nim='".$nim."' limit 1");
    $jumlah = mysqli_num_rows($hasil);

    if (empty($nim)){
        echo "<p class='text-warning'>NIM tidak boleh kosong</p>";
        echo "<script>   document.getElementById('Submit').disabled = true; </script>";
    }else {
        if ($jumlah>=1){
            echo "<p class='text-danger'> NIM telah digunakan</p>";
            echo "<script>  $('#Submit').prop('disabled', true); </script>";
        }else {
            echo "<p class='text-success'> NIM Tersedia</p>";
            echo "<script>  $('#Submit').prop('disabled', false); </script>";
        }
    }
}
?>
