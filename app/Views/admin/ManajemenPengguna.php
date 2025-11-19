<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Manajemen Pengguna' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4 align-items-center">
            <h1>👥 Daftar Pengguna Pelanggan</h1>
            <div>
                <a href="/admin/dashboard" class="btn btn-secondary me-2">Kembali ke Dashboard</a>
                <a href="/logout" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <p class="lead">Total Akun Pelanggan: **<?= count($users ?? []) ?>**</p>
        
        <table class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="table-primary">
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Kontak</th>
                    <th>Terdaftar Sejak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td>**<?= $user['id'] ?>**</td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['contact'] ?></td>
                        <td><?= date('d M Y', strtotime($user['created_at'] ?? date('Y-m-d'))) ?></td>
                        <td>
                            <a href="/admin/users/delete/<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna <?= $user['username'] ?>?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data pengguna pelanggan yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>