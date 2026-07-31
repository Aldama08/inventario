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
                <h5 class="mb-0">Registrar Arrendamiento Global</h5>
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
                    <h6 class="fw-bold mb-3">Detalle del Inventario a Arrendar</h6>

                    <div class="row g-3 mb-4 p-3 border rounded bg-light align-items-center">
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold">Selecciona la Presentación</label>
                            <select name="presentacion_cupo" class="form-select border-primary" required>
                                <option value="" disabled selected>-- Elige qué producto se va a arrendar --</option>
                                <?php foreach ($stock_global as $item): ?>
                                    <option value="<?= esc($item['presentacion_cupo']) ?>">
                                        <?= esc($item['presentacion_cupo']) ?> (Stock Total: <?= esc($item['total_cajas']) ?> cajas disponibles)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold text-danger">Cantidad Total de Cajas</label>
                            <input type="number" name="cajas_a_arrendar" class="form-control border-danger" placeholder="Ej. 150" min="1" required>
                            <div class="form-text">El sistema descontará automáticamente de los lotes más antiguos.</div>
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold text-success">Precio Total Cobrado (Sin IVA)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white">$</span>
                                <input type="number" step="0.01" name="precio_arrendamiento" class="form-control border-success" placeholder="Ej. 12000.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="observaciones" class="form-label fw-bold">Observaciones del Arrendamiento</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="2" placeholder="Cliente, evento, destino..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-box-arrow-right"></i> Confirmar Salida Global
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>