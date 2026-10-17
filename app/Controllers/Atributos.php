<?php

namespace App\Controllers;

use App\Models\AtributosModel;
use App\Models\MuseosModel;
use App\Models\PermisosModel;


class Atributos extends BaseController{   
    
    protected $sesion;
    protected $permisos;
    protected $atributos;
    protected $museos;

    public function __construct(){

        $this->sesion = session();
        $this->atributos = new AtributosModel();
        $this->museos = new MuseosModel();
        $this->permisos = new PermisosModel();
    }

    public function index(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('l', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');    
        }

        $atributos = $this->atributos->findAll();
        $museos = $this->museos->first();


        $datos = [ 'museos'=>$museos,
                  'atributos'=>$atributos,
                  'titulo' => 'Listado de atributos'];

        echo view('header',$datos);
        echo view('atributos/listado');
        echo  view('footer');
    }

    public function borrar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('b', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }
        $this->atributos->delete($id);
        $atributo = $this->atributos->withDeleted()->find($id); 
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Atributo eliminado correctamente'
        ]);
    } 

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de atributos'];

        echo view('header',$datos);
        echo view('atributos/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        // validacion que no deja ingresar a la funcion del controlador
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }
        //convertimos los datos que resivimos por post en las variables locales para poder trabajar
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipo_dato = strtolower(trim($this->request->getPost('tipo_dato')));
        $unidad = trim($this->request->getPost('unidad'));
        
        //revisemson de no recibir vacios
        if (empty($denominacion) || empty($tipo_dato)){
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }

        $datoAtributo = $this->atributos->where('denominacion', $denominacion)->withDeleted()->first();

        if (empty($datoAtributo)) {
            $this->atributos->save([
                'denominacion' => $denominacion,
                'tipo_dato'    => $tipo_dato,
                'unidad'       => $tipo_dato === 'numerico' ? ($unidad ?: null) : null,
            ]);
            $id = $this->atributos->getInsertID();
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' Guardado con éxito']);
            exit;
        }else{
            if(empty($datoAtributo['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }
            exit;
        }
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // 1. Trae el cliente de la BD
        $atributos = $this->atributos->where('id', $id)->first();

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'atributos'     => $atributos, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar atributo'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('atributos/editar');
        echo view('footer');
    }

    public function actualizar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipoDato     = strtolower(trim($this->request->getPost('tipo_dato')));
        $unidad = trim($this->request->getPost('unidad'));

        if (empty($denominacion)  || empty($tipoDato) ) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }
        // Busca si existe OTRO registro (id distinto) con la misma denominación
        $datoAtributo = $this->atributos->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
    
        if (empty($datoAtributo)) {
            $this->atributos->update($id, [
                'denominacion' => $denominacion,
                'tipo_dato'    => $tipoDato,
                'unidad'       => $tipoDato === 'numerico' ? ($unidad ?: null) : null,
            ]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' actualizado con éxito']);
            exit;
        }else{
            if(empty($datoAtributo['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }
            exit;
        }
    }

    public function papelera(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('l', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        
        $atributos = $this->atributos->onlyDeleted()->findAll();
        
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'atributos'     => $atributos, // <--- Aquí enviamos los datos
            'titulo'      => 'Atributos borrados'
        ];
        echo view('header', $datos);
        echo view('atributos/papelera');
        echo view('footer');
    }

    public function recuperacion($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('b', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }

        $atributo = $this->atributos->withDeleted()->find($id);
        $atributoActivo = $this->atributos->where('denominacion', $atributo['denominacion'])->first();
        if ($atributoActivo){
            echo json_encode(['exito' => false, 'mensaje' => 'Dato activo, No se puede recuperar']);
            exit;
        }else{
            $this->atributos->update($id, ['fecha_baja' => null]);
            echo json_encode(['exito' => true   , 'id' => $id, 'mensaje' => 'Atributo recuperado correctamente']);
            exit;
        }
    }
    // API: devuelve en JSON los atributos activos, del más nuevo al más viejo
    public function api_atributos(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $atributos = $this->atributos->orderBy('id','DESC')->findAll();
        $datos = ['atributos'=>$atributos];
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los atributos borrados (papelera)
    public function api_atributos_borrados(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $atributos = $this->atributos->onlyDeleted()->findAll();
        $datos = ['atributos'=>$atributos];
        return $this->response->setJSON($datos);
    }
}
