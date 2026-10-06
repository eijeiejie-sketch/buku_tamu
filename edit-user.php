<?php
require_once('function.php');
include_once('templates/header.php');

// pengecekan user role bukan admin maka tidak boleh mengakses halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('anda tidak memiliki akses')</script>";
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

// jika ada id_user di URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    $result = query("SELECT * FROM users WHERE id_user = '$id_user'");

    if (empty($result)) {
        echo "<script>alert('Data user tidak ditemukan!')</script>";
        echo "<script>window.location.href='users.php'</script>";
        exit;
    }

    // ambil data user yang sesuai dengan id_user
    $data = $result[0];
} else {
    echo "<script>window.location.href='users.php'</script>";
    exit;
}

// jika ada tombol simpan
if (isset($_POST['simpan'])) {
    if (ubah_user($_POST) > 0) {
?>
        <div class="alert alert-success" role="alert">
            Data berhasil diubah!
        </div>
<?php
        $data = query("SELECT * FROM users WHERE id_user = '$id_user'")[0];
    } else {
?>
        <div class="alert alert-danger" role="alert">
            Data gagal diubah!
        </div>
<?php
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ubah Data User</h1>

    <!-- Konten Edit Data User -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Data User</h6>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <input type="hidden" name="id_user" id="id_user" value="<?= $id_user ?>">
                <div class="form-group row">
                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= $data['username'] ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user_role" class="col-sm-3 col-form-label">User Role</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="user_role" name="user_role">
                            <option value="admin" <?= $data['user_role'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                            <option value="operator" <?= $data['user_role'] == 'operator' ? 'selected' : ''; ?>>Operator</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-8 d-flex justify-content-end">
                        <a type="button" class="btn btn-danger btn-icon-split" href="users.php">
                            <span class="icon text-white-50">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                            <span class="text">Kembali</span>
                        </a>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>
