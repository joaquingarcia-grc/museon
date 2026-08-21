<?php

namespace App\Controllers;

use App\Models\MuseosModel;
use App\Models\UsuariosModel;

class Login extends BaseController{
    
    protected $museos;

    protected $usuarios;

    public function __construct(){

        $this->museos = new MuseosModel();
        
        $this->usuarios = new UsuariosModel();

    }

        public function index(){
        $museos = $this->museos->first();


        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Logueo'];

        echo view('logueo/header',$datos);
        echo view('logueo/registro');
        echo  view('logueo/footer');
    }

    public function validacion(){
        
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $password = $this->request->getPost('password');
        
        $datosUsuario = $this->usuarios->where('denominacion',$denominacion)->first();

        if($datosUsuario != null){
            if(password_verify($password, $datosUsuario['password'])){
                $data_sesion = [
                    'id' => $datosUsuario['id'],
                    'denominacion' => $datosUsuario['denominacion']
                ];
                $sesion = session();
                $sesion->set($data_sesion);

                return redirect()->to(base_url() . 'usuarios/');               
            }else{
                echo "La credecial no es correcta.";
            }
        }else{

            return redirect()->to(base_url() . 'registro/');
        }
    }

    public function salir(){
        $sesion = session();
        $sesion->destroy();

        return redirect()->to(base_url() . 'registro/');
    }

}