<?php

namespace App\Controllers;

use App\Models\Inventario as InventarioModel;

class Inventario extends BaseController
{

    public function arrendamientoGeneral()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT presentacion_cupo, SUM(cantidad_cajas) as total_cajas FROM inventario WHERE cantidad_cajas > 0 GROUP BY presentacion_cupo");
        $data['stock_global'] = $query->getResultArray();

        return view('inventario/arrendamiento_general', $data);
    }

    public function procesarArrendamientoGeneral()
    {
        $presentacion = $this->request->getPost('presentacion_cupo');
        $cajas_solicitadas = (int) $this->request->getPost('cajas_a_arrendar');
        $precio_arrendamiento = $this->request->getPost('precio_arrendamiento');
        $observaciones = $this->request->getPost('observaciones');

        $inventarioModel = new InventarioModel();

        $lotes = $inventarioModel->where('presentacion_cupo', $presentacion)
                                 ->where('cantidad_cajas >', 0)
                                 ->orderBy('fecha_ingreso', 'ASC')
                                 ->orderBy('id_interno', 'ASC')
                                 ->findAll();

        $total_disponible = 0;
        foreach ($lotes as $l) {
            $total_disponible += (int)$l['cantidad_cajas'];
        }

        if ($cajas_solicitadas > $total_disponible) {
            return redirect()->back()->with('error', "No hay suficientes cajas. Solicitaste $cajas_solicitadas pero solo hay $total_disponible en total.");
        }

        $cajas_restantes_por_descontar = $cajas_solicitadas;

        foreach ($lotes as $lote) {
            if ($cajas_restantes_por_descontar <= 0) {
                break; 
            }

            $stock_lote = (int)$lote['cantidad_cajas'];

            if ($stock_lote <= $cajas_restantes_por_descontar) {
                $cajas_restantes_por_descontar -= $stock_lote;
                $inventarioModel->update($lote['id_interno'], ['cantidad_cajas' => 0]);
            } else {
                $nuevo_stock = $stock_lote - $cajas_restantes_por_descontar;
                $inventarioModel->update($lote['id_interno'], ['cantidad_cajas' => $nuevo_stock]);
                $cajas_restantes_por_descontar = 0; // Pedido completado
            }
        }

        return redirect()->to('inventario')->with('mensaje', "Arrendamiento de $cajas_solicitadas cajas de $presentacion registrado exitosamente.");
    }

    public function procesarArrendamiento()
    {
        $id_interno = $this->request->getPost('id_interno');
        $cajas_a_arrendar = (int) $this->request->getPost('cajas_a_arrendar');
        $precio_arrendamiento = $this->request->getPost('precio_arrendamiento');
        $observaciones = $this->request->getPost('observaciones');

        $inventarioModel = new InventarioModel();
        $lote = $inventarioModel->find($id_interno);

        if ($lote) {
            $stock_actual = (int) $lote['cantidad_cajas'];

            // Validar que no intente arrendar más de lo que hay
            if ($cajas_a_arrendar > $stock_actual) {
                return redirect()->back()->with('error', 'No puedes arrendar más cajas de las que hay disponibles en el lote.');
            }

            // Calculamos el nuevo stock restando lo arrendado
            $nuevo_stock = $stock_actual - $cajas_a_arrendar;

            // Actualizamos el lote en la base de datos
            $inventarioModel->update($id_interno, [
                'cantidad_cajas' => $nuevo_stock
            ]);

            // NOTA: Si en el futuro creas una tabla de 'historial_arrendamientos',
            // aquí es donde harías el insert con el $precio_arrendamiento y las $observaciones.

            return redirect()->to('inventario')->with('mensaje', 'Arrendamiento registrado y descontado del inventario exitosamente.');
        }

        return redirect()->to('inventario')->with('error', 'Ocurrió un error al procesar el arrendamiento.');
    }

    public function lista()
    {
        $inventarioModel = new InventarioModel();
        $data['inventario'] = $inventarioModel->findAll();

        return view('inventario/lista', $data);
    }

    public function entrada()
    {
        return view('inventario/entrada');
    }

    public function guardar()
    {
        $inventarioModel = new InventarioModel();

        $fechaIngreso  = $this->request->getPost('fecha_ingreso');
        $observaciones = $this->request->getPost('observaciones');

        $tiposCaja      = $this->request->getPost('tipo_caja') ?? [];
        $presentaciones = $this->request->getPost('presentacion') ?? [];
        $cantidades     = $this->request->getPost('cantidad_cajas') ?? [];
        $costos         = $this->request->getPost('costo_carton') ?? [];

        $prefijoLote = date('dmy', strtotime($fechaIngreso));

        for ($i = 0; $i < count($tiposCaja); $i++) {
            $cantCajas = (int)$cantidades[$i];
            $piezas    = (int)$tiposCaja[$i];
            $totalBotellas = $cantCajas * $piezas;

            $codigoLote = $prefijoLote . '-' . str_pad($totalBotellas, 6, '0', STR_PAD_LEFT);

            $data = [
                'codigo_lote'       => $codigoLote,
                'presentacion_cupo' => $presentaciones[$i],
                'cantidad_cajas'    => $cantCajas,
                'costo_por_carton'  => $costos[$i],
                'fecha_ingreso'     => $fechaIngreso,
                'observaciones'     => $observaciones
            ];

            $inventarioModel->insert($data);
        }

        return redirect()->to('inventario')->with('mensaje', 'Lote(s) registrado(s) correctamente.');
    }

    // --- CARGA DE VISTAS PARA ARCHIVOS ---
    
    public function subirArchivo($id_interno)
    {
        $inventarioModel = new InventarioModel();
        $data['lote'] = $inventarioModel->find($id_interno);

        if (!$data['lote']) {
            return redirect()->to('inventario')->with('error', 'Registro no encontrado.');
        }

        return view('inventario/subir_archivo', $data);
    }

    public function panelDocumento($id_interno)
    {
        $inventarioModel = new InventarioModel();
        $data['lote'] = $inventarioModel->find($id_interno);

        if (!$data['lote']) {
            return redirect()->to('inventario')->with('error', 'Registro no encontrado.');
        }

        return view('inventario/panel_documento', $data);
    }

    // --- PROCESAMIENTO DE SUBIDA DE PDFS ---

    public function procesarArchivo()
    {
        $id_interno = $this->request->getPost('id_interno');

        $reglas = [
            'archivo_adjunto' => [
                'label' => 'Archivo Adjunto',
                'rules' => 'uploaded[archivo_adjunto]|max_size[archivo_adjunto,4096]|ext_in[archivo_adjunto,pdf]',
            ],
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->with('error', $this->validator->listErrors());
        }

        $archivo = $this->request->getFile('archivo_adjunto');

        if ($archivo->isValid() && !$archivo->hasMoved()) {
            $nuevoNombre = $archivo->getRandomName();

            // Guarda en public/uploads/
            $archivo->move(FCPATH . 'uploads', $nuevoNombre);

            $inventarioModel = new InventarioModel();
            $inventarioModel->update($id_interno, [
                'archivo_original' => $nuevoNombre
            ]);

            return redirect()->to('inventario')->with('mensaje', 'Archivo base guardado correctamente en el proyecto.');
        }

        return redirect()->back()->with('error', 'Error al subir el archivo.');
    }

    public function subirFirmado()
    {
        $id_interno = $this->request->getPost('id_interno');

        $reglas = [
            'archivo_firmado' => [
                'label' => 'Documento Firmado',
                'rules' => 'uploaded[archivo_firmado]|max_size[archivo_firmado,4096]|ext_in[archivo_firmado,pdf]',
            ],
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->with('error', $this->validator->listErrors());
        }

        $archivo = $this->request->getFile('archivo_firmado');

        if ($archivo->isValid() && !$archivo->hasMoved()) {
            $nuevoNombre = 'FIRMADO_' . $archivo->getRandomName();

            // Guarda en public/uploads/
            $archivo->move(FCPATH . 'uploads', $nuevoNombre);

            $inventarioModel = new InventarioModel();
            $inventarioModel->update($id_interno, [
                'archivo_firmado' => $nuevoNombre
            ]);

            return redirect()->back()->with('mensaje', '¡Documento firmado recibido y guardado en el proyecto!');
        }

        return redirect()->back()->with('error', 'Ocurrió un error al guardar el archivo.');
    }

    // --- CORREO Y PREVISUALIZACIÓN ---

    public function previsualizar($id_interno = null)
    {
        $inventarioModel = new InventarioModel();
        $data['lote'] = $inventarioModel->find($id_interno);

        if (!$data['lote']) {
            return redirect()->to('inventario')->with('error', 'No existe el registro.');
        }

        return view('inventario/previsualizar', $data);
    }

    public function enviarCorreo()
    {
        $id_interno = $this->request->getPost('id_interno');
        $correo_destino = $this->request->getPost('correo_destino');

        $inventarioModel = new InventarioModel();
        $lote = $inventarioModel->find($id_interno);

        if ($lote) {
            $emailService = \Config\Services::email();
            
            $cuerpoCorreo = view('inventario/salida_inventario', $lote);

            $emailService->setTo($correo_destino);
            $emailService->setSubject('Reporte de Salida - Lote: ' . $lote['codigo_lote']);
            $emailService->setMessage($cuerpoCorreo);
            $emailService->setMailType('html');

            if ($emailService->send()) {
                return redirect()->to('inventario')->with('mensaje', 'El formato de salida ha sido enviado exitosamente.');
            } else {
                return redirect()->to('inventario')->with('error', 'Hubo un problema al intentar enviar el correo.');
            }
        }

        return redirect()->to('inventario')->with('error', 'Registro no encontrado.');
    }
}