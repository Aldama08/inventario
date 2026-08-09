<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvenBotellas - Iniciar Sesión</title>
    <!-- Agrega aquí tu enlace a Tailwind o CSS si lo utilizas -->
    <style>
        body { font-family: Arial, sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-weight: bold; }
        input { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: .75rem; background-color: #2563eb; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #1d4ed8; }
        .alert { color: white; background-color: #dc2626; padding: .5rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
    </style>
</head>
<body>

<div class="login-box">
    <h2 style="text-align: center; margin-bottom: 1.5rem;">InvenBotellas</h2>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/procesarLogin') ?>" method="post">
        <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Entrar al Sistema</button>
    </form>
</div>

</body>
</html>