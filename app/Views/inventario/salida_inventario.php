<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body style="background-color: #f4f6f9; padding: 20px; font-family: Arial, sans-serif;">
    <div style="max-width: 650px; background: #ffffff; padding: 30px; margin: 0 auto; border-radius: 8px;">
        <h3 style="color: #333; margin-top: 0;">Detalle de Salida de Inventario</h3>
        <p style="color: #555; margin-bottom: 30px;">A continuación se presenta el detalle del movimiento registrado en el sistema:</p>

        <?php 
            // Calcular dinámicamente las piezas por caja para evitar errores de clave de arreglo
            $partesLote = explode('-', $codigo_lote ?? '');
            $totalBotellas = isset($partesLote[1]) ? (int)$partesLote[1] : 0;
            $cajas = (int)($cantidad_cajas ?? 1);
            $piezasPorCaja = ($cajas > 0 && $totalBotellas > 0) ? ($totalBotellas / $cajas) : 12;
        ?>

        <!-- TABLA CON ESTILO IDÉNTICO AL FORMATO SOLICITADO -->
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 15px; color: #000;">
            <thead>
                <tr>
                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: left;">Presentación/Cupo</th>
                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: center;">Cantidad por cartón</th>
                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: right;">Costo por cartón (sin iva)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 12px 8px; text-align: left; border-bottom: 1px solid #ddd;"><?= esc($presentacion_cupo) ?></td>
                    <td style="padding: 12px 8px; text-align: center; border-bottom: 1px solid #ddd;"><?= esc($piezasPorCaja) ?></td>
                    <td style="padding: 12px 8px; text-align: right; border-bottom: 1px solid #ddd;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family: Arial, sans-serif;">
                            <tr>
                                <td style="text-align: left; width: 20px;">$</td>
                                <td style="text-align: right;"><?= number_format($costo_por_carton, 2) ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 40px; font-size: 12px; color: #888; text-align: center; border-top: 1px solid #eee; padding-top: 15px;">
            <p>ID Interno: <?= esc($id_interno) ?> | Código de Lote: <?= esc($codigo_lote) ?></p>
        </div>
    </div>
</body>
</html>