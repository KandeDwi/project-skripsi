<?php 

session_start();
require_once('../../config.php');

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM users WHERE id_pegawai=$id");

$_SESSION['berhasil'] = 'Data Berhasil Dihapus';
header("Location: pegawai.php");
exit;

include('../layout/footer.php');
?>