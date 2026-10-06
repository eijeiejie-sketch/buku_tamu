<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit;
}

// panggil file function.php
require_once 'function.php';

// jika ada id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // hapus file gambar terkait (jika ada) sebelum data dihapus
    $tamu = query("SELECT * FROM buku_tamu WHERE id_tamu = '$id'");
    if (!empty($tamu) && !empty($tamu[0]['gambar']) && file_exists('assets/upload_gambar/' . $tamu[0]['gambar'])) {
        unlink('assets/upload_gambar/' . $tamu[0]['gambar']);
    }

    if (hapus_tamu($id) > 0) {
        // jika data berhasil di hapus maka akan muncul alert
        echo "<script>alert('Data Berhasil di hapus!')</script>";
        // redirect ke halaman buku-tamu.php
        echo "<script>window.location.href='buku-tamu.php'</script>";
    } else {
        // jika gagal di hapus
        echo "<script>alert('Data Gagal di hapus!')</script>";
        echo "<script>window.location.href='buku-tamu.php'</script>";
    }
}
?>
