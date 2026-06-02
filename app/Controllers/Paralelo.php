<?php

namespace App\Controllers;

use App\Models\NoticieroModel;
use App\Models\AdministracionModel;

class Clientes extends BaseController
{   
    protected $clientes;

    protected $veterinaria;

    public function __construct() {

        $this->veterinaria = new AdministracionModel();

        $this->clientes = new ClientesModel();
    }

    public function index()
    {
        $clientes = $this->clientes->findAll();
        $veterinaria = $this->veterinaria->first();
        
        $datos = ['veterinaria'=>$veterinaria, 
                  'clientes'=>$clientes,
                  'titulo' => 'Listado'];

        echo view('header',$datos);
        echo view('clientes/listado');
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
        $datos = [  'veterinaria'=>$veterinaria,
                    'titulo' => 'Formulario'];

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

    public function editar($id){
        // 1. Trae el cliente de la BD
        $cliente = $this->clientes->where('id', $id)->first();
        // 2. Prepara los datos para la vista
        $veterinaria = $this->veterinaria->first();
        $datos = [
            'veterinaria' => $veterinaria,
            'cliente'     => $cliente, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar Cliente'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('clientes/editar');
        echo view('footer');
    }

    public function actualizar($id){

        $this->clientes->update($id,[
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('email'),
            'domicilio' => $this->request->getPost('domicilio'),
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'clientes');
    }
    }
?>