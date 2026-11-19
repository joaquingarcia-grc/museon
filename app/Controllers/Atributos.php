<?php
namespace App\Controllers;
// traemos los modelos que usa el controlador
use App\Models\AtributosModel;
use App\Models\MuseosModel;
use App\Models\PermisosModel;
use App\Models\ActividadesModel;
class Atributos extends BaseController{
    // propiedades donde guardamos la sesion y los modelos para usarlos en todas las funciones
    protected $sesion;
    protected $permisos;
    protected $atributos;
    protected $museos;
    protected $actividades;
    // el constructor se ejecuta solo cada vez que se llama a este controlador
    public function __construct(){
        // levantamos la sesion y creamos los modelos
        $this->sesion = session();
        $this->atributos = new AtributosModel();
        $this->museos = new MuseosModel();
        $this->permisos = new PermisosModel();
        $this->actividades = new ActividadesModel();
    }
    // muestra el listado de atributos
    public function index(){
        // si no hay sesion iniciada lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // traemos los permisos del usuario que estan guardados en la sesion
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'l' (listar) en atributo lo mandamos a objetos
        if (!in_array('l', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');
        }
        // traemos todos los atributos activos (los borrados no vienen por el soft delete)
        $atributos = $this->atributos->findAll();
        // traemos los datos del museo para el header
        $museos = $this->museos->first();
        // armamos los datos que le pasamos a las vistas
        $datos = [ 'museos'=>$museos,
                  'atributos'=>$atributos,
                  'titulo' => 'Listado de atributos'];
        // cargamos las vistas: header, listado y footer
        echo view('header',$datos);
        echo view('atributos/listado');
        echo  view('footer');
    }
    // borra un atributo (borrado logico), se llama por ajax y responde json
    public function borrar($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'b' (borrar) cortamos y devolvemos el error en json
        if (!in_array('b', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;
        }
        // borramos el atributo (como el modelo tiene soft delete solo le pone la fecha_baja)
        $this->atributos->delete($id);
        // guardamos en actividades que el usuario borro este atributo (accion 'b')
        $this->actividades->save([
            'id_usuario' => $this->sesion->get('id'),
            'tabla'      => 'atributos',
            'id_dato'    => $id,
            'accion'     => 'b'
        ]);
        // buscamos el atributo incluyendo los borrados
        $atributo = $this->atributos->withDeleted()->find($id);
        // respondemos con un json de exito
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Atributo eliminado correctamente'
        ]);
    }
    // muestra el formulario para cargar un atributo nuevo
    public function nuevo(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'a' (alta) lo mandamos a objetos
        if (!in_array('a', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');
        }
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de atributos'];
        // cargamos las vistas
        echo view('header',$datos);
        echo view('atributos/nuevo');
        echo  view('footer');
    }
    // guarda el atributo nuevo, se llama por ajax y responde json
    public function insertar(){
        // validacion que no deja ingresar a la funcion del controlador
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'a' (alta) cortamos con el error en json
        if (!in_array('a', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;
        }
        //convertimos los datos que resivimos por post en las variables locales para poder trabajar
        // (denominacion y tipo_dato van en minuscula y sin espacios de mas)
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipo_dato = strtolower(trim($this->request->getPost('tipo_dato')));
        $unidad = trim($this->request->getPost('unidad'));
        //revisemson de no recibir vacios
        if (empty($denominacion) || empty($tipo_dato)){
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }
        // buscamos si ya existe esa denominacion (tambien entre los borrados)
        $datoAtributo = $this->atributos->where('denominacion', $denominacion)->withDeleted()->first();
        // si no existe lo guardamos
        if (empty($datoAtributo)) {
            // la unidad solo se guarda si el tipo es numerico, sino va null
            $this->atributos->save([
                'denominacion' => $denominacion,
                'tipo_dato'    => $tipo_dato,
                'unidad'       => $tipo_dato === 'numerico' ? ($unidad ?: null) : null,
            ]);
            // sacamos el id del registro que se acaba de insertar
            $id = $this->atributos->getInsertID();
            // guardamos en actividades que el usuario dio de alta este atributo (accion 'a')
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'atributos',
                'id_dato'    => $id,
                'accion'     => 'a'
            ]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' Guardado con éxito']);
            exit;
        }else{
            // si ya existe vemos si esta activo o en la papelera (fecha_baja)
            if(empty($datoAtributo['fecha_baja'])){
                // existe y esta activo
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                // existe pero esta en la papelera, avisamos para que lo recupere
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }
            exit;
        }
    }
    // muestra el formulario para editar un atributo
    public function editar($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'm' (modificar) lo mandamos a objetos
        if (!in_array('m', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');
        }
        // 1. Trae el cliente de la BD
        // (en realidad trae el atributo que coincide con el id)
        $atributos = $this->atributos->where('id', $id)->first();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
            'atributos'     => $atributos, // <--- Aquí enviamos los datos
            'titulo'      => 'Editar atributo'
        ];
        // 3. Muestra la vista (la misma que usas para "Nuevo")
        echo view('header', $datos);
        echo view('atributos/editar');
        echo view('footer');
    }
    // guarda los cambios de un atributo, se llama por ajax y responde json
    public function actualizar($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'm' (modificar) cortamos con el error en json
        if (!in_array('m', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;
        }
        // agarramos los datos del post, en minuscula y sin espacios de mas
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $tipoDato     = strtolower(trim($this->request->getPost('tipo_dato')));
        $unidad = trim($this->request->getPost('unidad'));
        // revisamos que no vengan vacios
        if (empty($denominacion)  || empty($tipoDato) ) {
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacios']);
            exit;
        }
        // Busca si existe OTRO registro (id distinto) con la misma denominación
        $datoAtributo = $this->atributos->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        // si no hay otro con esa denominacion, actualizamos
        if (empty($datoAtributo)) {
            // la unidad solo se guarda si el tipo es numerico, sino va null
            $this->atributos->update($id, [
                'denominacion' => $denominacion,
                'tipo_dato'    => $tipoDato,
                'unidad'       => $tipoDato === 'numerico' ? ($unidad ?: null) : null,
            ]);
            // guardamos en actividades que el usuario modifico este atributo (accion 'm')
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'atributos',
                'id_dato'    => $id,
                'accion'     => 'm'
            ]);
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' actualizado con éxito']);
            exit;
        }else{
            // si ya existe otro con esa denominacion vemos si esta activo o en la papelera
            if(empty($datoAtributo['fecha_baja'])){
                // existe y esta activo
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Dato existente']);
            }else{
                // existe pero esta en la papelera, avisamos para que lo recupere
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recuperelo']);
            }
            exit;
        }
    }
    // muestra la papelera con los atributos borrados
    public function papelera(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'l' (listar) lo mandamos a objetos
        if (!in_array('l', $permisos['atributo'])){
            return redirect()->to(base_url() . 'objetos');
        }
        // traemos solo los atributos que estan borrados (con fecha_baja)
        $atributos = $this->atributos->onlyDeleted()->findAll();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
            'atributos'     => $atributos, // <--- Aquí enviamos los datos
            'titulo'      => 'Atributos borrados'
        ];
        echo view('header', $datos);
        echo view('atributos/papelera');
        echo view('footer');
    }
    // recupera un atributo de la papelera, se llama por ajax y responde json
    public function recuperacion($id){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        // si no tiene el permiso 'b' (borrar) cortamos con el error en json
        if (!in_array('b', $permisos['atributo'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;
        }
        // traemos el atributo borrado y despues buscamos si hay uno activo con la misma denominacion
        $atributo = $this->atributos->withDeleted()->find($id);
        $atributoActivo = $this->atributos->where('denominacion', $atributo['denominacion'])->first();
        // si ya hay uno activo con ese nombre no lo dejamos recuperar
        if ($atributoActivo){
            echo json_encode(['exito' => false, 'mensaje' => 'Dato activo, No se puede recuperar']);
            exit;
        }else{
            // lo recuperamos sacandole la fecha_baja
            $this->atributos->update($id, ['fecha_baja' => null]);
            // guardamos en actividades que el usuario recupero este atributo (accion 'r')
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'atributos',
                'id_dato'    => $id,
                'accion'     => 'r'
            ]);
            echo json_encode(['exito' => true   , 'id' => $id, 'mensaje' => 'Atributo recuperado correctamente']);
            exit;
        }
    }
    // API: devuelve en JSON los atributos activos, del más nuevo al más viejo
    public function api_atributos(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // traemos los activos ordenados por id de mayor a menor
        $atributos = $this->atributos->orderBy('id','DESC')->findAll();
        $datos = ['atributos'=>$atributos];
        // los devolvemos en json
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los atributos borrados (papelera)
    public function api_atributos_borrados(){
        // si no hay sesion lo mandamos al registro
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // traemos solo los borrados
        $atributos = $this->atributos->onlyDeleted()->findAll();
        $datos = ['atributos'=>$atributos];
        // los devolvemos en json
        return $this->response->setJSON($datos);
    }
}