<?php

namespace App\Controllers;

use App\Models\EtiquetasModel;
use App\Models\MuseosModel;

class Etiquetas extends BaseController{   
    
    protected $etiquetas;
    protected $museos;

    public function __construct() {

        $this->etiquetas = new EtiquetasModel();
        $this->museos = new MuseosModel();

    }

    public function index()
    {
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
        
        $this->etiquetas->delete($id);
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Etiqueta borrada'];

        echo view('header',$datos);
        echo view('etiquetas/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Etiquetas'];

        echo view('header',$datos);
        echo view('etiquetas/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        
        $this->etiquetas->save([
            'denominacion' => $this->request->getPost('denominacion'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'etiquetas');
    }

    public function editar($id){
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

        $this->etiquetas->update($id,[
            'denominacion' => $this->request->getPost('denominacion')
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'etiquetas');
    }

    public function papelera(){

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

        $this->etiquetas->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'etiquetas');
    }
}
?>