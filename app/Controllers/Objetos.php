<?php

namespace App\Controllers;

use App\Models\ObjetosModel;
use App\Models\MuseosModel;
use App\Models\ObjetosAtributosModel;
use App\Models\ObjetosEtiquetasModel;

class Objetos extends BaseController {   

    protected $objetos;
    protected $museos;
    protected $objetosatributos;
    protected $objetosetiquetas;

    public function __construct() {
        
        $this->objetos = new ObjetosModel();
        $this->museos = new MuseosModel();
        $this->objetosatributos = new ObjetosAtributosModel();
        $this->objetosetiquetas = new ObjetosEtiquetasModel();
    }

    public function index() {
        $objetos = $this->objetos->findAll();
        $museos = $this->museos->first();

        $datos = [ 
            'museos'  => $museos,
            'objetos' => $objetos,
            'titulo'  => 'Listado de objetos'
        ];

        echo view('header', $datos);
        echo view('objetos/listado');
        echo view('footer');
    }

    public function borrar($id) {
        $this->objetos->delete($id);
        $museos = $this->museos->first();

        $datos = [ 
            'museos' => $museos,
            'titulo' => 'Objeto borrado'
        ];

        echo view('header', $datos);
        echo view('objetos/avisoborrado');
        echo view('footer');
    } 

    public function nuevo() {
        $museos = $this->museos->first();

        $etiquetasModel = new \App\Models\EtiquetasModel();
        $atributosModel = new \App\Models\AtributosModel();

        $datos = [ 
            'museos'    => $museos,
            'etiquetas' => $etiquetasModel->findAll(), 
            'atributos' => $atributosModel->findAll(), 
            'titulo'    => 'Formulario de objetos'
        ];

        echo view('header', $datos);
        echo view('objetos/nuevo');
        echo view('footer');
    }

    public function insertar() {
        // 1. Guardamos el objeto principal
        $dataObjeto = [
            'codigo'       => $this->request->getPost('codigo'),
            'denominacion' => $this->request->getPost('denominacion'),
            'descripcion'  => $this->request->getPost('descripcion'),
        ];
        
        $objeto_id = $this->objetos->insert($dataObjeto, true);

        // 2. Si se guardó correctamente, procesamos etiquetas y atributos dinámicos
        if ($objeto_id) {
            
            // --- ETIQUETAS DINÁMICAS (Múltiples seleccionadas) ---
            $etiqueta_ids = $this->request->getPost('etiqueta_ids');
            if (!empty($etiqueta_ids) && is_array($etiqueta_ids)) {
                foreach ($etiqueta_ids as $id_etiqueta) {
                        $this->objetosetiquetas->vincularEtiqueta($objeto_id, $id_etiqueta);                }
            }

            // --- ATRIBUTOS DINÁMICOS
            $atributo_ids     = $this->request->getPost('atributo_ids');
            $atributo_valores = $this->request->getPost('atributo_valores');

            if (!empty($atributo_ids) && is_array($atributo_ids)) {
                foreach ($atributo_ids as $id_atributo) {
                    if (isset($atributo_valores[$id_atributo])) {
                        $valor = $atributo_valores[$id_atributo];
                       $this->objetosatributos->vincularAtributo($objeto_id, $id_atributo, $valor);
                    }
                }
            }
        }

        return redirect()->to(base_url('objetos'));
    }

    public function editar($id) {
        $objetos = $this->objetos->where('id', $id)->first();
        $museos = $this->museos->first();

        $datos = [ 
            'museos'  => $museos,
            'objetos' => $objetos,
            'titulo'  => 'Editar objeto'
        ];
        
        echo view('header', $datos);
        echo view('objetos/editar');
        echo view('footer');
    }

    public function actualizar($id) {
        $this->objetos->update($id, [
            'codigo'       => $this->request->getPost('codigo'),
            'denominacion' => $this->request->getPost('denominacion'),
            'descripcion'  => $this->request->getPost('descripcion'),
        ]);
        
        return redirect()->to(base_url('objetos'));
    }

    public function papelera() {
        $objetos = $this->objetos->onlyDeleted()->findAll();
        $museos = $this->museos->first();

        $datos = [ 
            'museos'  => $museos,
            'objetos' => $objetos, 
            'titulo'  => 'Objetos borrados'
        ];
        
        echo view('header', $datos);
        echo view('objetos/papelera');
        echo view('footer');
    }

    public function recuperacion($id) {
        $this->objetos->update($id, ['fecha_baja' => null]);
        return redirect()->to(base_url('objetos'));
    }

    public function ver($id) {
        // 1. Traemos los datos principales del objeto
        $objeto = $this->objetos->where('id', $id)->first();
        $museos = $this->museos->first();

        $objetosatributos = $this->objetosatributos->obtenerAtributosPorObjeto($id);
        $objetosetiquetas = $this->objetosetiquetas->obtenerEtiquetasPorObjeto($id);

        $datos = [ 
            'museos'    => $museos,
            'objeto'    => $objeto,
            'etiquetas' => $objetosetiquetas,
            'atributos' => $objetosatributos,
            'titulo'    => 'Detalle del Objeto'
        ];
        
        echo view('header', $datos);
        echo view('objetos/ver'); // Llamamos a la nueva vista
        echo view('footer');
        
    }
    
}