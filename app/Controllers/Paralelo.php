<?php

namespace App\Controllers;

use App\Models\ParaleloModel;

class Paralelo extends BaseController
{   

    //variable protegida de una instancia del paralelo

    protected $paralelo;

    //funcion constructora de mi objeto paralelo
    public function __construct() {
        // En su interior contiene una instancia de la base de datos
        $this->paralelo = new ParaleloModel();

    }
    public function index()
    {
        $paralelo = $this->paralelo->findAll();
        
        $datos = ['paralelo'=>$paralelo];

        echo view('header',$datos);
        echo view('clientes/clientes');
        echo  view('footer');
    }

    public function borrar($id){
        
        $this->clientes->delete($id);

        $veterinaria = $this->veterinaria->first();
        $datos = ['veterinaria'=>$veterinaria];

        echo view('header',$datos);
        echo view('clientes/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){


        $veterinaria = $this->veterinaria->first();
        $datos = ['veterinaria'=>$veterinaria];

        echo view('header',$datos);
        echo view('clientes/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        // Guarda la nueva noticia (CodeIgniter Model -> save())
        $this->clientes->save([
            'nombre' => $this->request->getPost('nombre'),
            'apellido'  => $this->request->getPost('apellido'),
            'telefono'  => $this->request->getPost('telefono'),
            'email'  => $this->request->getPost('email'),
            'domicilio'  => $this->request->getPost('domicilio'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'clientes');
    }
    
}

?>