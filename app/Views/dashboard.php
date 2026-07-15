<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h2>Panel de Control</h2>
        <p class="text-muted">Resumen general del inventario y operaciones.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-boxes"></i> Lotes Activos</h5>
                <p class="card-text display-6"><?= esc($lotes_activos) ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-box"></i> Cajas de 12</h5>
                <p class="card-text display-6"><?= esc($cajas_12) ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-box-fill"></i> Cajas de 24</h5>
                <p class="card-text display-6"><?= esc($cajas_24) ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-clock-history"></i> Movimientos Hoy</h5>
                <p class="card-text display-6"><?= esc($movimientos_hoy) ?></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>