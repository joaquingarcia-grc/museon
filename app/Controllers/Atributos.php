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
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Atributo borrado'];

        echo view('header',$datos);
        echo view('atributos/avisoborrado');
        echo  view('footer');
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

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipoDato = strtolower(trim($this->request->getPost('tipo_dato')));

        $datoAtributo = $this->atributos->where('denominacion', $denominacion)->first();

        if (!$datoAtributo) {
            $this->atributos->save([
                'denominacion' => $denominacion,
                'tipo_dato'    => $tipoDato,
            ]);

            return redirect()->to(base_url() . 'atributos');
        } else {
            echo "el dato ya existe";
        }
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
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipoDato     = strtolower(trim($this->request->getPost('tipo_dato')));
    
        // Busca si existe OTRO registro (id distinto) con la misma denominación
        $datoAtributo = $this->atributos->where('denominacion', $denominacion)->where('id !=', $id)->first();
    
        if ($datoAtributo === null) {
            $this->atributos->update($id, [
                'denominacion' => $denominacion,
                'tipo_dato'    => $tipoDato,
            ]);
    
            return redirect()->to(base_url() . 'atributos');
        } else {
            echo "el dato ya existe";
        }
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
        $this->atributos->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'atributos');
    }
}
?>