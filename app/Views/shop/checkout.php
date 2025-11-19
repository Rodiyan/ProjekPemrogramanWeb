<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Konfirmasi Pembelian' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .product-image { max-width: 100%; height: auto; border-radius: 8px; }
        .checkout-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .total-price { font-size: 1.8rem; font-weight: bold; color: #28a745; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h1>🛒 Konfirmasi Pembelian</h1>
            <a href="/" class="btn btn-secondary">Batalkan & Kembali ke Katalog</a>
        </div>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="checkout-box">
            <div class="row">
                <div class="col-md-5">
                    <h3>Detail Produk</h3>
                    <hr>
                    <img src="/images/<?= $product['image'] ?? 'default.jpg' ?>" class="product-image mb-3" alt="Produk">
                    <h5>**<?= $product['name'] ?>**</h5>
                    <p class="text-muted"><?= nl2br(esc(substr($product['description'], 0, 150))) ?>...</p>
                    <p>Harga Satuan: Rp **<?= number_format($product['price'], 0, ',', '.') ?>**</p>
                    <p>Stok Tersisa: **<?= $product['stock'] ?>**</p>
                </div>

                <div class="col-md-7">
                    <h4>Form Pesanan & Jumlah</h4>
                    <hr>
                    <form action="/process-order" method="post">
                        <?= csrf_field() ?>
                        
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Pembeli</label>
                            <input type="text" class="form-control" value="<?= $session->get('name') ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= $session->get('username') ?>" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="quantity" class="form-label">**Masukkan Jumlah Beli**</label>
                            <input type="number" class="form-control form-control-lg" id="quantity" name="quantity" 
                                value="1" min="1" max="<?= $product['stock'] ?>" required 
                                oninput="calculateTotal(this.value, <?= $product['price'] ?>)">
                            <div class="form-text">Maksimal pembelian: <?= $product['stock'] ?> unit.</div>
                        </div>

                        <div class="alert alert-info text-center">
                            Total Harga (belum termasuk ongkir):
                            <div id="totalDisplay" class="total-price">
                                Rp <?= number_format($product['price'], 0, ',', '.') ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 mt-3">
                            ✅ KONFIRMASI & PROSES PEMBAYARAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateTotal(quantity, price) {
            const total = quantity * price;
            const formattedTotal = total.toLocaleString('id-ID'); // Format ke Rupiah
            document.getElementById('totalDisplay').innerText = `Rp ${formattedTotal}`;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>