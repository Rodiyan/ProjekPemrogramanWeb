<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Beli Produk' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .product-image { max-width: 100%; height: auto; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h1>Detail Pembelian</h1>
            <a href="/" class="btn btn-secondary">Kembali ke Katalog</a>
        </div>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="/images/<?= $product['image'] ?? 'default.jpg' ?>" class="product-image" alt="Gambar Produk">
                    </div>
                    <div class="col-md-8">
                        <h2><?= $product['name'] ?></h2>
                        <h3 class="text-success">Rp **<?= number_format($product['price'], 0, ',', '.') ?>**</h3>
                        <p class="text-muted">Stok Tersedia: **<?= $product['stock'] ?>**</p>
                        <p><?= nl2br(esc($product['description'])) ?></p>
                        
                        <hr>
                        
                        <h4>Form Konfirmasi Pesanan</h4>
                        <form action="/process-order" method="post">
                            <?= csrf_field() ?>
                            
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="unit_price" value="<?= $product['price'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Nama Anda</label>
                                <input type="text" class="form-control" name="customer_name" value="<?= $session->get('name') ?>" required readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kontak (Nomor HP/WA)</label>
                                <input type="text" class="form-control" name="customer_contact" value="<?= $session->get('contact') ?? 'Mohon isi di halaman profil' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah Beli</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" required>
                                <div class="form-text">Maksimal stok yang dapat dibeli adalah <?= $product['stock'] ?>.</div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg">Konfirmasi & Proses Pesanan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>