<?php 
ob_start();
session_start();
if (!isset($_SESSION["login"])){
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if($_SESSION["role"] != 'pegawai') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}
  $judul = 'Pengajuan Ketidakhadiran';
include('../layout/header.php'); 
include_once("../../config.php");

if(isset($_POST['submit'])) {
  $id = $_POST['id_pegawai'];
  $keterangan = $_POST['keterangan'];
  $tanggal = $_POST['tanggal'];
  $deskripsi = $_POST['deskripsi'];
  $status_pengajuan = 'PENDING';

  if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $nama_file = $file['name'];
    $file_tmp = $file['tmp_name'];
    $ukuran_file = $file['size'];
    $file_direktori = "../../assets/file_ketidakhadiran /" . $nama_file;

    $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $ekstensi_diizinkan = ["jpg", "png", "jpeg","pdf"];
    $max_ukuran_file = 10 * 1024 * 1024;

    move_uploaded_file($file_tmp, $file_direktori);
  }

  if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (empty($keterangan)) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Keterangan harus diisi!";
    }
    if (empty($tanggal)) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Tanggal harus diisi!";
    }
    if (empty($deskripsi)) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Deskripsi harus diisi!";
    }
    
    if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya File JPG, JPEG, dan PNG yang diperbolehkan!";
    }
    if ($ukuran_file > $max_ukuran_file) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10Mb!";
    }

    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
      $result = mysqli_query($connection, "INSERT INTO ketidakhadiran(id_pegawai, keterangan, deskripsi, tanggal, status_pengajuan, file) VALUES ('$id', '$keterangan', '$deskripsi',
      '$tanggal', '$status_pengajuan', '$nama_file')");

      $_SESSION['berhasil'] = 'Data berhasil disimpan';
      header("Location: ketidakhadiran.php");
      exit;
    }
  }

}

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id_pegawai = '$id' ORDER BY id DESC ");
?>

<div class="page-body">
    <div class="container-xl">
      <div class="card col-md-6">
        <div class="card-body">
          <form action="" method="POST" enctype= "multipart/form-data" >
                <input type="hidden" value="<?= $_SESSION['id'] ?>" name="id_pegawai">
                <div class="mb-3">
                  <label for="">Keterangan</label>
                  <select class="form-control" name="keterangan">
                        <option value="">--Pilih Keterangan--</option>
                        <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'cuti') 
                        { echo 'selected';} ?> value="cuti">cuti</option>
                        <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'izin') 
                        { echo 'selected';} ?> value="izin">izin</option>
                        <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'sakit') 
                        { echo 'selected';} ?> value="sakit">sakit</option>
                          
                      </select>
                </div>
                <div class="mb-3">
                    <label for="">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" cols="30" rows="5"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal" >
                  </div>
                  <div class="mb-3">
                    <label for="">Surat Keterangan</label>
                    <input type="file" class="form-control" name="file">
                  </div>
                  <button type="submit" class="btn btn-primary" name="submit">Ajukan</button>
          </form>
        </div>
      </div>

    </div>
</div>
<?php include('../layout/footer.php'); ?>