<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Levantar Inventario (Nuevo Ingreso)</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('inventario/guardar') ?>" method="POST">
                    
                    <?= csrf_field() ?> 

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_lote" class="form-label">ID de Lote</label>
                            <input type="text" class="form-control" id="id_lote" name="id_lote" placeholder="Ej. L-2026-10A" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipo_de_caja" class="form-label">Presentación (Cajas)</label>
                            <select class="form-select" id="tipo_de_caja" name="tipo_de_caja" required>
                                <option value="" selected disabled>Selecciona el tipo...</option>
                                <option value="12">Caja de 12 botellas</option>
                                <option value="24">Caja de 24 botellas</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cantidad_cajas" class="form-label">Cantidad de Cajas</label>
                            <input type="number" class="form-control" id="cantidad_cajas" name="cantidad_cajas" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar Lote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>