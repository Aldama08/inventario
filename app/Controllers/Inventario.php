<?php

namespace App\Controllers;

use App\Models\Inventario as InventarioModel;

class Inventario extends BaseController
{
    // Carga la vista de la lista de inventario
    public function lista()
    {
        $inventarioModel = new \App\Models\Inventario();
        $data['inventario'] = $inventarioModel->findAll();
        return view('inventario/lista', $data);
    }

    // Carga el formulario para levantar inventario
    public function entrada()
    {
        return view('inventario/entrada');
    }

    // Procesa el formulario (POST)
    public function guardar()
    {
        $inventarioModel = new InventarioModel();

        $datos = [
            'codigo_lote'          => $this->request->getPost('id_lote'),
            'tipo_de_caja'     => $this->request->getPost('tipo_de_caja'),
            'cantidad_cajas'   => $this->request->getPost('cantidad_cajas'),
            'fecha_ingreso'    => $this->request->getPost('fecha_ingreso'),
            'observaciones'    => $this->request->getPost('observaciones')
        ];

        $inventarioModel->insert($datos);

        return redirect()->to('inventario')->with('mensaje', 'El lote se ha registrado');
    }
}