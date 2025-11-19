<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin | Toko Komputer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tambahkan style untuk memisahkan navigasi utama */
        .admin-nav {
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-bottom: 25px;
            display: flex;
            gap: 10px;
        }
        /* Style untuk gambar thumbnail di tabel */
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body class="p-5">
    
    <div class="d-flex justify-content-between mb-4 align-items-center">
        <h1>Dashboard Admin</h1>
        <a href="/logout" class="btn btn-danger">Logout (<?= session()->get('name') ?>)</a> 
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <div class="admin-nav">
        <a href="/admin/dashboard" class="btn btn-dark">Dashboard</a> 
        
        <a href="/admin/orders" class="btn btn-success">📦 Kelola Pesanan</a>
        
        <a href="/admin/users" class="btn btn-primary">👥 Kelola Pengguna</a>
    </div>
    
    <div class="card mb-5">
        <div class="card-header">Tambah Produk Baru</div>
        <div class="card-body">
            <form action="/admin/produk/store" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?> 
                
                <div class="row">
                    <div class="col-md-3 mb-2"><input type="text" name="name" class="form-control" placeholder="Nama Produk" required></div>
                    <div class="col-md-2 mb-2"><input type="number" name="price" class="form-control" placeholder="Harga" required></div>
                    <div class="col-md-2 mb-2"><input type="number" name="stock" class="form-control" placeholder="Stok" required></div>
                    
                    <div class="col-md-5 mb-2">
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="form-text text-muted">Maksimal ukuran file 2MB.</small>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-7 mb-2">
                        <textarea name="description" class="form-control" placeholder="Deskripsi Singkat"></textarea>
                    </div>
                    <div class="col-md-5 mb-2">
                        <button type="submit" class="btn btn-primary w-100">Simpan Produk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <h3>Daftar Produk (<?= count($products ?? []) ?>)</h3>
    <table class="table table-bordered table-hover">
        <thead>
            <tr><th>Gambar</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach($products as $p): ?>
                <tr>
                    <td>
                        <img src="/images/<?= $p['image'] ?? 'default.jpg' ?>" alt="Produk" class="product-thumb">
                    </td>
                    <td><?= $p['name'] ?></td>
                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td>
                        <a href="/admin/produk/edit/<?= $p['id'] ?>" class="btn btn-sm btn-info text-white me-2">Edit</a> 
                        <a href="/admin/produk/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">Belum ada data produk yang tersedia.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>

    <h3 class="mt-5">Pesanan Masuk (<?= count($orders ?? []) ?>)</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr><th>ID Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= $o['customer_name'] ?? 'N/A' ?></td>
                    <td>Rp <?= number_format($o['total_price'], 0, ',', '.') ?></td>
                    <td><span class="badge bg-primary"><?= $o['status'] ?></span></td>
                    <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">Tidak ada pesanan baru.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>