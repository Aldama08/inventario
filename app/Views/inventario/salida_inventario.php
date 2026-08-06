<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Salida</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 650px; background-color: #ffffff; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center; margin-bottom: 5px;">Reporte de Salida</h2>
        <h4 style="color: #666666; text-align: center; margin-top: 0;">Formato de Arrendamiento</h4>
        
        <p style="color: #333; font-size: 14px;"><strong>Fecha de Salida:</strong> <?= esc($fecha ?? date('d/m/Y')) ?></p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-family: Arial, sans-serif; font-size: 14px;">
            <thead>
                <tr>
                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: left;">Presentación / Cupo</th>
                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: center;">Cajas Arrendadas</th>
                    <th style="border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 12px 8px; text-align: right;">Costo Cobrado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items) && is_array($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="padding: 12px 8px; text-align: left; border-bottom: 1px solid #ddd;">
                                <?= esc($item['presentacion']) ?>
                            </td>
                            <td style="padding: 12px 8px; text-align: center; border-bottom: 1px solid #ddd;">
                                <?= esc($item['cantidad_carton']) ?>
                            </td>
                            <td style="padding: 12px 8px; text-align: right; border-bottom: 1px solid #ddd;">
                                $<?= number_format((float)$item['costo_carton'], 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px; color: #888;">No hay productos</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 40px; border-top: 1px dashed #ccc; padding-top: 20px; color: #888888; font-size: 12px; text-align: center;">
            <p>Comprobante.</p>
        </div>
    </div>
</body>
</html>