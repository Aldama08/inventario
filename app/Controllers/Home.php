<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Contar Lotes Activos (Filas totales en la tabla inventario)
        $lotesActivos = $db->table('inventario')->countAllResults();

        // 2. Sumar Cajas de 12
        $cajas12 = $db->table('inventario')
                      ->selectSum('cantidad_cajas')
                      ->where('tipo_de_caja', 12)
                      ->get()
                      ->getRow()
                      ->cantidad_cajas ?? 0; // Si es null, pone 0

        // 3. Sumar Cajas de 24
        $cajas24 = $db->table('inventario')
                      ->selectSum('cantidad_cajas')
                      ->where('tipo_de_caja', 24)
                      ->get()
                      ->getRow()
                      ->cantidad_cajas ?? 0;

        // 4. Movimientos de Hoy (Por ahora dejamos un valor simulado de 0 hasta que manejes la tabla historial)
        $movimientosHoy = 0; 

        // Empaquetamos todo en el arreglo $data para inyectarlo a la vista
        $data = [
            'lotes_activos'   => $lotesActivos,
            'cajas_12'        => $cajas12,
            'cajas_24'        => $cajas24,
            'movimientos_hoy' => $movimientosHoy
        ];

        return view('dashboard', $data);
    }
}