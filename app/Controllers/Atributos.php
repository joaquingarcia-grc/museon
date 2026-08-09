<?php

namespace App\Controllers;

use App\Models\AtributosModel;
use App\Models\MuseosModel;

class Atributos extends BaseController{   
    
    protected $atributos;
    protected $museos;

    public function __construct() {

        $this->atributos = new AtributosModel();
        $this->museos = new MuseosModel();

    }

    public function index()
    {
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
        
        $this->atributos->delete($id);
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Atributo borrado'];

        echo view('header',$datos);
        echo view('atributos/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de atributos'];

        echo view('header',$datos);
        echo view('atributos/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        
        $this->atributos->save([
            'denominacion' => $this->request->getPost('denominacion'),
            'tipo_dato' => $this->request->getPost('tipo_dato'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'atributos');
    }

    public function editar($id){
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

        $this->atributos->update($id,[
            'denominacion' => $this->request->getPost('denominacion'),
            'tipo_dato' => $this->request->getPost('tipo_dato'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'atributos');
    }

    public function papelera(){

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

        $this->atributos->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'atributos');
    }
}
?>