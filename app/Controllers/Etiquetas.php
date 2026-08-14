<?php

namespace App\Controllers;

use App\Models\EtiquetasModel;
use App\Models\MuseosModel;

class Etiquetas extends BaseController{ 
    
    protected $sesion;

    protected $etiquetas;
    protected $museos;

    public function __construct() {
        
        $this->sesion = session();
        $this->etiquetas = new EtiquetasModel();
        $this->museos = new MuseosModel();

    }

    public function index(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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

        $this->etiquetas->delete($id);
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Etiqueta borrada'];

        echo view('header',$datos);
        echo view('etiquetas/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));

        $datoEtiqueta = $this->etiquetas->where('denominacion', $denominacion)->first();

        if ($datoEtiqueta === null) {
            $this->etiquetas->save([
                'denominacion' => $denominacion,
            ]);

            return redirect()->to(base_url() . 'etiquetas');
        } else {
            echo "el dato ya existe";
        }
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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
        
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
    
        // Busca si existe OTRO registro (id distinto) con la misma denominación
        $datoEtiqueta = $this->etiquetas->where('denominacion', $denominacion)->where('id !=', $id)->first();
    
        if ($datoEtiqueta === null) {
            $this->etiquetas->update($id, [
                'denominacion' => $denominacion,
            ]);
    
            return redirect()->to(base_url() . 'etiquetas');
        } else {
            echo "el dato ya existe";
        }
    }

    public function papelera(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
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
        
        $this->etiquetas->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'etiquetas');
    }
}
?>