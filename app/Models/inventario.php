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
        'tipo_de_caja',
        'cantidad_cajas',
        'fecha_ingreso',
        'observaciones'
    ];
}