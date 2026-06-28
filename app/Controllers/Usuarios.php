<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\MuseosModel;

class Usuarios extends BaseController
{   
    protected $usuarios;

    protected $museos;

    public function __construct() {

        $this->usuarios = new UsuariosModel();
        $this->museos = new MuseosModel();

    }

    public function index()
    {
        $usuarios = $this->usuarios->findAll();
        $museos = $this->museos->first();


        $datos = [ 'museos'=>$museos,
                  'usuarios'=>$usuarios,
                  'titulo' => 'Listado de usuarios'];

        echo view('header',$datos);
        echo view('usuarios/listado');
        echo  view('footer');
    }

    public function borrar($id){
        
        $this->usuarios->delete($id);
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Usuario borrado'];

        echo view('header',$datos);
        echo view('usuarios/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Usuarios'];

        echo view('header',$datos);
        echo view('usuarios/nuevo');
        echo  view('footer');

    }

    public function insertar(){

        $this->usuarios->save([
            'denominacion' => $this->request->getPost('denominacion'),
            'email'  => $this->request->getPost('email'),
            'telefono'  => $this->request->getPost('telefono')
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'usuarios');
    }

    public function editar($id){
        // 1. Trae el cliente de la BD
        $usuarios = $this->usuarios->where('id', $id)->first();

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'usuarios'     => $usuarios, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar usuario'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('usuarios/editar');
        echo view('footer');
    }

    public function actualizar($id){

        $this->usuarios->update($id,[
            'denominacion' => $this->request->getPost('denominacion'),
            'email'  => $this->request->getPost('email'),
            'telefono'  => $this->request->getPost('telefono')
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'usuarios');
    }

    public function papelera(){

        $usuarios = $this->usuarios->onlyDeleted()->findAll();
        
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'usuarios'     => $usuarios, // <--- Aquí enviamos los datos
            'titulo'      => 'Usuarios borrados'
        ];
        echo view('header', $datos);
        echo view('usuarios/papelera');
        echo view('footer');
    }

    public function recuperacion($id){

        $this->usuarios->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'usuarios');
    }
}
?>