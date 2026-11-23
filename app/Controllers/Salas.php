<?php
namespace App\Controllers;
use App\Models\MuseosModel;
use App\Models\UsuariosModel;
use App\Models\SalasModel;
use App\Models\ObjetosModel;

class Salas extends BaseController{ 

    protected $objetos;
    protected $sesion;
    protected $museos;
    protected $usuarios;
    protected $salas;
    public function __construct(){
        $this->sesion = session();
        $this->objetos = new ObjetosModel();
        $this->museos = new MuseosModel();
        $this->usuarios = new UsuariosModel();
        $this->salas = new SalasModel();
    }
    public function index(){
        // si no esta logueado lo mando al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no puede listar salas lo mando a objetos
        if (!in_array('l', $permisos['sala'])){
            return redirect()->to(base_url() . 'objetos');
        }
        $objetos = $this->objetos->findAll();
        $salas = $this->salas->findAll();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Salas',
                  'salas' => $salas,
                  'objetos' => $objetos];
        echo view('header',$datos);
        echo view('salas/salas');
        echo  view('footer');
    }
    public function insertar(){
        // si no esta logueado no entra a la funcion
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene permiso de alta no guardo nada
        if (!in_array('a', $permisos['sala'])){
            return $this->response->setStatusCode(400)->setJSON(['mensaje'=>'Usted no tiene la autorización']);
        }
        $denominacion = trim($this->request->getPost('denominacion'));
        $descripcion = trim($this->request->getPost('descripcion'));
        $id_pertenece = $this->request->getPost('id_pertenece');
        // si no escriben denominacion no guardo nada
        if(empty($denominacion)){
            return $this->response->setStatusCode(400)->setJSON(['mensaje'=>'Falta la denominación']);
        }
        // busco si ya existe una con ese nombre (tambien en la papelera)
        $datoSala = $this->salas->where('denominacion', $denominacion)->withDeleted()->first();
        if(!empty($datoSala)){
            if(empty($datoSala['fecha_baja'])){
                return $this->response->setStatusCode(400)->setJSON(['papelera'=>false, 'mensaje'=>'Dato existente']);
            }else{
                return $this->response->setStatusCode(400)->setJSON(['papelera'=>true, 'mensaje'=>'Dato existente en la papelera, recupérelo']);
            }
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
            'mensaje'=>'Ubicación insertada correctamente',
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
        // si no tiene permiso de modificar corto aca
        if (!in_array('m', $permisos['sala'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }
        $denominacion = trim($this->request->getPost('denominacion'));
        $descripcion = trim($this->request->getPost('descripcion'));
        if (empty($denominacion)) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacíos']);
            exit;
        }
        // busco si existe OTRA sala (id distinto) con el mismo nombre
        $datoSala = $this->salas->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        if (!empty($datoSala)) {
            if(empty($datoSala['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recupérelo']);
            }
            exit;
        }
        $this->salas->update($id, [
            'denominacion' => $denominacion,
            'descripcion'  => $descripcion
        ]);
        echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => 'Ubicación actualizada correctamente']);
        exit;
    }
    public function borrar($id){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene permiso de borrar no sigo
        if (!in_array('b', $permisos['sala'])){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Usted no tiene la autorización'
            ]);
        }
        // cuento lo que tiene adentro esta sala
        $cantidad = $this->salas->where('id_pertenece', $id)->countAllResults();
        // si tiene algo adentro no la dejo borrar y aviso que primero hay que vaciarla
        if($cantidad > 0){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Primero tenés que borrar lo que hay adentro de esta ubicación para poder borrarla'
            ]);
        }
        $this->salas->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Ubicación eliminada correctamente'
        ]);
    }
    public function papelera(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // para ver la papelera tiene que poder listar
        if (!in_array('l', $permisos['sala'])){
            return redirect()->to(base_url() . 'objetos');
        }
        $salas = $this->salas->findAll();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Papelera',
                  'salas' => $salas];
        echo view('header',$datos);
        echo view('salas/papelera');
        echo  view('footer');
    }
    public function recuperacion($id){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // recuperar usa el mismo permiso que borrar
        if (!in_array('b', $permisos['sala'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }
        $sala = $this->salas->withDeleted()->find($id);
        // si no existe la sala corto
        if (empty($sala)){
            echo json_encode(['exito' => false, 'mensaje' => 'La ubicación no existe']);
            exit;
        }
        // si ya hay una activa con el mismo nombre no la dejo recuperar
        $salaActiva = $this->salas->where('denominacion', $sala['denominacion'])->first();
        if ($salaActiva){
            echo json_encode(['exito' => false, 'mensaje' => 'Dato activo, No se puede recuperar']);
            exit;
        }
        if (!empty($sala['id_pertenece'])){
            $padre = $this->salas->find($sala['id_pertenece']);
            if (!$padre){
                echo json_encode(['exito' => false, 'mensaje' => 'La ubicación donde estaba está en la papelera, recupérela primero']);
                exit;
            }
        }
        $this->salas->update($id, ['fecha_baja' => null]);
        echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => 'Ubicación recuperada correctamente']);
        exit;
    }
    public function hijas(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('sala')) {
            return $this->accesoDenegado();
        }
        $id = $this->request->getPost('id');
        // la sala seleccionada (para mostrar su descripcion) y lo que tiene adentro
        $sala = $this->salas->find($id);
        $hijas = $this->salas->where('id_pertenece', $id)->orderBy('denominacion', 'asc')->findAll();
        // los objetos que estan en esta sala
        $objetos = $this->puedeVer('objeto') ? $this->objetos->where('id_ubicacion', $id)->orderBy('denominacion', 'asc')->findAll() : [];
        if ($sala) {
            $sala = $this->ocultarFechaAlta([$sala], 'sala')[0];
        }
        $hijas = $this->ocultarFechaAlta($hijas, 'sala');
        $objetos = $this->ocultarFechaAlta($objetos, 'objeto');
        return $this->response->setJSON(['sala'=>$sala, 'hijas'=>$hijas, 'objetos'=>$objetos]);
    }
    // trae los objetos que todavía no estan en ninguna sala (id_ubicacion en null)
    public function objetosLibres(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('sala') || !$this->puedeVer('objeto')) {
            return $this->accesoDenegado();
        }
        $objetos = $this->objetos->where('id_ubicacion IS NULL')->orderBy('denominacion', 'asc')->findAll();
        return $this->response->setJSON(['objetos'=>$this->ocultarFechaAlta($objetos, 'objeto')]);
    }
    // le asigna a un objeto la sala en la que estoy parado
    public function asignarObjeto(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('objeto')) {
            return $this->accesoDenegado();
        }
        $permisos = $this->sesion->get('permisos');
        // agregar un objeto a una sala usa el permiso de alta de salas
        if (!in_array('a', $permisos['sala'])){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Usted no tiene la autorización'
            ]);
        }
        $id_objeto = $this->request->getPost('id_objeto');
        $id_ubicacion = $this->request->getPost('id_ubicacion');
        // si falta alguno de los dos no hago nada
        if(empty($id_objeto) || empty($id_ubicacion)){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Faltan datos'
            ]);
        }
        // me fijo que el objeto exista
        $objeto = $this->objetos->find($id_objeto);
        if(empty($objeto)){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'El objeto no existe'
            ]);
        }
        $this->objetos->update($id_objeto, ['id_ubicacion' => $id_ubicacion]);
        return $this->response->setJSON([
            'exito' => true,
            'mensaje' => 'Objeto agregado correctamente'
        ]);
    }
    // saca un objeto de la sala, queda con id_ubicacion en null
    public function quitarObjeto($id){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('objeto')) {
            return $this->accesoDenegado();
        }
        $permisos = $this->sesion->get('permisos');
        // sacar un objeto usa el permiso de borrar de salas
        if (!in_array('b', $permisos['sala'])){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Usted no tiene la autorización'
            ]);
        }
        // me fijo que el objeto exista
        $objeto = $this->objetos->find($id);
        if(empty($objeto)){
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'El objeto no existe'
            ]);
        }
        $this->objetos->update($id, ['id_ubicacion' => null]);
        return $this->response->setJSON([
            'exito' => true,
            'mensaje' => 'Objeto sacado de la sala correctamente'
        ]);
    }
    public function api_salas_borrados(){
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('sala')) {
            return $this->accesoDenegado();
        }
        $salas = $this->salas->onlyDeleted()->findAll();
        $datos = ['salas'=>$this->ocultarFechaAlta($salas, 'sala')];
        return $this->response->setJSON($datos);
    }
}
?>