<?php

namespace App\Controllers;

use App\Models\MuseosModel;
use App\Models\UsuariosModel;
use App\Models\PermisosModel;
use App\Models\SalasModel;

class Salas extends BaseController{ 
    protected $sesion;
    protected $museos;
    protected $usuarios;
    protected $permisos;
    protected $salas;

    public function __construct(){
        $this->sesion = session();
        $this->museos = new MuseosModel();
        $this->usuarios = new UsuariosModel();
        $this->permisos = new PermisosModel();
        $this->salas = new SalasModel();
    }
    public function index(){

        $salas = $this->salas->findAll();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Salas',
                  'salas' => $salas];

        echo view('header',$datos);
        echo view('salas/salas');
        echo  view('footer');
    }
    public function insertar(){
    
        $denominacion = $this->request->getPost('denominacion');
        $descripcion = $this->request->getPost('descripcion');
        $id_pertenece = $this->request->getPost('id_pertenece');
    
        // si no escriben denominacion no guardo nada
        if(empty($denominacion)){
            return $this->response->setStatusCode(400)->setJSON(['mensaje'=>'Falta la denominacion']);
        }
    
        // si no eligen ubicacion queda colgada del museo (id 1)
        if(empty($id_pertenece)){
            $id_pertenece = 1;
        }
    
        $this->salas->save([
            'denominacion'=>$denominacion,
            'descripcion'=>$descripcion,
            'id_pertenece'=>$id_pertenece
        ]);
    
        // devuelvo el id nuevo y donde quedo para que el js la ponga en el arbol
        return $this->response->setJSON([
            'mensaje'=>'Sala guardada',
            'id'=>$this->salas->getInsertID(),
            'id_pertenece'=>$id_pertenece
        ]);
    }
    public function actualizar($id){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');

        if (!in_array('m', $permisos['sala'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;
        }

        $denominacion = trim($this->request->getPost('denominacion'));
        $descripcion = trim($this->request->getPost('descripcion'));

        if (empty($denominacion)) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }

        $this->salas->update($id, [
            'denominacion' => $denominacion,
            'descripcion'  => $descripcion
        ]);
        echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' actualizada con éxito']);
        exit;
    }

    public function borrar($id){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');

        if (!in_array('b', $permisos['sala'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;
        }
        $this->salas->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Sala eliminada correctamente'
        ]);
    }
    public function hijas(){
        $id = $this->request->getPost('id');
        // la sala seleccionada (para mostrar su descripcion) y lo que tiene adentro
        $sala = $this->salas->find($id);
        $hijas = $this->salas->where('id_pertenece', $id)->orderBy('denominacion', 'asc')->findAll();
        return $this->response->setJSON(['sala'=>$sala, 'hijas'=>$hijas]);
    }
}

?>