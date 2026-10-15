
<?php
require 'koneksi.php';

$cari = trim($_GET['cari'] ?? '');

$stmt = mysqli_prepare($conn,
    "SELECT * FROM showroom_mobil
     WHERE nama_mobil LIKE CONCAT('%', ?, '%')
        OR brand_mobil LIKE CONCAT('%', ?, '%')
     ORDER BY id DESC");

mysqli_stmt_bind_param($stmt, 'ss', $cari, $cari);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

function aman($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShowRoom EAD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <p class="label">PRAKTIKUM PAW / MODUL 2</p>
    <h1>ShowRoom EAD</h1>
    <p>Kelola data mobil yang tersedia di showroom.</p>

    <?php if (isset($_GET['pesan'])): ?>
        <p class="pesan">Perubahan data berhasil disimpan.</p>
    <?php endif; ?>

    <p><a class="tombol" href="tambah.php">Tambah mobil</a></p>

    <form method="get" class="pencarian">
        <label for="cari">Cari nama atau brand</label>
        <input id="cari" name="cari" value="<?= aman($cari) ?>">
        <button type="submit">Cari</button>
        <a href="index.php">Reset</a>
    </form>

    <?php $jumlah = mysqli_num_rows($hasil); ?>
    <p>Jumlah hasil: <?= $jumlah ?></p>

    <?php if ($jumlah === 0): ?>
        <p>Data tidak ditemukan. Coba kata kunci lain.</p>
    <?php endif; ?>

    <div class="daftar">
        <?php while ($mobil = mysqli_fetch_assoc($hasil)): ?>
            <article>
                <p class="label"><?= aman($mobil['kode_mobil']) ?></p>

                <h2><?= aman($mobil['nama_mobil']) ?></h2>

                <p>
                    <?= aman($mobil['brand_mobil']) ?> &bull;
                    <?= aman($mobil['tipe_mobil']) ?>
                </p>

                <p>Warna: <?= aman($mobil['warna_mobil']) ?></p>

                <p>
                    Rp <?= number_format(
                        (float)$mobil['harga_mobil'], 0, ',', '.'
                    ) ?>
                </p>

                <a href="detail.php?id=<?= (int)$mobil['id'] ?>">
                    Detail
                </a>

                <a href="edit.php?id=<?= (int)$mobil['id'] ?>">
                    Edit
                </a>

                <a href="hapus.php?id=<?= (int)$mobil['id'] ?>">
                    Hapus
                </a>
            </article>
        <?php endwhile; ?>
    </div>
</main>
</body>
</html>
