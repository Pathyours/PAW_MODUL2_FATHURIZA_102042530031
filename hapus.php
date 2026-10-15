
<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    exit('ID mobil tidak valid.');
}

$stmt = mysqli_prepare($conn,
    "SELECT * FROM showroom_mobil WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$mobil = mysqli_fetch_assoc($hasil);

if (!$mobil) {
    exit('Mobil tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['konfirmasi'] ?? '') !== 'ya') {
        header('Location: index.php');
        exit;
    }

    $stmt = mysqli_prepare($conn,
        "DELETE FROM showroom_mobil WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit;
    }

    exit('Gagal menghapus data mobil.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Mobil - ShowRoom EAD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <p class="label">SHOWROOM EAD</p>
    <h1>Hapus mobil</h1>

    <p>Yakin ingin menghapus mobil
        <strong><?= htmlspecialchars($mobil['nama_mobil']) ?></strong>?
    </p>
    <p>Kode: <?= htmlspecialchars($mobil['kode_mobil']) ?></p>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" name="konfirmasi" value="ya">
            Ya, hapus mobil
        </button>
        <a href="index.php">Batal</a>
    </form>
</main>
</body>
</html>
