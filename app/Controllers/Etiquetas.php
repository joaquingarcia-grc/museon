<?php
namespace App\Controllers;
// traemos los modelos que usa el controlador
use App\Models\EtiquetasModel;
use App\Models\MuseosModel;
use App\Models\ActividadesModel;
class Etiquetas extends BaseController{
    // propiedades donde guardamos la sesion y los modelos para usarlos en todas las funciones
    protected $sesion;
    protected $etiquetas;
    protected $museos;
    protected $actividades;
    // el constructor se ejecuta solo cada vez que se llama a este controlador
    public function __construct() {
        // levantamos la sesion y creamos los modelos
        $this->sesion = session();
        $this->etiquetas = new EtiquetasModel();
        $this->museos = new MuseosModel();
        $this->actividades = new ActividadesModel();
    }
    // muestra el listado de etiquetas
    public function index(){
        // si no hay sesion iniciada lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // traemos los permisos del usuario que estan guardados en la sesion
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'l' (listar) en etiqueta lo mandamos a objetos
        if (!in_array('l', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');
        }
        // traemos todas las etiquetas activas (las borradas no vienen por el soft delete)
        $etiquetas = $this->etiquetas->findAll();
        // traemos los datos del museo para el header
        $museos = $this->museos->first();
        // armamos los datos que le pasamos a las vistas
        $datos = [ 'museos'=>$museos,
                  'etiquetas'=>$etiquetas,
                  'titulo' => 'Listado de etiquetas'];
        // cargamos las vistas: header, listado y footer
        echo view('header',$datos);
        echo view('etiquetas/listado');
        echo  view('footer');
    }
    // borra una etiqueta (borrado logico), se llama por ajax y responde json
    public function borrar($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'b' (borrar) cortamos y devolvemos el error en json
        if (!in_array('b', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }
        // borramos la etiqueta (como el modelo tiene soft delete solo le pone la fecha_baja)
        $this->etiquetas->delete($id);
        // guardamos en actividades que el usuario borro esta etiqueta (accion 'b')
        $this->actividades->save([
            'id_usuario' => $this->sesion->get('id'),
            'tabla'      => 'etiquetas',
            'id_dato'    => $id,
            'accion'     => 'b'
        ]);
        // buscamos la etiqueta incluyendo las borradas
        $etiquetas = $this->etiquetas->withDeleted()->find($id);
        // respondemos con un json de exito
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Etiqueta eliminada correctamente'
        ]);
    }
    // muestra el formulario para cargar una etiqueta nueva
    public function nuevo(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'a' (alta) lo mandamos a objetos
        if (!in_array('a', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');
        }
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Etiquetas'];
        // cargamos las vistas
        echo view('header',$datos);
        echo view('etiquetas/nuevo');
        echo  view('footer');
    }
    // guarda la etiqueta nueva, se llama por ajax y responde json
    public function insertar(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'a' (alta) cortamos con el error en json
        if (!in_array('a', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }
        // agarramos la denominacion del post, en minuscula y sin espacios de mas
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        // revisamos que no venga vacia
        if (empty($denominacion)) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacíos']);
            exit;
        }
        // buscamos si ya existe esa denominacion (tambien entre las borradas)
        $datoEtiqueta = $this->etiquetas->where('denominacion', $denominacion)->withDeleted()->first();
        // si no existe la guardamos
        if (empty($datoEtiqueta)){
            $this->etiquetas->save([
                'denominacion' => $denominacion,
            ]);
            // sacamos el id del registro que se acaba de insertar
            $id = $this->etiquetas->getInsertID();
            // guardamos en actividades que el usuario dio de alta esta etiqueta (accion 'a')
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'etiquetas',
                'id_dato'    => $id,
                'accion'     => 'a'
            ]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' Guardado con éxito']);
            exit;
        }else{
            // si ya existe vemos si esta activa o en la papelera (fecha_baja)
            if(empty($datoEtiqueta['fecha_baja'])){
                // existe y esta activa
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                // existe pero esta en la papelera, avisamos para que la recupere
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recupérelo']);
            }exit;
        }return redirect()->to(base_url() . 'etiquetas');
    }
    // muestra el formulario para editar una etiqueta
    public function editar($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'm' (modificar) lo mandamos a objetos
        if (!in_array('m', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');
        }
        // 1. Trae el cliente de la BD
        // (en realidad trae la etiqueta que coincide con el id)
        $etiquetas = $this->etiquetas->where('id', $id)->first();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
            'etiquetas'     => $etiquetas, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar etiqueta'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('etiquetas/editar');
        echo view('footer');
    }
    // guarda los cambios de una etiqueta, se llama por ajax y responde json
    public function actualizar($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'm' (modificar) cortamos con el error en json
        if (!in_array('m', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }
        // agarramos la denominacion del post, en minuscula y sin espacios de mas
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        // revisamos que no venga vacia
        if (empty($denominacion)) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacíos']);
            exit;}
        // Busca si existe OTRO registro (id distinto) con la misma denominación
        $datoEtiqueta = $this->etiquetas->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        // si no hay otra con esa denominacion, actualizamos
        if (empty($datoEtiqueta)) {
            $this->etiquetas->update($id, [
                'denominacion' => $denominacion,
            ]);
            // guardamos en actividades que el usuario modifico esta etiqueta (accion 'm')
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'etiquetas',
                'id_dato'    => $id,
                'accion'     => 'm'
            ]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' actualizada con éxito']);
            exit;
        }else{
            // si ya existe otra con esa denominacion vemos si esta activa o en la papelera
            if(empty($datoEtiqueta['fecha_baja'])){
                // existe y esta activa
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                // existe pero esta en la papelera, avisamos para que la recupere
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recupérelo']);
            }exit;
        }return redirect()->to(base_url() . 'etiquetas');
    }
    // muestra la papelera con las etiquetas borradas
    public function papelera(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'l' (listar) lo mandamos a objetos
        if (!in_array('l', $permisos['etiqueta'])){
            return redirect()->to(base_url() . 'objetos');
        }
        // traemos solo las etiquetas que estan borradas (con fecha_baja)
        $etiquetas = $this->etiquetas->onlyDeleted()->findAll();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
            'etiquetas'     => $etiquetas, // <--- Aquí enviamos los datos
            'titulo'      => 'Etiquetas borradas'
        ];
        echo view('header', $datos);
        echo view('etiquetas/papelera');
        echo view('footer');
    }
    // recupera una etiqueta de la papelera, se llama por ajax y responde json
    public function recuperacion($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'b' (borrar) cortamos con el error en json
        if (!in_array('b', $permisos['etiqueta'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }
        // traemos la etiqueta borrada y despues buscamos si hay una activa con la misma denominacion
        $etiquetas = $this->etiquetas->withDeleted()->find($id);
        $etiquetaActiva = $this->etiquetas->where('denominacion', $etiquetas['denominacion'])->first();
        // si ya hay una activa con ese nombre no la dejamos recuperar
        if ($etiquetaActiva){
            echo json_encode(['exito' => false, 'mensaje' => 'Dato activo, No se puede recuperar']);
            exit;
        }else{
            // la recuperamos sacandole la fecha_baja
            $this->etiquetas->update($id, ['fecha_baja' => null]);
            // guardamos en actividades que el usuario recupero esta etiqueta (accion 'r')
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'etiquetas',
                'id_dato'    => $id,
                'accion'     => 'r'
            ]);
            echo json_encode(['exito' => true   , 'id' => $id, 'mensaje' => 'Se actualizó correctamente']);
            exit;
        }
    }
    // API: devuelve en JSON los etiquetas activos, del más nuevo al más viejo
    public function api_etiquetas(){
        if (!$this->puedeVer('etiqueta')) {
            return $this->accesoDenegado();
        }
        // traemos las activas ordenadas por id de mayor a menor
        $etiquetas = $this->etiquetas->orderBy('id','DESC')->findAll();
        $datos = ['etiquetas'=>$this->ocultarFechaAlta($etiquetas, 'etiqueta')];
        // las devolvemos en json
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los etiquetas borrados (papelera)
    public function api_etiquetas_borrados(){
        if (!$this->puedeVer('etiqueta')) {
            return $this->accesoDenegado();
        }
        // traemos solo las borradas
        $etiquetas = $this->etiquetas->onlyDeleted()->findAll();
        $datos = ['etiquetas'=>$this->ocultarFechaAlta($etiquetas, 'etiqueta')];
        // las devolvemos en json
        return $this->response->setJSON($datos);
    }
}
?>