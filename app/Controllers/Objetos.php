<?php

namespace App\Controllers;

use App\Models\ObjetosModel;
use App\Models\MuseosModel;
use App\Models\AtributosModel;
use App\Models\EtiquetasModel;
use App\Models\ObjetosAtributosModel;
use App\Models\ObjetosEtiquetasModel;
use App\Models\UsuariosModel;

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
        $this->usuarios = new UsuariosModel();     
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
        $objetos = $this->objetos->withDeleted()->find($id); 
        return $this->response->setStatusCode(200);
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
        
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $id_usuario = $this->sesion->get('id');
        $codigo = trim($this->request->getPost('codigo'));
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $descripcion = trim($this->request->getPost('descripcion'));
        
        if (empty($denominacion) || empty($codigo)){
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }

        $datoObjeto = $this->objetos->where('denominacion', $denominacion)->withDeleted()->first();

        // 1. Guardamos el objeto principal

        if (empty($datoObjeto)) {
            $objetoGuardar = [
                'id_usuario'   => $id_usuario,
                'denominacion' => $denominacion,
                'codigo'       => $codigo,
                'descripcion'  => $descripcion
            ];
        } else{
            if (($datoObjeto['fecha_baja'])) {
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }
            exit;
        }
        
        
        // Insertamos y obtenemos el id generado
        if($objeto_id = $this->objetos->insert($objetoGuardar, true));

        if (empty($objeto_id)){
            echo json_encode(['exito' => false, 'mensaje' => 'Error al cargar']);
            exit;
        }

        // 2. Si se guardó correctamente, procesamos etiquetas y atributos dinámicos
        if ($objeto_id) {
            
            // --- ETIQUETAS DINÁMICAS (Múltiples seleccionadas) ---
            $etiqueta_ids = $this->request->getPost('etiqueta_ids');
            if (!empty($etiqueta_ids) && is_array($etiqueta_ids)) {
                foreach ($etiqueta_ids as $id_etiqueta) {
                    $this->vincularEtiqueta($objeto_id, $id_etiqueta);
                }
            }

            // --- ATRIBUTOS DINÁMICOS
            $atributo_ids     = $this->request->getPost('atributo_ids');
            $atributo_valores = $this->request->getPost('atributo_valores');
            
            if (!empty($atributo_ids) && is_array($atributo_ids)) {
                foreach ($atributo_ids as $id_atributo) {
                    if (isset($atributo_valores[$id_atributo])) {
                        $valor = $atributo_valores[$id_atributo];
                        $this->vincularAtributo($objeto_id, $id_atributo, $valor);
                    }
                }
            }
        }
        
        $id = $this->objetos->getInsertID();
        echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' Guardado con éxito']);
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
            'etiquetasSeleccionadas' => $this->obtenerEtiquetasPorObjeto($id),
            'atributosSeleccionados' => $this->obtenerAtributosPorObjeto($id),
            'titulo'                 => 'Editar pieza'
        ];
        
        echo view('header', $datos);
        echo view('objetos/editar');
        echo view('footer');
    }

    public function actualizar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $codigo = trim($this->request->getPost('codigo'));
        $descripcion = trim($this->request->getPost('descripcion'));

        if(empty($denominacion) || empty($codigo)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
            exit;
        }
        $datoObjeto = $this->objetos->onlyDeleted()->where('denominacion', $denominacion)->where('id !=', $id)->first();

        if (!empty($datoObjeto)){
            echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            exit;
        }

        $this->objetos->update($id,[
            'codigo' => $codigo,    
            'denominacion' => $denominacion,
            'descripcion' => $descripcion     
        ]);

        // 2. Damos de baja las asociaciones anteriores para volver a armarlas
        //    con lo que llegó del formulario (misma lógica que insertar())
        $this->objetosetiquetas->where('objeto_id', $id)->delete();
        $this->objetosatributos->where('objeto_id', $id)->delete();

        // --- ETIQUETAS DINÁMICAS (Múltiples seleccionadas) ---
        $etiqueta_ids = $this->request->getPost('etiqueta_ids');
        if (!empty($etiqueta_ids) && is_array($etiqueta_ids)) {
            foreach ($etiqueta_ids as $id_etiqueta) {
                $this->vincularEtiqueta($id, $id_etiqueta);
            }
        }

        // --- ATRIBUTOS DINÁMICOS ---
        $atributo_ids     = $this->request->getPost('atributo_ids');
        $atributo_valores = $this->request->getPost('atributo_valores');

        if (!empty($atributo_ids) && is_array($atributo_ids)) {
            foreach ($atributo_ids as $id_atributo) {
                if (isset($atributo_valores[$id_atributo])) {
                    $valor = $atributo_valores[$id_atributo];
                    $this->vincularAtributo($id, $id_atributo, $valor);
                }
            }
        }
        echo json_encode(['exito' => true, 'mensaje' => 'Actualizado exitosamente']);
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
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $objeto = $this->objetos->withDeleted()->find($id);
        if ($objeto){
            $this->objetos->update($id, ['fecha_baja' => null]);
            echo json_encode(['exito' => true, 'mensaje' => 'recuperado exitosamente']);
            exit;
        }
    }

    public function ver($id) {
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // 1. Traemos los datos principales del objeto
        //$objeto = $this->objetos->where('id', $id)->first();
        $museos = $this->museos->first();

        $objeto =   $this->objetos
                                ->select('objetos.*, usuarios.denominacion AS cargado_por')
                                ->join('usuarios', 'usuarios.id = objetos.id_usuario')
                                ->where('objetos.id', $id)
                                ->first();

        $objetosatributos = $this->obtenerAtributosPorObjeto($id);
        $objetosetiquetas = $this->obtenerEtiquetasPorObjeto($id);

        $datos = [ 
            'museos'    => $museos,
            //'usaurio' => $objeto_por_usuario,
            'objeto'    => $objeto,
            'etiquetas' => $objetosetiquetas,
            'atributos' => $objetosatributos,
            'titulo'    => 'Detalle del Objeto'
        ];
        
        echo view('header', $datos);
        echo view('objetos/ver'); // Llamamos a la nueva vista
        echo view('footer');
        
    }

    // API: devuelve en JSON los atributos activos, del más nuevo al más viejo
    public function api_objetos(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $objetos = $this->objetos->select('objetos.*, usuarios.denominacion AS cargado_por')
                    ->join('usuarios', 'usuarios.id = objetos.id_usuario', 'left')
                    ->orderBy('objetos.id','DESC')
                    ->findAll();

        $datos = ['objetos'=>$objetos];
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los atributos borrados (papelera)
    public function api_objetos_borrados(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $objetos = $this->objetos->onlyDeleted()->findAll();
        $datos = ['objetos'=>$objetos];
        return $this->response->setJSON($datos);
    }


    public function obtenerAtributosPorObjeto($objeto_id){
        return $this->objetosatributos->select('atributos.id, atributos.denominacion, atributos.tipo_dato,atributos.unidad, objeto_atributos.valor')
                    ->join('atributos', 'atributos.id = objeto_atributos.atributo_id')
                    ->where('objeto_atributos.objeto_id', $objeto_id)
                    ->findAll();
    }
    
     // Vincula un objeto con un atributo y su valor
    public function vincularAtributo($objeto_id, $atributo_id, $valor){
        return $this->objetosatributos->insert([
            'objeto_id'   => $objeto_id,
            'atributo_id' => $atributo_id,
            'valor'       => $valor,
        ]);
    }
    public function vincularEtiqueta($objeto_id, $etiqueta_id){
        return $this->objetosetiquetas->insert([
            'objeto_id'   => $objeto_id,
            'etiqueta_id' => $etiqueta_id,
        ]);
    }
    // Trae las etiquetas vinculadas a un objeto
    
    public function obtenerEtiquetasPorObjeto($objeto_id){
        return $this->objetosetiquetas->select('etiquetas.id, etiquetas.denominacion')
                    ->join('etiquetas', 'etiquetas.id = objeto_etiquetas.etiqueta_id')
                    ->where('objeto_etiquetas.objeto_id', $objeto_id)
                    ->findAll();
    }
    
}