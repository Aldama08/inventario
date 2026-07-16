<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventario Actual</h2>
    <a href="<?= base_url('inventario/entrada') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Ingreso</a>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('mensaje') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 80px;">ID</th> <th>ID Lote</th> <th>Tipo de Caja</th>
                        <th>Cajas Disponibles</th>
                        <th>Total Botellas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventario) && is_array($inventario)): ?>
                        <?php foreach ($inventario as $lote): ?>
                            <tr>
                                <td><strong><?= esc($lote['id_interno']) ?></strong></td>
                                
                                <td><?= esc($lote['codigo_lote']) ?></td>
                                
                                <td>
                                    <?php if ($lote['tipo_de_caja'] == 24): ?>
                                        <span class="badge bg-dark">24 Botellas</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">12 Botellas</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($lote['cantidad_cajas']) ?></td>
                                
                                <td><?= esc($lote['cantidad_cajas'] * $lote['tipo_de_caja']) ?></td>
                                
                                <td>
                                    <a href="<?= base_url('inventario/editar/' . esc($lote['id_interno'])) ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('inventario/eliminar/' . esc($lote['id_interno'])) ?>" class="btn btn-sm btn-outline-danger" title="Dar de baja" onclick="return confirm('¿Seguro que deseas eliminar este lote?');">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No hay lotes registrados en el inventario.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>