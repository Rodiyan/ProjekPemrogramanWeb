<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image { max-width: 150px; height: auto; border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body class="p-5">
    
    <div class="d-flex justify-content-between mb-4 align-items-center">
        <h1>⚙️ Edit Produk: <?= $product['name'] ?></h1>
        <a href="/admin/dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card mb-5">
        <div class="card-header">Form Edit Data Produk ID: **<?= $product['id'] ?>**</div>
        <div class="card-body">
            <form action="/admin/produk/update/<?= $product['id'] ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?> 
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" value="<?= old('name', $product['name']) ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="price" class="form-control" value="<?= old('price', $product['price']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stock" class="form-control" value="<?= old('stock', $product['stock']) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"><?= old('description', $product['description']) ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label">Gambar Saat Ini</label><br>
                        <img src="/images/<?= $product['image'] ?>" alt="Gambar Produk" class="product-image"><br>

                        <div class="mb-3">
                            <label class="form-label">Ganti Gambar (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Abaikan jika tidak ingin mengganti gambar.</small>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-warning btn-lg mt-3">Perbarui Produk</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>