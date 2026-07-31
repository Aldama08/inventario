<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Levantar Inventario (Nuevo Ingreso)</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('inventario/guardar') ?>" method="POST">
                    <?= csrf_field() ?>

                    <!-- CABECERA DEL LOTE -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ID (Interno)</label>
                            <input type="text" class="form-control bg-light" value="Autogenerado por el sistema" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">ID de Lote</label>
                            <input type="text" class="form-control bg-light" value="Se generará automáticamente" readonly>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Detalle de Presentaciones / Cajas</h6>

                    <!-- CONTENEDOR DINÁMICO DE FILAS -->
                    <div id="contenedor-filas">
                        <div class="row g-2 align-items-center mb-3 fila-item border-bottom pb-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Tipo de Caja (Piezas)</label>
                                <select name="tipo_caja[]" class="form-select" required>
                                    <option value="" disabled selected>Selecciona el tipo...</option>
                                    <option value="12">Caja con 12 Piezas</option>
                                    <option value="24">Caja con 24 Piezas</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Presentación / Cupo</label>
                                <select name="presentacion[]" class="form-select" required>
                                    <option value="" disabled selected>Selecciona presentación...</option>
                                    <option value="Botella 900ml">Botella 900ml</option>
                                    <option value="Botella 1L">Botella 1L</option>
                                    <option value="Lata 355ml">Lata 355ml</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted">Cantidad de Cajas</label>
                                <input type="number" name="cantidad_cajas[]" class="form-control" placeholder="Ej. 10" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Costo por Cartón (Sin IVA)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="costo_carton[]" class="form-control" placeholder="55.00" required>
                                </div>
                            </div>
                            <div class="col-md-1 text-center pt-4">
                                <button type="button" class="btn btn-outline-danger btn-eliminar" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BOTÓN AGREGAR FILA -->
                    <div class="mb-4">
                        <button type="button" id="btn-agregar" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-plus-circle"></i> Agregar otra presentación
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="2" placeholder="Detalles o notas"></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-down"></i> Registrar Lote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT PARA AGREGAR / QUITAR FILAS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('contenedor-filas');
    const btnAgregar = document.getElementById('btn-agregar');

    function actualizarBotonesEliminar() {
        const filas = contenedor.querySelectorAll('.fila-item');
        filas.forEach(fila => {
            const btn = fila.querySelector('.btn-eliminar');
            if (btn) btn.disabled = (filas.length === 1);
        });
    }

    btnAgregar.addEventListener('click', function () {
        const primeraFila = contenedor.querySelector('.fila-item');
        const nuevaFila = primeraFila.cloneNode(true);

        // Limpiar inputs
        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        contenedor.appendChild(nuevaFila);
        actualizarBotonesEliminar();
    });

    contenedor.addEventListener('click', function (e) {
        if (e.target.closest('.btn-eliminar')) {
            const filas = contenedor.querySelectorAll('.fila-item');
            if (filas.length > 1) {
                e.target.closest('.fila-item').remove();
                actualizarBotonesEliminar();
            }
        }
    });

    actualizarBotonesEliminar();
});
</script>
<?= $this->endSection() ?>