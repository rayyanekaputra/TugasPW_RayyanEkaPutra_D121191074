<?php

function koneksi()
{
  return mysqli_connect('localhost', 'root', 'root', 'pw_043040023');
}

function query($query)
{
  $conn = koneksi();

  $result = mysqli_query($conn, $query);

  // jika hasilnya hanya 1 data
  if (mysqli_num_rows($result) == 1) {
    return mysqli_fetch_assoc($result);
  }

  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function upload(){
    $nama_file = $_FILES['gambar']['name'];
    $tipe_file = $_FILES['gambar']['type'];
    $ukuran_file = $_FILES['gambar']['size'];
    $error =  $_FILES['gambar']['error'];
    $tmp_file = $_FILES['gambar']['tmp_name'];

    //ketika tidak ada gambar yang dipilih
    if($error == 4){
      echo "<script>
        alert('Gambar kosong!');
      </script>";

      return 'nophoto.jpg';
    }

    //cek ekstensi file
    $daftar_gambar = ['jpg','jpeg','png'];
    $ekstensi_file = explode('.', $nama_file);
    $ekstensi_file = strtolower(end($ekstensi_file));

    if(!in_array($ekstensi_file, $daftar_gambar )){
      echo "<script>
        alert('bukan gambar!');
      </script>";

      return false;
    }

    //cek tipe file
    if($tipe_file != 'image/jpeg' && $tipe_file != 'image/png'){
      echo "<script>
        alert('bukan gambar!');
      </script>";

      return false;
    }

    //cek ukuran file maks 5mb 

    if($ukuran_file>500000){
      echo "<script>
        alert('ukuran terlalu besar');
      </script>";

      return false;
    }

    //lolos

    $nama_file_baru = uniqid();
    $nama_file_baru .= '.';
    $nama_file_baru .= $ekstensi_file;
    move_uploaded_file($tmp_file, 'img/', $nama_file);

    return $nama_file_baru;
}

function tambah($data)
{
  $conn = koneksi();

  $nama = htmlspecialchars($data['nama']);
  $nrp = htmlspecialchars($data['nrp']);
  $email = htmlspecialchars($data['email']);
  $jurusan = htmlspecialchars($data['jurusan']);
  $gambar = htmlspecialchars($data['gambar']);

  //upload gambar
  $gambar = upload();
  if (!$gambar){
    return false;
  }

  $query = "INSERT INTO
              mahasiswa
            VALUES
            (null, '$nama', '$nrp', '$email', '$jurusan', '$gambar');
          ";
  mysqli_query($conn, $query);
  echo mysqli_error($conn);
  return mysqli_affected_rows($conn);
}
