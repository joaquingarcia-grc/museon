<?php

namespace App\Controllers;

use App\Models\AtributosModel;
use App\Models\MuseosModel;

class Atributos extends BaseController{   
    
    protected $sesion;

    protected $atributos;
    protected $museos;

    public function __construct(){

        $this->sesion = session();
        $this->atributos = new AtributosModel();
        $this->museos = new MuseosModel();

    }

    public function index(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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
        
        $this->atributos->delete($id);
        $atributo = $this->atributos->withDeleted()->find($id); 
        return $this->response->setStatusCode(200);
    } 

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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
        //convertimos los datos que resivimos por post en las variables locales para poder trabajar
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipo_dato = strtolower(trim($this->request->getPost('tipo_dato')));
        
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
        return redirect()->to(base_url() . 'atributos');
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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
        

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipoDato     = strtolower(trim($this->request->getPost('tipo_dato')));

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
        return redirect()->to(base_url() . 'atributos');
    }

    public function papelera(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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

        $atributo = $this->atributos->withDeleted()->find($id);
        $existeActivo = $this->atributos->where('denominacion', $atributo['denominacion'])->first();
        if ($existeActivo){
            echo json_encode(['exito' => false, 'mensaje' => 'Dato activo']);
            exit;
        }else{
            $this->atributos->update($id, ['fecha_baja' => null]);
            echo json_encode(['exito' => true   , 'id' => $id, 'mensaje' => 'Se actualizo correctamente']);
            exit;
        }
        return redirect()->to(base_url() . 'atributos');
    }
}
