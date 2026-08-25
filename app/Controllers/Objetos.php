<?php

namespace App\Controllers;

use App\Models\ObjetosModel;
use App\Models\MuseosModel;
use App\Models\ObjetosAtributosModel;

class Objetos extends BaseController {   
    
    protected $objetos;
    protected $museos;
    protected $objetosatributos;

    public function __construct() {
        $this->objetos = new ObjetosModel();
        $this->museos = new MuseosModel();
        $this->objetosatributos = new ObjetosAtributosModel();
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
                    $this->objetos->vincularEtiqueta($objeto_id, $id_etiqueta);
                }
            }

            // --- ATRIBUTOS DINÁMICOS
            $atributo_ids     = $this->request->getPost('atributo_ids');
            $atributo_valores = $this->request->getPost('atributo_valores');

            if (!empty($atributo_ids) && is_array($atributo_ids)) {
                foreach ($atributo_ids as $id_atributo) {
                    if (isset($atributo_valores[$id_atributo])) {
                        $valor = $atributo_valores[$id_atributo];
                        $this->objetos->vincularAtributo($objeto_id, $id_atributo, $valor);
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

        // 2. Traemos las etiquetas y atributos usando los métodos nuevos del modelo
        // $etiquetas = $this->objetos->obtenerEtiquetasPorObjeto($id);
           // 2. Traemos los atributos del objeto (con join a la tabla 'atributos' para traer la denominación)
        $objetosatributos = $this->db->table('objeto_atributos')
            ->select('atributos.denominacion, objeto_atributos.valor')
            ->join('atributos', 'atributos.id = objeto_atributos.atributo_id')
            ->where('objeto_atributos.objeto_id', $id)
            ->get()->getResultArray();
        
        $datos = [ 
            'museos'    => $museos,
            'objeto'    => $objeto,
    
            'atributos' => $objetosatributos,
            'titulo'    => 'Detalle del Objeto'
        ];
        
        echo view('header', $datos);
        echo view('objetos/ver'); // Llamamos a la nueva vista
        echo view('footer');
        
    }
    // Guarda la relación entre un objeto y una etiqueta.

    public function vincularEtiqueta($objeto_id, $etiqueta_id)
    {
        $data = [
            // Asigna el ID del objeto a la columna correspondiente
            'objeto_id'   => $objeto_id,
            // Asigna el ID de la etiqueta a la columna correspondiente
            'etiqueta_id' => $etiqueta_id,
            'fecha_alta'  => date('Y-m-d H:i:s')
        ];
        // Accede a la tabla 'objeto_etiqueta' e inserta los datos asignados
        $this->db->table('objeto_etiquetas')->insert($data);
    }

    // Guarda la relación entre un objeto, un atributo y su valor escrito.
    
  public function vincularAtributo($objeto_id, $atributo_id, $valor)
    {
        $data = [
            // ID del objeto al que pertenece el atributo
            'objeto_id'   => $objeto_id,
            // ID del atributo que se le asigna
            'atributo_id' => $atributo_id,
            // Valor explicito del atributo asignado al objeto
            'valor'       => $valor,
            'fecha_alta'  => date('Y-m-d H:i:s')
        ];
        // Accede a la tabla ' objeto_atributo' e inserta el registro
        $this->db->table('objeto_atributos')->insert($data);
    }

    // Trae todas las etiquetas asociadas a un objeto
    public function obtenerEtiquetasPorObjeto($id_objeto)
    {
        // Hace la consulta a la tabla 'objeto_etiqueta'
        return $this->db->table('objeto_etiquetas')
            // Selecciona unicamente la columna denominacion de la tabla etiquetas
            ->select('etiquetas.denominacion')
            // Une la tabla 'etiquetas' comparando sus IDs para traer los nombres reales
            ->join('etiquetas', 'etiquetas.id = objeto_etiquetas.etiqueta_id')
            // Filtra para traer solo las etiquetas del objeto pasado por parametro
            ->where('objeto_etiquetas.objeto_id', $id_objeto)
            // Ejecuta la consulta SQL 
            ->get()->getResultArray();
    }

    // Trae todos los atributos y sus valores asociados a un objeto
    
   
}