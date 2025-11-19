<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;">
    <form action="/login/auth" method="post" style="border:1px solid #ccc; padding:20px; border-radius:5px;">
        <h2>Login Admin</h2>
        <?php if(session()->getFlashdata('msg')):?>
            <div style="color:red;"><?= session()->getFlashdata('msg') ?></div>
        <?php endif;?>
        <p>Username: <input type="text" name="username"></p>
        <p>Password: <input type="password" name="password"></p>
        <button type="submit">Masuk</button>
    </form>
</body>
</html>