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

        $datos = [ 
            'museos'   => $museos,
            'usuarios' => $usuarios,
            'titulo'   => 'Listado de usuarios'
        ];

        echo view('header', $datos);
        echo view('usuarios/listado');
        echo view('footer');
    }

    public function borrar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $this->usuarios->delete($id);
        $usuario = $this->usuarios->withDeleted()->find($id); 
        return $this->response->setStatusCode(200);
    } 

    public function nuevo(){

        //if(!isset($this->sesion->id)){
        //    return redirect()->to(base_url() . "registro/");
        //}

        $museos = $this->museos->first();

        $datos = [ 
            'museos' => $museos,
            'titulo' => 'Formulario de Usuarios'
        ];

        echo view('header', $datos);
        echo view('usuarios/nuevo');
        echo view('footer');

    }

    public function insertar(){
        
        //if(!isset($this->sesion->id)){
        //    return redirect()->to(base_url() . "registro/");
        //}

        header('Content-Type: application/json');
        
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $email        = trim($this->request->getPost('email'));
        $telefono     = trim($this->request->getPost('telefono'));
        $password     = trim($this->request->getPost('password'));

        if (empty($denominacion) || empty($email)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios (Usuario o Email)']);
            exit;
        }
        
        if (empty($password)) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña es obligatoria']);
            exit;
        }

        // Se han eliminado las validaciones de longitud y caracteres especiales.

        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->withDeleted()->first();
        
        if (empty($datoUsuario)){
        
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->usuarios->save([
                'denominacion' => $denominacion,
                'email'        => $email,
                'telefono'     => $telefono,
                'password'     => $hash
            ]);
            $id = $this->usuarios->getInsertID();
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => 'Guardado con éxito']);
            exit;
        }else{
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false, 'papelera' => false, 'mensaje' => 'Usuario existente']);                
            }else{
                echo json_encode(['exito' => false, 'papelera' => true, 'mensaje' => 'Usuario existente en la papelera, recupérelo']);
            }
            exit;
        }
        return redirect()->to(base_url() . 'usuarios');
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $usuarios = $this->usuarios->where('id', $id)->first();
        $museos   = $this->museos->first();

        $datos = [ 
            'museos'   => $museos,
            'usuarios' => $usuarios,
            'titulo'   => 'Editar usuario'
        ];

        echo view('header', $datos);
        echo view('usuarios/editar');
        echo view('footer');
    }

    public function actualizar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        header('Content-Type: application/json');
        
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $email        = trim($this->request->getPost('email'));
        $telefono     = trim($this->request->getPost('telefono'));
        $password     = trim($this->request->getPost('password'));

        if (empty($denominacion) || empty($email)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
            exit;
        }

        // Se eliminaron las restricciones de la contraseña al actualizar.
            
        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        
        if (empty($datoUsuario)){
            $datoActualizar = [
                'denominacion' => $denominacion,
                'email'        => $email,
                'telefono'     => $telefono
            ];
            if (!empty($password)){
                $datoActualizar['password'] = password_hash($password, PASSWORD_DEFAULT);  
            }
            $this->usuarios->update($id, $datoActualizar);
            echo json_encode(['exito' => true, 'mensaje' => 'Actualizado con éxito']);
            exit;
        }else{
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false, 'papelera' => false, 'mensaje' => 'Usuario existente']);                
            }else{
                echo json_encode(['exito' => false, 'papelera' => true, 'mensaje' => 'Usuario existente en la papelera, recupérelo']);
            }
            exit;
        }
        return redirect()->to(base_url() . 'usuarios');

    }

    public function papelera(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $usuarios = $this->usuarios->onlyDeleted()->findAll();
        $museos   = $this->museos->first();

        $datos = [ 
            'museos'   => $museos,
            'usuarios' => $usuarios,
            'titulo'   => 'Usuarios borrados'
        ];

        echo view('header', $datos);
        echo view('usuarios/papelera');
        echo view('footer');
    }

    public function recuperacion($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        header('Content-Type: application/json');

        $usuarios      = $this->usuarios->withDeleted()->find($id);
        $usuarioActivo = $this->usuarios->where('denominacion', $usuarios['denominacion'])->first();
        
        if ($usuarioActivo) {
            echo json_encode(['exito' => false, 'mensaje' => 'Usuario activo, No se puede recuperar']);
            exit;
        }else{
            $this->usuarios->update($id, ['fecha_baja' => null]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => 'Usuario actualizado correctamente']);
            exit;
        }
        return redirect()->to(base_url() . 'usuarios');
    }
}