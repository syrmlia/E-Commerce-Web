<?php 
// 1. KONEKSI DATABASE
$conn = mysqli_connect("localhost", "root", "", "toko_online");

// Ambil ID dari URL (misal: edit.php?id=5)
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$data = mysqli_fetch_assoc($query);

// 2. LOGIKA UPDATE DATA
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Perintah SQL untuk mengubah data berdasarkan ID
    mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok' WHERE id=$id");
    
    // Kembali ke halaman utama setelah berhasil
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - E-Commerce-Web</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --text: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .edit-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            font-weight: 700;
            color: var(--text);
            margin-bottom: 25px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: 0.3s;
            text-align: center;
            text-decoration: none;
        }

        .btn-save { background: var(--primary); color: white; }
        .btn-save:hover { background: #4f46e5; transform: translateY(-2px); }

        .btn-cancel { background: #f1f5f9; color: #64748b; }
        .btn-cancel:hover { background: #e2e8f0; }
    </style>
</head>
<body>

<div class="edit-card">
    <h2><i class="fas fa-pen-to-square" style="color: var(--primary);"></i> Edit Produk</h2>
    
    <form method="POST">
        <label>Nama Produk</label>
        <input type="text" name="nama" value="<?php echo $data['nama_produk']; ?>" required>

        <label>Harga (Rp)</label>
        <input type="number" name="harga" value="<?php echo $data['harga']; ?>" required>

        <label>Stok Barang</label>
        <input type="number" name="stok" value="<?php echo $data['stok']; ?>" required>

        <div class="btn-group">
            <a href="index.php" class="btn btn-cancel">Batal</a>
            <button type="submit" name="update" class="btn btn-save">Update Data</button>
        </div>
    </form>
</div>

</body>
</html>