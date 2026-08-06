<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-9">
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Arrendamiento</h5>
            </div>
            
            <div class="card-body p-4">
                <form action="<?= base_url('arrendamientos/procesarGeneral') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Salida</label>
                            <input type="date" name="fecha_arrendamiento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3 text-primary">Detalle del Inventario</h6>

                    <!-- TABLA DE SELECCIÓN MÚLTIPLE -->
                    <div class="table-responsive mb-4 border rounded p-2 bg-light">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Presentación / Cupo</th>
                                    <th class="text-center">Total Disponible</th>
                                    <th class="text-center" style="width: 200px;">Cajas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($stock_global)): ?>
                                    <?php foreach ($stock_global as $item): ?>
                                        <tr>
                                            <td class="fw-bold">
                                                <?= esc($item['presentacion_cupo']) ?>
                                                <!-- Pasamos el nombre oculto en un arreglo -->
                                                <input type="hidden" name="presentaciones[]" value="<?= esc($item['presentacion_cupo']) ?>">
                                            </td>
                                            <td class="text-center text-muted">
                                                <?= esc($item['total_cajas']) ?> cajas
                                            </td>
                                            <td>
                                                <!-- Casilla para ingresar la cantidad a descontar -->
                                                <input type="number" name="cantidades[]" class="form-control border-danger text-center" value="0" min="0" max="<?= esc($item['total_cajas']) ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No hay inventario disponible.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6 offset-md-6">
                            <label class="form-label fw-bold text-success">Total  </label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">$</span>
                                <input type="number" step="0.01" name="precio_arrendamiento" class="form-control border-success" placeholder="Ej. 12000.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="2" placeholder="..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-box-arrow-right"></i> Salida 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>