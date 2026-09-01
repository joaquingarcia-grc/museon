<?php

namespace App\Controllers;

use App\Models\ObjetosModel;
use App\Models\MuseosModel;
use App\Models\AtributosModel;
use App\Models\EtiquetasModel;
use App\Models\ObjetosAtributosModel;
use App\Models\ObjetosEtiquetasModel;

class Objetos extends BaseController {   
    
    protected $sesion;
    protected $objetos;
    protected $museos;
    protected $etiquetas;
    protected $atributos;
    protected $objetosatributos;
    protected $objetosetiquetas;

    public function __construct() {
    
        $this->sesion = session();        
        $this->objetos = new ObjetosModel();
        $this->museos = new MuseosModel();
        $this->etiquetas = new EtiquetasModel();
        $this->atributos = new AtributosModel();
        $this->objetosatributos = new ObjetosAtributosModel();
        $this->objetosetiquetas = new ObjetosEtiquetasModel();
    }

    public function index(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
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

    public function borrar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
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

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $museos = $this->museos->first();
        
        $etiquetas = $this->etiquetas->findAll();
        $atributos = $this->atributos->findAll();

        $datos = [ 
            'museos'    => $museos,
            'etiquetas' => $etiquetas, 
            'atributos' => $atributos, 
            'titulo'    => 'Formulario de objetos'
        ];

        echo view('header', $datos);
        echo view('objetos/nuevo');
        echo view('footer');
    }

    public function insertar(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $codigo = trim($this->request->getPost('codigo'));
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $descripcion = trim($this->request->getPost('descripcion'));
        
        if (!$denominacion || !$codigo){
            echo "faltan datos obligatorios";
            return;
        }

        $datoObjeto = $this->objetos->where('denominacion', $denominacion)->withDeleted()->first();

        // 1. Guardamos el objeto principal

        if (!$datoObjeto){
            $this->objetos->save([
                'denominacion' => $denominacion,
                'codigo'    => $codigo,
                'descripcion'    => $descripcion
            ]);
        }else{
            if(!$datoObjeto['fecha_baja']){
                echo "El dato existe.";                
            }else{
                echo "El dato existe en la papelera, si desea recuperarlo.";
            }
        }
        
        
        // Insertamos y obtenemos el id generado
        $objeto_id = $this->objetos->insert([
            'denominacion' => $denominacion,
            'codigo'       => $codigo,
            'descripcion'  => $descripcion
        ], true);

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

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $objetos = $this->objetos->where('id', $id)->first();
        $museos = $this->museos->first();
        $etiquetas = $this->etiquetas->findAll();
        $atributos = $this->atributos->findAll();

        $datos = [ 
            'museos'                 => $museos,
            'objetos'                => $objetos,
            'etiquetas'              => $etiquetas,
            'atributos'              => $atributos,
            'etiquetasSeleccionadas' => $this->objetosetiquetas->obtenerEtiquetasPorObjeto($id),
            'atributosSeleccionados' => $this->objetosatributos->obtenerAtributosPorObjeto($id),
            'titulo'                 => 'Editar objeto'
        ];
        
        echo view('header', $datos);
        echo view('objetos/editar');
        echo view('footer');
    }

    public function actualizar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $codigo = trim($this->request->getPost('codigo'));
        $descripcion = trim($this->request->getPost('descripcion'));

        if(!$denominacion || !$codigo){
            
            echo "faltan datos obligatorios";
            return;
        }

        $datoObjeto = $this->objetos->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();

        if (!$datoObjeto){
            $this->objetos->update($id,[
                'codigo' => $codigo,    
                'denominacion' => $denominacion,
                'descripcion' => $descripcion     
            ]);
        }else {
            if(!$datoObjeto['fecha_baja']){
                echo "El dato existe";
            }else{
                echo "El dato existe en la papelera, si desea recuperarlo.";
            }
        }

        // 2. Damos de baja las asociaciones anteriores para volver a armarlas
        //    con lo que llegó del formulario (misma lógica que insertar())
        $this->objetosetiquetas->where('objeto_id', $id)->delete();
        $this->objetosatributos->where('objeto_id', $id)->delete();

        // --- ETIQUETAS DINÁMICAS (Múltiples seleccionadas) ---
        $etiqueta_ids = $this->request->getPost('etiqueta_ids');
        if (!empty($etiqueta_ids) && is_array($etiqueta_ids)) {
            foreach ($etiqueta_ids as $id_etiqueta) {
                $this->objetosetiquetas->vincularEtiqueta($id, $id_etiqueta);
            }
        }

        // --- ATRIBUTOS DINÁMICOS ---
        $atributo_ids     = $this->request->getPost('atributo_ids');
        $atributo_valores = $this->request->getPost('atributo_valores');

        if (!empty($atributo_ids) && is_array($atributo_ids)) {
            foreach ($atributo_ids as $id_atributo) {
                if (isset($atributo_valores[$id_atributo])) {
                    $valor = $atributo_valores[$id_atributo];
                    $this->objetosatributos->vincularAtributo($id, $id_atributo, $valor);
                }
            }
        }

        return redirect()->to(base_url('objetos'));
    }

    public function papelera(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
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
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $objeto = $this->objetos->withDeleted()->find($id);
        
        $objetoActivo = $this->objetos->where('denominacion', $objeto['denominacion'])->first();

        if($objetoActivo){
            echo "ya existe un atributo activo con esa denominacion";
            return;
        }

        $this->objetos->update($id, ['fecha_baja' => null]);
        return redirect()->to(base_url('objetos'));
    }

    public function ver($id) {
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
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