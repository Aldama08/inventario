<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Total de lotes activos
        $lotesActivos = $db->table('inventario')->countAllResults();

        // 2. Obtener todos los registros de inventario
        $inventario = $db->table('inventario')->get()->getResultArray();

        $cajas12 = 0;
        $cajas24 = 0;

        // 3. Procesar cada lote para saber si es de 12 o 24 botellas
        foreach ($inventario as $lote) {
            $cantidadCajas = (int) ($lote['cantidad_cajas'] ?? 0);
            $codigoLote    = $lote['codigo_lote'] ?? '';

            // Extrae el total de botellas del código de lote (ej. "260720-000120" -> 120)
            $partesLote = explode('-', $codigoLote);
            $totalBotellas = isset($partesLote[1]) ? (int) $partesLote[1] : 0;

            // Calcula cuántas piezas tiene cada caja
            $piezasPorCaja = ($cantidadCajas > 0) ? ($totalBotellas / $cantidadCajas) : 12;

            if ($piezasPorCaja == 24) {
                $cajas24 += $cantidadCajas;
            } else {
                $cajas12 += $cantidadCajas;
            }
        }

        $movimientosHoy = 0;

        $data = [
            'lotes_activos'   => $lotesActivos,
            'cajas_12'        => $cajas12,
            'cajas_24'        => $cajas24,
            'movimientos_hoy' => $movimientosHoy
        ];

        return view('dashboard', $data);
    }
}