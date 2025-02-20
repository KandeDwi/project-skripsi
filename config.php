<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "absen_app";

$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if( !$connection){
  echo "Koneksi ke database Gagal" . mysqli_connect_error();
}


function base_url($url = null)
{
  $base_url = 'http://localhost/presensi-guru';
  if($url != null){
    return $base_url .'/'.$url;
  }else{
     return $base_url;
}
}
?>