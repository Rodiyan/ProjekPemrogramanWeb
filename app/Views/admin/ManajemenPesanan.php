<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Manajemen Pesanan' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4 align-items-center">
            <h1>📦 Daftar Semua Pesanan</h1>
            <div>
                <a href="/admin/dashboard" class="btn btn-secondary me-2">Kembali ke Dashboard</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <p class="lead">Total Pesanan Tercatat: **<?= count($orders ?? []) ?>**</p>
        
        <table class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="table-primary">
                    <th>ID</th>
                    <th>Tanggal Pesan</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td>**<?= $order['id'] ?>**</td>
                        <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                        <td><?= $order['customer_name'] ?? 'Pelanggan ID ' . $order['user_id'] ?></td> 
                        <td>Rp **<?= number_format($order['total_price'], 0, ',', '.') ?>**</td>
                        <td>
                            <?php 
                                $status = $order['status'];
                                $badge_class = '';
                                if ($status == 'Pending') $badge_class = 'bg-warning text-dark';
                                else if ($status == 'Processing') $badge_class = 'bg-info';
                                else if ($status == 'Completed') $badge_class = 'bg-success';
                                else $badge_class = 'bg-secondary';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= $status ?></span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info">Detail</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pesanan yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>