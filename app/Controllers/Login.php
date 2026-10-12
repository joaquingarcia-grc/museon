<?php

namespace App\Controllers;

use App\Models\MuseosModel;
use App\Models\UsuariosModel;
use App\Models\PermisosModel;

class Login extends BaseController{
    
    protected $museos;
    protected $usuarios;
    protected $permisos;

    public function __construct(){
        $this->museos = new MuseosModel();
        $this->usuarios = new UsuariosModel();
        $this->permisos = new PermisosModel();
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
        
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $password = $this->request->getPost('password');

        if (empty($denominacion) || empty($password)){
          echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos']);
          exit;  
        }
        
        $datosUsuario = $this->usuarios->where('denominacion',$denominacion)->first();
        
        if(!empty($datosUsuario)){
            $permisos = $this->permisos->select('objeto, etiqueta, atributo, usuario')
                                        ->where('id_rol', $datosUsuario['id_rol'])
                                        ->first();

            $permisosSesionTabla = [];
            foreach ( ['usuario', 'objeto', 'atributo', 'etiqueta'] as $tabla) {
                if (!empty($permisos[$tabla])){
                    $permisosSesionTabla[$tabla] = explode(',',$permisos[$tabla]);
                }else{
                    $permisosSesionTabla[$tabla] = [];
                }
            }
            if(password_verify($password, $datosUsuario['password'])){
                $data_sesion = [
                    'id' => $datosUsuario['id'],
                    'denominacion' => $datosUsuario['denominacion'],
                    'permisos' => $permisosSesionTabla,
                    'rol' => $datosUsuario['id_rol']
                ];
                if ($datosUsuario['id_rol'] == 1){
                    $url = 'usuarios';
                }else{
                    $url = 'objetos';
                }
                $sesion = session();
                $sesion->set($data_sesion);
                echo json_encode(['exito' => true, 'mensaje' => 'Bienvenido','redirigir' => base_url($url)]);
                exit;              
            }else{
                echo json_encode(['exito' => false, 'mensaje' => 'Credecial incorrecta']);
                exit;
            }   
        }else{
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no se encuentra registrado']);
            exit;
        }
    }

    public function salir(){
        $sesion = session();
        $sesion->destroy();

        return redirect()->to(base_url() . 'registro/');
    }

}