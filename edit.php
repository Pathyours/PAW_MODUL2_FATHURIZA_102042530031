
<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$hasil = mysqli_query($conn,
    "SELECT * FROM showroom_mobil WHERE id = $id");
$mobil = mysqli_fetch_assoc($hasil);

if (!$mobil) {
    exit('Mobil tidak ditemukan.');
}

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode_mobil'] ?? '');
    $nama = trim($_POST['nama_mobil'] ?? '');
    $brand = trim($_POST['brand_mobil'] ?? '');
    $warna = trim($_POST['warna_mobil'] ?? '');
    $tipe = trim($_POST['tipe_mobil'] ?? '');
    $harga = trim($_POST['harga_mobil'] ?? '');

    if (
        $kode === '' || $nama === '' || $brand === '' ||
        $warna === '' || $tipe === '' || $harga === '' ||
        !ctype_digit($harga)
    ) {
        $pesan = 'Semua kolom wajib diisi. Harga harus berupa angka.';
    } else {
        $stmt = mysqli_prepare($conn,
            "UPDATE showroom_mobil
             SET kode_mobil=?, nama_mobil=?, brand_mobil=?,
                 warna_mobil=?, tipe_mobil=?, harga_mobil=?
             WHERE id=?");

        mysqli_stmt_bind_param(
            $stmt, 'sssssii',
            $kode, $nama, $brand, $warna, $tipe, $harga, $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: index.php');
            exit;
        } else {
            $pesan = 'Gagal memperbarui data. Pastikan kode mobil tidak duplikat.';
        }
    }

    $mobil['kode_mobil'] = $kode;
    $mobil['nama_mobil'] = $nama;
    $mobil['brand_mobil'] = $brand;
    $mobil['warna_mobil'] = $warna;
    $mobil['tipe_mobil'] = $tipe;
    $mobil['harga_mobil'] = $harga;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mobil - ShowRoom EAD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <p class="label">SHOWROOM EAD</p>
    <h1>Edit mobil</h1>

    <?php if ($pesan !== ''): ?>
        <p class="kesalahan"><?= htmlspecialchars($pesan) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Kode mobil</label>
        <input name="kode_mobil" maxlength="20" required
            value="<?= htmlspecialchars($mobil['kode_mobil']) ?>">

        <label>Nama mobil</label>
        <input name="nama_mobil" maxlength="100" required
            value="<?= htmlspecialchars($mobil['nama_mobil']) ?>">

        <label>Brand</label>
        <input name="brand_mobil" maxlength="50" required
            value="<?= htmlspecialchars($mobil['brand_mobil']) ?>">

        <label>Warna</label>
        <input name="warna_mobil" maxlength="30" required
            value="<?= htmlspecialchars($mobil['warna_mobil']) ?>">

        <label>Tipe</label>
        <input name="tipe_mobil" maxlength="20" required
            value="<?= htmlspecialchars($mobil['tipe_mobil']) ?>">

        <label>Harga (angka saja)</label>
        <input name="harga_mobil" type="number" min="0" required
            value="<?= htmlspecialchars((string)$mobil['harga_mobil']) ?>">

        <button type="submit">Simpan perubahan</button>
    </form>

    <p><a href="index.php">Kembali ke daftar</a></p>
</main>
</body>
</html>
