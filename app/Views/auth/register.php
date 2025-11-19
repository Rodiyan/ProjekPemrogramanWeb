<!DOCTYPE html>
<html>
<head><title>Registrasi Pelanggan</title></head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;">
    <form action="/register/process" method="post" style="border:1px solid #ccc; padding:20px; border-radius:5px;">
        <h2>Daftar Akun Baru</h2>
        <p>Nama Lengkap: <input type="text" name="name" required></p>
        <p>No HP/Kontak: <input type="text" name="contact" required></p>
        <p>Username (Email): <input type="text" name="username" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <button type="submit">Daftar</button>
        <p><small>Sudah punya akun? <a href="/login">Login di sini</a></small></p>
    </form>
</body>
</html>