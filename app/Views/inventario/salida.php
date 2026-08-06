<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Registrar Arrendamiento (Salida)</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('arrendamientos/procesar') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Salida</label>
                            <input type="date" name="fecha_salida" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cliente / Observaciones</label>
                            <input type="text" name="cliente" class="form-control" placeholder="Nombre del cliente o evento..." required>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3 text-primary">Detalle de Productos a Arrendar</h6>

                    <!-- CONTENEDOR DINÁMICO (Igual que en la entrada) -->
                    <div id="contenedor-filas">
                        <div class="row g-2 align-items-center mb-3 fila-item border-bottom pb-3">
                            <div class="col-md-5">
                                <label class="form-label small text-muted">Presentación</label>
                                <select name="presentacion[]" class="form-select border-primary" required>
                                    <option value="" disabled selected>Selecciona presentación...</option>
                                    <?php foreach ($stock_global as $item): ?>
                                        <option value="<?= esc($item['presentacion_cupo']) ?>">
                                            <?= esc($item['presentacion_cupo']) ?> (Disponibles: <?= esc($item['total_cajas']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Cantidad de Cajas</label>
                                <input type="number" name="cantidad_cajas[]" class="form-control border-danger" placeholder="Ej. 10" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Precio Cobrado (Sin IVA)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">$</span>
                                    <input type="number" step="0.01" name="precio[]" class="form-control border-success" placeholder="0.00" required>
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
                            <i class="bi bi-plus-circle"></i> Agregar otro producto
                        </button>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-check2-circle"></i> Generar Salida
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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