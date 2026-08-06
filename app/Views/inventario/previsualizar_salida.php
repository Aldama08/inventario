<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Previsualización de Formato de Salida</h5>
                <a href="<?= base_url('inventario') ?>" class="btn btn-sm btn-outline-light">Ir al Inventario</a>
            </div>
            
            <div class="card-body">
                <p class="text-muted mb-3">Revisa los datos del arrendamiento procesado e ingresa el correo del cliente:</p>

                <form action="<?= base_url('arrendamientos/enviarCorreoSalida') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="p-3 bg-white border rounded mb-3" style="overflow-x: auto;">
                        <table class="table align-middle" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 15px; color: #000;">
                            <thead>
                                <tr>
                                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px; text-align: left;">Presentación / Cupo</th>
                                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px; text-align: center;">Cajas Arrendadas</th>
                                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px; text-align: right;">Precio Cobrado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                                                <input type="text" name="presentacion[]" class="form-control form-control-sm bg-light" value="<?= esc($item['presentacion']) ?>" readonly>
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                                                <input type="number" name="cantidad_carton[]" class="form-control form-control-sm text-center bg-light" value="<?= esc($item['cantidad']) ?>" readonly>
                                            </td>
                                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" name="costo_carton[]" class="form-control text-end bg-light" value="<?= esc($precio_total ?? 0) ?>" readonly>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label for="correo_destino" class="form-label fw-bold">Correo Electrónico del Cliente:</label>
                        <input type="email" class="form-control" id="correo_destino" name="correo_destino" placeholder="cliente@ejemplo.com" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Enviar Formato al Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>