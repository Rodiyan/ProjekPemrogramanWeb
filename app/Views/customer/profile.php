<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title><?= $title ?? 'Profil Pelanggan' ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f4f4f4;
            }

            .container {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>

    <body>
        <div class="container mt-5">
            <div class="d-flex justify-content-between mb-4 align-items-center">
                <h1>Halo, **<?= $user['name'] ?>**!</h1>
                <div>
                    <a href="/" class="btn btn-secondary me-2">Kembali ke Katalog</a>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">Detail Akun</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Nama Lengkap: **<?= $user['name'] ?>**</li>
                            <li class="list-group-item">Email: **<span id="user-email"><?= $user['username'] ?? 'Tidak tersedia' ?></span>**</li>
                            <li class="list-group-item">Kontak: **<span id="user-contact"><?= $user['contact'] ?? 'Tidak tersedia' ?></span>**</li>
                            <li class="list-group-item">Status: <span
                                    class="badge bg-success"><?= ucfirst($user['role']) ?></span></li>
                        </ul>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">Ubah Kontak</div>
                        <div class="card-body">
                            <form id="update-contact-form">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>"> 
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token_field"> 
                                <div class="mb-3">
                                    <label for="new_contact" class="form-label">Nomor Kontak Baru</label>
                                    <input type="text" class="form-control" id="new_contact" name="contact" value="<?= $user['contact'] ?>" required>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">Simpan Kontak Baru</button>
                            </form>
                            <div id="update-message" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <h2>Riwayat Pesanan Anda</h2>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Produk</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="orders-list">
                            <tr>
                                <td colspan="5" class="text-center">Memuat riwayat pesanan...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            // START: Penambahan API KEY dari PHP
            const API_KEY = "<?= $apiKey ?? '' ?>"; // Ambil API Key dari Controller
            // END: Penambahan API KEY

            // Pastikan ID pengguna tersedia dari PHP
            const currentUserId = <?= json_encode($user['id'] ?? null) ?>;

            // =======================================================
            // 1. FUNGSI UNTUK MEMUAT RIWAYAT PESANAN (via GET API)
            // =======================================================

            function loadOrders() {
                const ordersListBody = document.getElementById('orders-list');
                const apiUrl = `/api/order?user_id=${currentUserId}`;
                
                if (!currentUserId) {
                    ordersListBody.innerHTML = `<tr><td colspan="5" class="text-center text-warning">ID Pengguna tidak ditemukan.</td></tr>`;
                    return;
                }

                ordersListBody.innerHTML = `<tr><td colspan="5" class="text-center text-info">Memuat riwayat pesanan...</td></tr>`;

                fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'X-API-KEY': API_KEY, // Kirim API Key
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    // Cek jika API Key salah (401) atau error server (500)
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    ordersListBody.innerHTML = ''; 
                    const orders = data.orders || [];

                    if (data.status === 'success' && orders.length > 0) {
                        orders.forEach(order => {
                            const formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(order.total_price);
                            
                            const dateObj = new Date(order.created_at);
                            const formattedDate = dateObj.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            const row = `
                                <tr>
                                    <td>**${order.id}**</td>
                                    <td>${order.product_name || 'N/A'} (x${order.qty})</td>
                                    <td>${formattedPrice}</td>
                                    <td><span class="badge bg-secondary">${order.status}</span></td>
                                    <td>${formattedDate}</td>
                                </tr>
                            `;
                            ordersListBody.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        ordersListBody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center">Anda belum memiliki riwayat pesanan.</td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error("Gagal memuat pesanan:", error);
                    ordersListBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-danger">Gagal memuat data pesanan: ${error.message}</td>
                        </tr>
                    `;
                });
            }

            // =======================================================
            // 2. EVENT LISTENER UNTUK UPDATE KONTAK (via POST API)
            // =======================================================

            document.getElementById('update-contact-form').addEventListener('submit', function(e) {
                e.preventDefault(); 

                const form = e.target;
                const formData = new FormData(form);
                const updateMessage = document.getElementById('update-message');
                const displayContactElement = document.getElementById('user-contact'); 
                
                // Tambahkan CSRF Token ke FormData sebelum dikirim (Wajib untuk POST di CI4)
                const csrfTokenField = document.getElementById('csrf_token_field');
                formData.append(csrfTokenField.name, csrfTokenField.value);

                updateMessage.innerHTML = '<span class="text-info">Memperbarui...</span>';
                
                fetch('/api/user/update-contact', {
                    method: 'POST',
                    headers: {
                        'X-API-KEY': API_KEY, // Kirim API Key
                    },
                    body: formData 
                })
                .then(response => {
                    // Cek jika API Key salah (401) atau error CSRF (403)
                    if (!response.ok) {
                         return response.json().then(errorData => {
                            throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        updateMessage.innerHTML = `<span class="text-success">${data.message}</span>`;
                        
                        // Perbarui tampilan di Detail Akun
                        const updatedContact = form.querySelector('#new_contact').value;
                        if (displayContactElement) {
                            displayContactElement.textContent = updatedContact;
                        }
                        
                        // Logika Refresh CSRF Token (Jika CI4 mengirim token baru di response header)
                        // Karena kita menggunakan FormData, CI4 biasanya tidak otomatis merespons dengan JSON hash baru.
                        // Namun, jika Anda menggunakan logic CSRF refresh, Anda harus ambil hash baru di sini.
                        // Untuk saat ini, kita biarkan saja (fokus ke fungsionalitas utama).
                        
                    } else {
                        updateMessage.innerHTML = `<span class="text-danger">Gagal: ${data.message || 'Terjadi kesalahan pada server.'}</span>`;
                    }
                })
                .catch(error => {
                    console.error('Error saat update:', error);
                    updateMessage.innerHTML = `<span class="text-danger">Koneksi gagal atau Akses Ditolak: ${error.message}</span>`;
                });
            });

            // Panggil fungsi loadOrders saat halaman selesai dimuat
            if (document.getElementById('orders-list')) {
                loadOrders();
            }
        </script>
    </body>
</html>