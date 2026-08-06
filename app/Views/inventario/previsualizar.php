<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Previsualización de Salida</h5>
                <a href="<?= base_url('inventario') ?>" class="btn btn-sm btn-outline-light">Regresar</a>
            </div>
            
            <div class="card-body">
                <!-- <p class="text-muted mb-4">Así es exactamente como el destinatario verá la tabla en su correo electrónico:</p> -->

                <?php 
                    // Extraer total de botellas del código de lote (ej. "260720-000120" -> 120)
                    $partesLote = explode('-', $lote['codigo_lote'] ?? '');
                    $totalBotellas = isset($partesLote[1]) ? (int)$partesLote[1] : 0;
                    
                    // Calcular piezas por caja
                    $cajas = (int)($lote['cantidad_cajas'] ?? 1);
                    $piezasPorCaja = ($cajas > 0 && $totalBotellas > 0) ? ($totalBotellas / $cajas) : 12;
                ?>

                <!-- TABLA ESTILO IMAGEN CON BORDES SUPERIOR E INFERIOR -->
                <div class="p-4 bg-white border rounded mb-4" style="overflow-x: auto;">
                    <table style="width: 100%; max-width: 600px; border-collapse: collapse; font-family: Arial, sans-serif; margin: 0 auto; font-size: 15px; color: #000;">
                        <thead>
                            <tr>
                                <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: left;">Presentación/Cupo</th>
                                <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: center;">Cantidad por cartón</th>
                                <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: right;">Costo por cartón (sin iva)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px 8px; text-align: left; border-bottom: 1px solid #ddd;"><?= esc($lote['presentacion_cupo']) ?></td>
                                <td style="padding: 12px 8px; text-align: center; border-bottom: 1px solid #ddd;"><?= esc($piezasPorCaja) ?></td>
                                <td style="padding: 12px 8px; text-align: right; border-bottom: 1px solid #ddd;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="text-align: left; width: 20px;">$</td>
                                            <td style="text-align: right;"><?= number_format($lote['costo_por_carton'], 2) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- FORMULARIO DE ENVÍO -->
                <form action="<?= base_url('inventario/enviarCorreo') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_interno" value="<?= esc($lote['id_interno']) ?>">
                    
                    <div class="mb-3">
                        <label for="correo_destino" class="form-label fw-bold">Enviar a (Correo Electrónico):</label>
                        <input type="email" class="form-control" id="correo_destino" name="correo_destino" placeholder="ejemplo@empresa.com" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Enviar Formato
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>