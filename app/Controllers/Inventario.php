<?php

namespace App\Controllers;

use App\Models\Inventario as InventarioModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Inventario extends BaseController
{
    // 1. ENTRADA Y VISUALIZACIÓN DE MERCANCÍA

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

    // 2. ARRENDAMIENTO, DESCUENTO Y ENVÍO HTML

    public function arrendamientoGeneral()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT presentacion_cupo, SUM(cantidad_cajas) as total_cajas FROM inventario WHERE cantidad_cajas > 0 GROUP BY presentacion_cupo");
        $data['stock_global'] = $query->getResultArray();

        return view('inventario/arrendamiento_general', $data);
    }

    public function procesarArrendamientoGeneral()
    {
        $presentaciones = $this->request->getPost('presentaciones') ?? [];
        $cantidades     = $this->request->getPost('cantidades') ?? [];
        
        $precio_arrendamiento = $this->request->getPost('precio_arrendamiento');
        $observaciones        = $this->request->getPost('observaciones');

        $inventarioModel = new InventarioModel();

        // 1. Filtrar productos a arrendar
        $items_solicitados = [];
        for ($i = 0; $i < count($presentaciones); $i++) {
            $cant = (int)$cantidades[$i];
            if ($cant > 0) {
                $items_solicitados[] = [
                    'presentacion' => $presentaciones[$i],
                    'cantidad'     => $cant
                ];
            }
        }

        if (empty($items_solicitados)) {
            return redirect()->back()->with('error', 'Debes ingresar al menos una cantidad mayor a 0 para procesar el arrendamiento.');
        }

        // 2. Descuento FIFO por lote en la BD
        foreach ($items_solicitados as $item) {
            $lotes = $inventarioModel->where('presentacion_cupo', $item['presentacion'])
                                     ->where('cantidad_cajas >', 0)
                                     ->orderBy('fecha_ingreso', 'ASC')
                                     ->orderBy('id_interno', 'ASC')
                                     ->findAll();
            
            $cajas_restantes_por_descontar = $item['cantidad'];

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
                    $cajas_restantes_por_descontar = 0; 
                }
            }
        }

        // 3. Pasar datos a la vista de previsualización para envío de correo
        session()->setFlashdata('salida_datos', [
            'items' => $items_solicitados,
            'precio_total' => $precio_arrendamiento,
            'observaciones' => $observaciones
        ]);

        return redirect()->to('arrendamientos/previsualizarSalida');
    }

    public function previsualizarSalida()
    {
        $salida = session()->getFlashdata('salida_datos');

        if (!$salida) {
            return redirect()->to('inventario')->with('error', 'No hay datos de salida recientes para previsualizar.');
        }

        session()->setFlashdata('salida_datos', $salida);

        return view('inventario/previsualizar_salida', $salida);
    }

    public function enviarCorreoSalida()
    {
        $correo_destino = $this->request->getPost('correo_destino');
        $presentaciones = $this->request->getPost('presentacion') ?? [];
        $cantidades     = $this->request->getPost('cantidad_carton') ?? [];
        $costos         = $this->request->getPost('costo_carton') ?? [];

        $items = [];
        for ($i = 0; $i < count($presentaciones); $i++) {
            $items[] = [
                'presentacion'    => $presentaciones[$i],
                'cantidad_carton' => $cantidades[$i],
                'costo_carton'    => $costos[$i]
            ];
        }

        $datosCorreo = [
            'items' => $items,
            'fecha' => date('d/m/Y')
        ];

       //HTML como un string
        $cuerpoHtml = view('inventario/salida_inventario', $datosCorreo);


        //Configuramos e inicializamos Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        
        $dompdf = new Dompdf($options);
        
        $dompdf->loadHtml($cuerpoHtml);
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render();

        //Guardamos el PDF temporalmente en tu carpeta uploads
        $output = $dompdf->output();
        $nombre_pdf = 'Reporte_Salida_' . time() . '.pdf';
        
        // Definimos la ruta de la carpeta
        $directorio_destino = FCPATH . 'uploads/';

        // Verificamos si la carpeta no existe, y si sí, la creamos con permisos de escritura
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        $ruta_temporal = $directorio_destino . $nombre_pdf;
        
        //guardamos el archivo 
        file_put_contents($ruta_temporal, $output);

        // Correo con el archivo adjunto
        $emailService = \Config\Services::email();
        $emailService->setTo($correo_destino);
        $emailService->setSubject('Reporte de Salida / Formato de Arrendamiento');
        
        $emailService->setMessage('Hola. Adjunto a este correo encontrarás el formato de arrendamiento correspondiente a la salida de inventario en formato PDF.');
        $emailService->setMailType('text');
        
        $emailService->attach($ruta_temporal);

        // Enviamos y limpiamos el archivo temporal
        if ($emailService->send()) {
            unlink($ruta_temporal);
            return redirect()->to('inventario')->with('mensaje', 'El formato de salida ha sido generado en PDF y enviado exitosamente al cliente.');
        } else {
            if (file_exists($ruta_temporal)) {
                unlink($ruta_temporal);
            }
            return redirect()->to('inventario')->with('error', 'Hubo un problema al intentar enviar el correo con el PDF.');
        }
    }   

    //GESTIÓN DE ARCHIVOS PDF Y FIRMAS

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

            // Guardar dentro del proyecto en public/uploads/
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

            // Guardar dentro del proyecto public/uploads/
            $archivo->move(FCPATH . 'uploads', $nuevoNombre);

            $inventarioModel = new InventarioModel();
            $inventarioModel->update($id_interno, [
                'archivo_firmado' => $nuevoNombre
            ]);

            return redirect()->back()->with('mensaje', '¡Documento firmado recibido y guardado en el proyecto!');
        }

        return redirect()->back()->with('error', 'Ocurrió un error al guardar el archivo.');
    }
}   