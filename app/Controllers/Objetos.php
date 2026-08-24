<?php

namespace App\Controllers;

use App\Models\ObjetosModel;
use App\Models\MuseosModel;

class Objetos extends BaseController{   
    
    protected $objetos;
    protected $museos;

    public function __construct() {

        $this->objetos = new ObjetosModel();
        $this->museos = new MuseosModel();

    }

    public function index(){

        $objetos = $this->objetos->findAll();
        $museos = $this->museos->first();


        $datos = [ 'museos'=>$museos,
                  'objetos'=>$objetos,
                  'titulo' => 'Listado de objetos'];

        echo view('header',$datos);
        echo view('objetos/listado');
        echo  view('footer');
    }

    public function borrar($id){
        
        $this->objetos->delete($id);
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Objeto borrado'];

        echo view('header',$datos);
        echo view('objetos/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de objetos'];

        echo view('header',$datos);
        echo view('objetos/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        
        $this->objetos->save([
            'denominacion' => $this->request->getPost('denominacion'),
            'descripcion' => $this->request->getPost('descripcion'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'objetos');
    }

    public function editar($id){
        // 1. Trae el cliente de la BD
        $objetos = $this->objetos->where('id', $id)->first();

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'objetos'     => $objetos, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar objeto'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('objetos/editar');
        echo view('footer');
    }

    public function actualizar($id){

        $this->objetos->update($id,[
            'denominacion' => $this->request->getPost('denominacion'),
            'descripcion' => $this->request->getPost('descripcion'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'objetos');
    }

    public function papelera(){

        $objetos = $this->objetos->onlyDeleted()->findAll();
        
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'objetos'     => $objetos, // <--- Aquí enviamos los datos
            'titulo'      => 'Objetos borrados'
        ];
        echo view('header', $datos);
        echo view('objetos/papelera');
        echo view('footer');
    }

    public function recuperacion($id){

        $this->objetos->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'objetos');
    }
}
?>