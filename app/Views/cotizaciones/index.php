<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Cotizaciones</h2>
    <button class="btn btn-primary"><i class="bi bi-file-earmark-plus"></i> Nueva Cotización</button>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Detalle (Cajas)</th>
                    <th>Monto Estimado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>COT-001</td>
                    <td>Distribuidora Central</td>
                    <td>10 x Cajas (24)</td>
                    <td>$4,500.00</td>
                    <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                    <td>
                        <button class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Ver</button>
                        <button class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Aprobar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>