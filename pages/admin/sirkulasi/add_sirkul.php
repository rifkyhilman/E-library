<?php  
    $carikode = mysqli_query($koneksi,"SELECT id_sk FROM tb_sirkulasi order by id_sk desc");
    $datakode = mysqli_fetch_array($carikode);
    $kode = $datakode['id_sk'];
    $urut = substr($kode, 1, 3);
    $tambah = (int) $urut + 1;

    if (strlen($tambah) == 1){
        $format = "S"."00".$tambah;
    }else if (strlen($tambah) == 2){
        $format = "S"."0".$tambah;
    }else {
        $format = "S".$tambah;
    }
?>

<section class="container">
    <div class="nav-links">
        <h5>Tambah Data Sirkulasi</h5>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/E-Library">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="?page=data_sirkul">Sirkulasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah_Sirkulasi</li>
            </ol>
        </nav>                          
    </div>
    <div class="box-page">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="box-body">
                <div class="form-group">
                    <label>Id Sirkulasi</label>
                    <input type="text" name="id_sk" id="id_sk" class="form-control inpt"
                        value="<?php echo $format; ?>" readonly />
                </div>
                <div class="form-group">
                    <label>Nama Peminjam</label>
                    <select name="id_anggota" id="id_anggota" class="form-control inpt select2" style="width: 100%;">
                        <option selected="selected">-- Pilih --</option>
                        <?php
                        // ambil data dari database
                        $query = "select * from tb_anggota";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                        <option value="<?php echo $row['id_anggota'] ?>">
                            <?php echo $row['id_anggota'] ?>
                            -
                            <?php echo $row['nama'] ?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Buku</label>
                    <select name="id_buku" id="id_buku" class="form-control inpt select2" style="width: 100%;">
                        <option selected="selected">-- Pilih --</option>
                        <?php
                        // ambil data dari database
                        $query = "select * from tb_buku";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                        ?>
                        <option value="<?php echo $row['id_buku'] ?>">
                            <?php echo $row['id_buku'] ?>
                            -
                            <?php echo $row['judul_buku'] ?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tgl Pinjam</label>
                    <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control inpt" />
                </div>

            </div>
            <div class="box-footer">
                <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                <a href="?page=data_sirkul" class="btn btn-warning">Batal</a>
            </div>
        </form>
    </div>
</section>


<?php
    if (isset ($_POST['Simpan'])){

		//menangkap post tgl pinjam
		$tgl_p=$_POST['tgl_pinjam'];
		//membuat tgl kembali
		$tgl_k=date('Y-m-d', strtotime('+7 days', strtotime($tgl_p)));
		$tgl_hk=date('Y-m-d');
    
        $sql_simpan = "INSERT INTO tb_sirkulasi (id_sk,id_buku,id_anggota,tgl_pinjam,status,tgl_kembali,tgl_dikembalikan) VALUES (
           '".$_POST['id_sk']."',
          '".$_POST['id_buku']."',
          '".$_POST['id_anggota']."',
          '".$_POST['tgl_pinjam']."',
		  'PIN',
		  '".$tgl_k."',
		  '".$tgl_hk."');";
		$sql_simpan .= "INSERT INTO log_pinjam (id_buku,id_anggota,tgl_pinjam) VALUES (
			'".$_POST['id_buku']."',
			'".$_POST['id_anggota']."',
            '".$_POST['tgl_pinjam']."')";   
        $query_simpan = mysqli_multi_query($koneksi, $sql_simpan);
        mysqli_close($koneksi);

    if ($query_simpan){

      echo "<script>
      Swal.fire({
        title: 'Tambah Data Sirkulasi Berhasil',
        text: '',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then((result) => {
          if (result.value) {
              window.location = '?page=data_sirkul';
          }
      })</script>";
    }else{
      echo "<script>
      Swal.fire({
        title: 'Tambah Data Sirkulasi Gagal',
        text: '',
        icon: 'error',
        confirmButtonText: 'OK'
      }).then((result) => {
          if (result.value) {
              window.location = '?page=add_sirkul';
          }
      })</script>";
    }
  }

  ?>