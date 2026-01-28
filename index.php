<?php 
session_start();

// 1. PROTEKSI LOGIN
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    header("Location: login.php");
    exit();
}

// 2. KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "toko_online");
if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// 3. LOGIKA TAMBAH DATA (DENGAN UPLOAD FOTO)
if (isset($_POST['tambah'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];
    
    $filename = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $type     = explode('.', $filename);
    $format   = end($type);
    $namabaru = 'produk_' . time() . '.' . $format; 

    if (move_uploaded_file($tmp_name, './img/' . $namabaru)) {
        mysqli_query($conn, "INSERT INTO produk (nama_produk, foto, harga, stok) VALUES ('$nama', '$namabaru', '$harga', '$stok')");
        header("Location: index.php");
        exit();
    }
}

// 4. LOGIKA HAPUS DATA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $data_foto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM produk WHERE id=$id"));
    if ($data_foto && file_exists('./img/' . $data_foto['foto'])) {
        unlink('./img/' . $data_foto['foto']);
    }
    mysqli_query($conn, "DELETE FROM produk WHERE id=$id");
    header("Location: index.php");
    exit();
}

// 5. HITUNG STATISTIK
$res_stat = mysqli_query($conn, "SELECT SUM(harga * stok) as total_nilai, SUM(stok) as total_stok FROM produk");
$stat = mysqli_fetch_assoc($res_stat);

// 6. LOGIKA PENCARIAN
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
    $query_tampil = "SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' ORDER BY id DESC";
} else {
    $query_tampil = "SELECT * FROM produk ORDER BY id DESC";
}
$hasil_tampil = mysqli_query($conn, $query_tampil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce-Web Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary: #6366f1; --bg: #f8fafc; --text: #1e293b; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        
        /* Layout */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
        .card { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .form-grid { display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto; gap: 10px; align-items: end; }
        .widget-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .widget { background: white; padding: 20px; border-radius: 20px; border-left: 5px solid var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

        /* Form & Input */
        input { padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; outline: none; width: 100%; box-sizing: border-box; font-family: inherit; }
        input:focus { border-color: var(--primary); }

        /* Table */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 20px; overflow: hidden; }
        th { background: #f1f5f9; padding: 15px; text-align: left; color: #64748b; font-size: 13px; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; }

        /* Buttons */
        .btn { padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; transition: 0.3s; }
        .btn-add { background: var(--primary); color: white; }
        .btn-print { background: #0ea5e9; color: white; }
        .btn-logout { background: #fee2e2; color: #ef4444; }
        .btn-edit { background: #e0e7ff; color: var(--primary); padding: 8px; }
        .btn-delete { background: #fee2e2; color: #ef4444; padding: 8px; }
        .btn:hover { opacity: 0.8; transform: translateY(-2px); }

        /* CSS KHUSUS CETAK */
        @media print {
            .no-print, .header, .card, .btn-edit, .btn-delete, form { display: none !important; }
            body { background: white; padding: 0; }
            .container { max-width: 100%; }
            table { border: 1px solid #eee; }
            th { background: #eee !important; color: black !important; }
            .print-only { display: block !important; }
        }
        .print-only { display: none; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="print-only">
        <h1>LAPORAN INVENTARIS BARANG</h1>
        <p>E-Commerce-Web | Tanggal: <?php echo date('d-m-Y'); ?></p>
        <hr>
    </div>

    <div class="header no-print">
        <div>
            <h1 style="margin:0; font-size:24px; color:var(--primary);">E-Commerce-Web</h1>
            <p style="margin:5px 0 0 0; color:#64748b;">Halo, <b><?php echo $_SESSION['user_admin']; ?></b>!</p>
        </div>
        <div style="display:flex; gap:10px;">
            <button onclick="window.print()" class="btn btn-print"><i class="fas fa-print"></i> Cetak Laporan</button>
            <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="widget-grid">
        <div class="widget">
            <small>NILAI INVENTARIS</small>
            <h2 style="margin:5px 0 0 0;">Rp <?php echo number_format($stat['total_nilai'], 0, ',', '.'); ?></h2>
        </div>
        <div class="widget" style="border-left-color: #10b981;">
            <small>TOTAL STOK</small>
            <h2 style="margin:5px 0 0 0;"><?php echo number_format($stat['total_stok'], 0, ',', '.'); ?> Unit</h2>
        </div>
    </div>

    <div class="card no-print">
        <h3 style="margin-top:0;"><i class="fas fa-plus-circle"></i> Tambah Produk Baru</h3>
        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <div><label><small>Nama</small></label><input type="text" name="nama" required></div>
            <div><label><small>Foto</small></label><input type="file" name="foto" required></div>
            <div><label><small>Harga</small></label><input type="number" name="harga" required></div>
            <div><label><small>Stok</small></label><input type="number" name="stok" required></div>
            <button type="submit" name="tambah" class="btn btn-add">Simpan Produk</button>
        </form>
    </div>

    <div class="no-print" style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
        <form method="GET" style="display:flex; gap:10px;">
            <input type="text" name="cari" placeholder="Cari barang..." value="<?php echo htmlspecialchars($keyword); ?>" style="width:200px;">
            <button type="submit" class="btn btn-add"><i class="fas fa-search"></i></button>
            <?php if($keyword != ""): ?>
                <a href="index.php" class="btn btn-logout" style="padding:10px;"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-print">Foto</th>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Stok</th>
                <th>Subtotal</th>
                <th class="no-print" style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($hasil_tampil) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($hasil_tampil)): ?>
                <tr>
                    <td class="no-print"><img src="img/<?php echo $row['foto']; ?>" width="50" height="50" style="object-fit:cover; border-radius:8px;"></td>
                    <td><b><?php echo $row['nama_produk']; ?></b></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['stok']; ?> unit</td>
                    <td style="font-weight:bold;">Rp <?php echo number_format($row['harga'] * $row['stok'], 0, ',', '.'); ?></td>
                    <td class="no-print" style="text-align:center">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i></a>
                        <a href="index.php?hapus=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Hapus produk ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:20px;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>