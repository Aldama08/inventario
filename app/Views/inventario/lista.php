<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Inventario</h2>
    <a href="<?= base_url('inventario/entrada') ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Ingreso</a>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('mensaje') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 80px;">ID</th> 
                        <th>ID Lote</th> 
                        <th>Presentación / Cupo</th>
                        <th>Cajas Disponibles</th>
                        <th>Total Botellas</th>
                        <th class="text-center" style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventario) && is_array($inventario)): ?>
                        <?php foreach ($inventario as $lote): ?>
                            <?php 
                                // Extrae el total de botellas desde el código de lote
                                $partesLote = explode('-', $lote['codigo_lote'] ?? '');
                                $totalBotellas = isset($partesLote[1]) ? (int)$partesLote[1] : 0;
                                
                                // Calcula cuántas piezas vienen por caja
                                $cajas = (int)($lote['cantidad_cajas'] ?? 1);
                                $piezasPorCaja = ($cajas > 0) ? ($totalBotellas / $cajas) : 12;
                            ?>
                            <tr>
                                <td><strong><?= esc($lote['id_interno']) ?></strong></td>
                                
                                <td><?= esc($lote['codigo_lote']) ?></td>
                                
                                <td>
                                    <strong>Presentación: <?= esc($lote['presentacion_cupo']) ?></strong>
                                    <br>
                                    <?php if ($piezasPorCaja == 24): ?>
                                        <span class="badge bg-dark">24 Botellas/Caja</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">12 Botellas/Caja</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= esc($lote['cantidad_cajas']) ?></td>
                                
                                <td><?= esc($totalBotellas) ?></td>
                                
                                <td class="text-center">
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-box-seam"></i> En Almacén
                                        </span>
                                    </div>

                                    <!-- Subir PDF Base (Admin) -->
                                    <a href="<?= base_url('inventario/subir/' . esc($lote['id_interno'])) ?>" class="btn btn-sm btn-warning text-white" title="Subir PDF">
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                    </a>

                                    <!-- Panel de Gestión y Firma (Cliente) -->
                                    <a href="<?= base_url('inventario/documento/' . esc($lote['id_interno'])) ?>" class="btn btn-sm btn-success" title="Panel de Gestión y Firma (Cliente)">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </a>

                                    <!-- Editar -->
                                    <a href="<?= base_url('inventario/editar/' . esc($lote['id_interno'])) ?>" class="btn btn-sm btn-outline-primary" title="Editar Lote">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Eliminar -->
                                    <a href="<?= base_url('inventario/eliminar/' . esc($lote['id_interno'])) ?>" class="btn btn-sm btn-outline-danger" title="Dar de baja" onclick="return confirm('¿Seguro que deseas eliminar este lote?');">
                                        <i class="bi bi-trash"></i>
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