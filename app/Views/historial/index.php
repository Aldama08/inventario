<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col">
        <h2>Historial de Movimientos</h2>
        <p class="text-muted">Registro de auditoría de entradas y salidas de lotes.</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr>
                    <th>Fecha y Hora</th>
                    <th>ID Lote</th>
                    <th>Tipo Movimiento</th>
                    <th>Cantidad (Cajas)</th>
                    <th>Usuario Responsable</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= date('Y-m-d H:i') ?></td>
                    <td>L-2026-10A</td>
                    <td class="text-success"><i class="bi bi-arrow-down-left-circle"></i> Entrada</td>
                    <td>+50 (Caja 12)</td>
                    <td>Admin</td>
                </tr>
                <tr>
                    <td><?= date('Y-m-d H:i', strtotime('-2 hours')) ?></td>
                    <td>L-2026-09C</td>
                    <td class="text-danger"><i class="bi bi-arrow-up-right-circle"></i> Salida</td>
                    <td>-10 (Caja 24)</td>
                    <td>Admin</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>