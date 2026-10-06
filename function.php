<?php
// panggil file koneksi.php
require_once('koneksi.php');

// membuat query ke / dari database
function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);

    if ($result === false) {
        return [];
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function generate_kode($table, $column, $prefix, $length)
{
    global $koneksi;

    $query = "SELECT MAX($column) AS kode_terbesar FROM $table";
    $result = mysqli_query($koneksi, $query);

    if ($result === false || mysqli_num_rows($result) === 0) {
        return $prefix . str_repeat('0', $length - 1) . '1';
    }

    $row = mysqli_fetch_assoc($result);
    $kodeTerbesar = $row['kode_terbesar'] ?? '';
    $angka = 0;

    if ($kodeTerbesar !== null && $kodeTerbesar !== '') {
        $angka = (int) preg_replace('/\D+/', '', $kodeTerbesar);
    }

    return $prefix . sprintf('%0' . $length . 'd', $angka + 1);
}

// ==========================================================
// UPLOAD GAMBAR
// ==========================================================
function uploadGambar()
{
    // jika user tidak memilih gambar, tidak apa-apa (opsional)
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] == 4) {
        return '';
    }

    // ambil data file gambar dari variable $_FILES
    $namaFile   = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error      = $_FILES['gambar']['error'];
    $tmpName    = $_FILES['gambar']['tmp_name'];

    // cek apakah yang diunggah adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('File yang diunggah harus gambar!');
              </script>";
        return false;
    }

    // cek jika ukurannya terlalu besar (maksimal 1 MB)
    if ($ukuranFile > 1000000) {
        echo "<script>
                alert('Ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

    // jika lolos pengecekan, gambar akan diunggah
    // generate nama gambar baru dengan uniqid()
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'assets/upload_gambar/' . $namaFileBaru);

    return $namaFileBaru;
}

// ==========================================================
// CRUD BUKU TAMU
// ==========================================================

// function tambah data tamu
function tambah_tamu($data)
{
    global $koneksi;

    $kode         = htmlspecialchars($data["id_tamu"]);
    $tanggal      = date("Y-m-d");
    $nama_tamu    = htmlspecialchars($data["nama_tamu"]);
    $alamat       = htmlspecialchars($data["alamat"]);
    $no_hp        = htmlspecialchars($data["no_hp"]);
    $bertemu      = htmlspecialchars($data["bertemu"]);
    $kepentingan  = htmlspecialchars($data["kepentingan"]);

    // upload gambar
    $gambar = uploadGambar();
    if ($gambar === false) {
        return false;
    }

    $query = "INSERT INTO buku_tamu VALUES ('$kode','$tanggal','$nama_tamu','$alamat','$no_hp','$bertemu','$kepentingan','$gambar')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// function ubah data tamu
function ubah_tamu($data)
{
    global $koneksi;

    $id           = htmlspecialchars($data["id_tamu"]);
    $nama_tamu    = htmlspecialchars($data["nama_tamu"]);
    $alamat       = htmlspecialchars($data["alamat"]);
    $no_hp        = htmlspecialchars($data["no_hp"]);
    $bertemu      = htmlspecialchars($data["bertemu"]);
    $kepentingan  = htmlspecialchars($data["kepentingan"]);
    $gambarLama   = htmlspecialchars($data['gambarLama']);

    // cek apakah user pilih gambar baru atau tidak
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] == 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = uploadGambar();
        if ($gambar === false) {
            $gambar = $gambarLama;
        }
    }

    $query = "UPDATE buku_tamu SET
                nama_tamu    = '$nama_tamu',
                alamat       = '$alamat',
                no_hp        = '$no_hp',
                bertemu      = '$bertemu',
                kepentingan  = '$kepentingan',
                gambar       = '$gambar'
              WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// function hapus data tamu
function hapus_tamu($id)
{
    global $koneksi;

    $query = "DELETE FROM buku_tamu WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// ==========================================================
// CRUD USER
// ==========================================================

// function tambah data user
function tambah_user($data)
{
    global $koneksi;

    $kode      = htmlspecialchars($data["id_user"]);
    $username  = htmlspecialchars($data["username"]);
    $password  = htmlspecialchars($data["password"]);
    $user_role = htmlspecialchars($data["user_role"]);

    // enkripsi password dengan password_hash
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users VALUES ('$kode','$username','$password_hash','$user_role')";

    mysqli_query($koneksi, $query);
    if (strlen($password) < 6) {
        echo "
            <script>
                alert('password minimal 6 karakter');
            </script>
        ";
        return 0;
    }

    return mysqli_affected_rows($koneksi);
}

// function ubah data user
function ubah_user($data)
{
    global $koneksi;

    $kode      = htmlspecialchars($data["id_user"]);
    $username  = htmlspecialchars($data["username"]);
    $user_role = htmlspecialchars($data["user_role"]);

    $query = "UPDATE users SET
                username  = '$username',
                user_role = '$user_role'
              WHERE id_user = '$kode'";

    mysqli_query($koneksi, $query);

    

    return mysqli_affected_rows($koneksi);
}

// function hapus data user
function hapus_user($id)
{
    global $koneksi;

    $query = "DELETE FROM users WHERE id_user = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// function ganti password user
function ganti_password($data)
{
    global $koneksi;

    $kode          = htmlspecialchars($data["id_user"]);
    $password      = htmlspecialchars($data["password"]);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET
                password = '$password_hash'
              WHERE id_user = '$kode'";

    mysqli_query($koneksi, $query);
    if (strlen($password) < 6) {
        echo "
            <script>
                alert('password minimal 6 karakter');
            </script>
        ";
        return 0;
    }
    return mysqli_affected_rows($koneksi);
}
