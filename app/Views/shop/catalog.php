<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Katalog Produk' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .product-image { height: 200px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .card-footer { background: white; border-top: none; }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm">
            <h1>💻 Toko Komputer</h1>
            <div>
                <?php if (session()->get('isLoggedIn') && session()->get('role') == 'admin'): ?>
                    <a href="/admin/dashboard" class="btn btn-warning me-2">Dashboard Admin</a>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                <?php elseif (session()->get('isLoggedIn') && session()->get('role') == 'customer'): ?>
                    <a href="/profile" class="btn btn-primary me-2">Profil Saya</a>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-outline-primary me-2">Login</a>
                    <a href="/register" class="btn btn-success">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success mt-3"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mt-3"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <h2>Katalog Produk Terbaik</h2>
        <hr>
        
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach($products as $product): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="/images/<?= $product['image'] ?? 'default.jpg' ?>" class="card-img-top product-image" alt="<?= $product['name'] ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                                <p class="card-text text-muted small flex-grow-1"><?= substr($product['description'], 0, 80) ?>...</p>
                                <p class="total-price fs-4 text-success">Rp **<?= number_format($product['price'], 0, ',', '.') ?>**</p>
                            </div>
                            <div class="card-footer text-center">
                                <?php if ($product['stock'] > 0): ?>
                                    <?php if (session()->get('isLoggedIn')): ?>
                                        <a href="/buy/<?= $product['id'] ?>" class="btn btn-warning w-100">Beli Sekarang</a>
                                    <?php else: ?>
                                        <a href="/login" class="btn btn-secondary w-100">Login untuk Beli</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-outline-danger w-100" disabled>Stok Habis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 alert alert-warning">
                    Tidak ada produk yang tersedia saat ini.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>