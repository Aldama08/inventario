<?php

namespace App\Models;

use CodeIgniter\Model;

class Inventario extends Model
{
    protected $table      = 'inventario';
    protected $primaryKey = 'id_interno';
    
    // protected $useAutoIncrement = false; 

    protected $allowedFields = [
        'codigo_lote',
        'presentacion_cupo',
        'cantidad_cajas',
        'costo_por_carton',
        'fecha_ingreso',
        'observaciones'
    ];
}