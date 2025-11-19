<!DOCTYPE html>
<html>
<head><title>Login Admin / Pelanggan</title></head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;">
     <form action="/login/process" method="post" style="border:1px solid #ccc; padding:20px; border-radius:5px;">
     <h2>Login Admin / Pelanggan</h2>
        
         <?= csrf_field() ?>
        
     <?php if(session()->getFlashdata('msg')):?>
     <div style="color:red;"><?= session()->getFlashdata('msg') ?></div>
     <?php endif;?>
        <?php if(session()->getFlashdata('error')):?>
     <div style="color:red;"><?= session()->getFlashdata('error') ?></div>
     <?php endif;?>
        
     <p>Username: <input type="text" name="username"></p>
     <p>Password: <input type="password" name="password"></p>
     <button type="submit">Masuk</button>
        <p><small>Belum punya akun? <a href="/register">Daftar Akun Pelanggan</a></small></p>
     </form>
</body>
</html>