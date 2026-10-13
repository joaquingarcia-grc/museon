<?php

namespace App\Controllers;

use App\Models\EtiquetasModel;
use App\Models\MuseosModel;
use App\Models\PermisosModel;

class Etiquetas extends BaseController{ 
    
    protected $sesion;
    protected $permisos;
    protected $etiquetas;
    protected $museos;

    public function __construct() {
        
        $this->sesion = session();
        $this->etiquetas = new EtiquetasModel();
        $this->museos = new MuseosModel();
        $this->permisos = new PermisosModel();
    }

    public function index(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('l', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        $etiquetas = $this->etiquetas->findAll();
        $museos = $this->museos->first();


        $datos = [ 'museos'=>$museos,
                  'etiquetas'=>$etiquetas,
                  'titulo' => 'Listado de etiquetas'];

        echo view('header',$datos);
        echo view('etiquetas/listado');
        echo  view('footer');
    }

    public function borrar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('b', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }    
        $this->etiquetas->delete($id);
        $etiquetas = $this->etiquetas->withDeleted()->find($id); 
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Etiqueta eliminada correctamente'
        ]);
    } 

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');    
        }

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Etiquetas'];

        echo view('header',$datos);
        echo view('etiquetas/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));

        if (empty($denominacion)) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }

        $datoEtiqueta = $this->etiquetas->where('denominacion', $denominacion)->withDeleted()->first();

        if (empty($datoEtiqueta)){
            $this->etiquetas->save([
                'denominacion' => $denominacion,
            ]);
            $id = $this->etiquetas->getInsertID();
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' Guardado con éxito']);
            exit;
        }else{
            if(empty($datoEtiqueta['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }exit;
        }return redirect()->to(base_url() . 'etiquetas');
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }    

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // 1. Trae el cliente de la BD
        $etiquetas = $this->etiquetas->where('id', $id)->first();

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'etiquetas'     => $etiquetas, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar etiqueta'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('etiquetas/editar');
        echo view('footer');
    }

    public function actualizar($id){
                
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));

        if (empty($denominacion)) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;}
        // Busca si existe OTRO registro (id distinto) con la misma denominación
        $datoEtiqueta = $this->etiquetas->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();

        if (empty($datoEtiqueta)) {
            $this->etiquetas->update($id, [
                'denominacion' => $denominacion,
            ]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' actualizada con éxito']);
            exit;
        }else{
            if(empty($datoEtiqueta['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }exit;
        }return redirect()->to(base_url() . 'etiquetas');
    }

    public function papelera(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('l', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');    
        }

        $etiquetas = $this->etiquetas->onlyDeleted()->findAll();
        
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'etiquetas'     => $etiquetas, // <--- Aquí enviamos los datos
            'titulo'      => 'Etiquetas borradas'
        ];
        echo view('header', $datos);
        echo view('etiquetas/papelera');
        echo view('footer');
    }

    public function recuperacion($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('b', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }

        $etiquetas = $this->etiquetas->withDeleted()->find($id); 
        $etiquetaActiva = $this->etiquetas->where('denominacion', $etiquetas['denominacion'])->first();
        
        if ($etiquetaActiva){
            echo json_encode(['exito' => false, 'mensaje' => 'Dato activo, No se puede recuperar']);
            exit;
        }else{
            $this->etiquetas->update($id, ['fecha_baja' => null]);
            echo json_encode(['exito' => true   , 'id' => $id, 'mensaje' => 'Se actualizo correctamente']);
            exit;
        }
        
    }
    // API: devuelve en JSON los etiquetas activos, del más nuevo al más viejo
    public function api_etiquetas(){
        $etiquetas = $this->etiquetas->orderBy('id','DESC')->findAll();
        $datos = ['etiquetas'=>$etiquetas];
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los etiquetas borrados (papelera)
    public function api_etiquetas_borrados(){
        $etiquetas = $this->etiquetas->onlyDeleted()->findAll();
        $datos = ['etiquetas'=>$etiquetas];
        return $this->response->setJSON($datos);
    }
}
?>