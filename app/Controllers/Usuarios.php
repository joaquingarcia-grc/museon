<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\MuseosModel;

class Usuarios extends BaseController{
    
    protected $sesion;
    protected $usuarios;
    protected $museos;

    public function __construct() {

        $this->sesion = session();
        $this->usuarios = new UsuariosModel();
        $this->museos = new MuseosModel();

    }

    public function index(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

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
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $this->usuarios->delete($id);
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Usuario borrado'];

        echo view('header',$datos);
        echo view('usuarios/avisoborrado');
        echo  view('footer');
    } 

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Usuarios'];

        echo view('header',$datos);
        echo view('usuarios/nuevo');
        echo  view('footer');

    }

    public function insertar(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $email = trim($this->request->getPost('email'));
        $telefono = trim($this->request->getPost('telefono'));

        if (!$denominacion || !$email){
            echo "faltan datos obligatorios";
            return;
        }

        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->first();
        
        if (!$datoUsuario){
        
            $hash = password_hash($this->request->getPost('password'),PASSWORD_DEFAULT);
            $this->usuarios->save([
                'denominacion' => $denominacion,
                'email'  => $email,
                'telefono'  => $telefono,
                'password' => $hash
            ]);
            // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
            return redirect()->to(base_url() . 'usuarios');
            
            }else{
                echo "el usuario ya existe";
            }
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
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
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $this->usuarios->update($id,[
            'denominacion' => $this->request->getPost('denominacion'),
            'email'  => $this->request->getPost('email'),
            'telefono'  => $this->request->getPost('telefono')
        ]);
        // Redirige a la ruta /noticias/tabla (asegúrate de que esa ruta exista y apunte a contenidoTabla)
        return redirect()->to(base_url() . 'usuarios');
    }

    public function papelera(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

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
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $usuarios = $this->usuarios->withDeleted()->find($id);
        
        $usuarioActivo = $this->usuarios->where('denominacion', $usuarios['denominacion'])->first();
        
        if ($usuarioActivo) {
            echo "ya existe un usuario activo con esa denominacion";
            return;
        }
        
        $this->usuarios->update($id,['fecha_baja' => null]);
        return redirect()->to(base_url() . 'usuarios');
    }
}
?>