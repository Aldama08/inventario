<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario - Botellas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>"><i class="bi bi-box-seam"></i> InvenBotellas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Menú Principal</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('inventario/entrada') ?>">Levantar Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('inventario') ?>">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cotizaciones') ?>">Cotizaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('historial') ?>">Historial</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>