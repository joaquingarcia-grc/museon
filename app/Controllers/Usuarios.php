<?php
namespace App\Controllers;
// Importamos los modelos que usa este controlador
use App\Models\UsuariosModel;
use App\Models\MuseosModel;
use App\Models\PermisosModel;
use App\Models\RolesModel;
class Usuarios extends BaseController{
    private const MODULOS_ROL = ['objeto', 'etiqueta', 'atributo', 'usuario', 'sala'];
    // Propiedades: guardan la sesión y los modelos para usarlos en todos los métodos
    protected $sesion;
    protected $usuarios;
    protected $museos;
    protected $permisos;
    protected $roles;
    public function __construct() {
        // Iniciamos la sesión y creamos las instancias de los modelos
        $this->sesion = session();
        $this->usuarios = new UsuariosModel();
        $this->roles = new RolesModel();
        $this->museos = new MuseosModel();
        $this->permisos = new PermisosModel();
    }
    // Lista los usuarios activos
    public function index(){
        // Si no hay un usuario logueado, lo mandamos a la pantalla de registro/login
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Traemos los permisos que se guardaron en la sesión al loguearse
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'l' (listar) de usuario, lo mandamos a objetos
        if (!in_array('l', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // Dos consultas: todos los usuarios activos y el primer registro del museo (para el header)
        $usuarios = $this->usuarios->findAll();
        $museos = $this->museos->first();
        // Armamos el arreglo de datos que se envía a las vistas
        $datos = [ 'museos'=>$museos,
                  'usuarios'=>$usuarios,
                  'titulo' => 'Listado de usuarios'];
        // Mostramos header, listado y footer
        echo view('header',$datos);
        echo view('usuarios/listado');
        echo view('footer');
    }
    // Borrado lógico de un usuario (se llama por AJAX)
    public function borrar($id){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Indicamos que la respuesta va a ser JSON
        header('Content-Type: application/json');
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si tiene el permiso 'b' (borrar) hacemos el borrado lógico
        if (in_array('b', $permisos['usuario'])) {
            $usuario = $this->usuarios->find($id);
            if (!$usuario || !$this->puedeGestionarUsuario($usuario)) {
                echo json_encode(['exito' => false, 'mensaje' => 'No tenés permiso para eliminar este usuario']);
                exit;
            }
            $this->usuarios->delete($id);
            echo json_encode(['exito' => true, 'mensaje' => 'Usuario borrado exitosamente']);
            exit;
        }else{
            // No tiene permiso: respondemos error
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;
        }  
    } 
    // Muestra un formulario vacío para crear un usuario
    public function nuevo(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'a' (agregar) lo mandamos a objetos
        if (!in_array('a', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // Traemos los roles para el select del formulario
        $roles = $this->rolesDisponibles();
        // Datos del museo para el header
        $museos = $this->museos->first();
        // Armamos el arreglo de datos que se envía a las vistas
        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Usuarios',
                'roles' => $roles];
        // Mostramos header, formulario y footer
        echo view('header', $datos);
        echo view('usuarios/nuevo');
        echo view('footer');
    }
    private function rolPermitido($rol): bool {
        if (empty($rol) || empty($rol['id_rol'])) {
            return false;
        }
        if ($this->sesion->get('rol') == 1) {
            return true;
        }
        if ($rol['id_rol'] == 1) {
            return false;
        }
        $permisos = $this->sesion->get('permisos');
        foreach (array_merge(self::MODULOS_ROL, ['actividad']) as $modulo) {
            $acciones = empty($rol[$modulo]) ? [] : explode(',', $rol[$modulo]);
            if (array_diff($acciones, $permisos[$modulo] ?? [])) {
                return false;
            }
        }
        return true;
    }

    private function rolesDisponibles(): array {
        $roles = $this->roles
            ->select('roles.id, roles.denominacion, rol_permiso.id_rol, rol_permiso.objeto, rol_permiso.etiqueta, rol_permiso.atributo, rol_permiso.usuario, rol_permiso.sala, rol_permiso.actividad')
            ->join('rol_permiso', 'rol_permiso.id_rol = roles.id')
            ->orderBy('roles.denominacion', 'ASC')
            ->findAll();
        return array_values(array_filter($roles, function($rol){
            return $this->rolPermitido($rol);
        }));
    }

    private function puedeAsignarRol($id): bool {
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            return false;
        }
        $rol = $this->permisos->where('id_rol', $id)->first();
        return $this->rolPermitido($rol);
    }

    private function puedeGestionarUsuario(array $usuario): bool {
        if (!$this->puedeAsignarRol((string) $usuario['id_rol'])) {
            return false;
        }
        $personalizados = \Config\Database::connect()->table('usuario_permiso')
            ->where('id_usuario', $usuario['id'])->get()->getRowArray();
        return !$personalizados || $this->rolPermitido(['id_rol' => $usuario['id_rol']] + $personalizados);
    }

    private function marcarUsuariosGestionables(array $usuarios): array {
        if (!$usuarios) {
            return $usuarios;
        }
        $roles = array_map('strval', array_column($this->rolesDisponibles(), 'id'));
        $personalizados = \Config\Database::connect()->table('usuario_permiso')
            ->whereIn('id_usuario', array_column($usuarios, 'id'))->get()->getResultArray();
        $porUsuario = array_column($personalizados, null, 'id_usuario');
        foreach ($usuarios as &$usuario) {
            $permisos = $porUsuario[$usuario['id']] ?? null;
            $usuario['puede_gestionar'] = in_array((string) $usuario['id_rol'], $roles, true)
                && (!$permisos || $this->rolPermitido(['id_rol' => $usuario['id_rol']] + $permisos));
        }
        unset($usuario);
        return $usuarios;
    }

    private function validarPermisos($seleccion, $actividad): array {
        if (!is_array($seleccion) || array_diff(array_keys($seleccion), self::MODULOS_ROL)) {
            return ['error' => 'Seleccioná permisos válidos', 'estado' => 400];
        }
        $permisosSesion = $this->sesion->get('permisos');
        $datos = [];
        foreach (self::MODULOS_ROL as $modulo) {
            $acciones = $seleccion[$modulo] ?? [];
            if (!is_array($acciones) || count(array_filter($acciones, 'is_string')) !== count($acciones) || array_diff($acciones, ['a', 'b', 'm', 'l', 'f'])) {
                return ['error' => 'Seleccioná permisos válidos', 'estado' => 400];
            }
            if ($this->sesion->get('rol') != 1 && array_diff($acciones, $permisosSesion[$modulo] ?? [])) {
                return ['error' => 'Solo podés otorgar permisos que tenés habilitados', 'estado' => 403];
            }
            if ($acciones && !in_array('l', $acciones, true)) {
                return ['error' => 'Para crear, editar, eliminar o leer la fecha de alta, habilitá también Leer en ese módulo', 'estado' => 400];
            }
            $datos[$modulo] = implode(',', array_unique($acciones));
        }
        if (!is_string($actividad) || !in_array($actividad, ['', 'l'], true)) {
            return ['error' => 'El permiso de actividad no es válido', 'estado' => 400];
        }
        if ($actividad === 'l' && $this->sesion->get('rol') != 1 && !in_array('l', $permisosSesion['actividad'] ?? [], true)) {
            return ['error' => 'Solo podés otorgar permisos que tenés habilitados', 'estado' => 403];
        }
        $datos['actividad'] = $actividad;
        return ['datos' => $datos];
    }

    public function crearRol(){
        if (!isset($this->sesion->id)) {
            return $this->response->setStatusCode(401)->setJSON(['exito' => false, 'mensaje' => 'Iniciá sesión nuevamente']);
        }
        $permisosSesion = $this->sesion->get('permisos');
        if (!in_array('a', $permisosSesion['usuario'] ?? [], true)) {
            return $this->response->setStatusCode(403)->setJSON(['exito' => false, 'mensaje' => 'No tenés permiso para crear roles']);
        }
        $denominacion = $this->request->getPost('denominacion');
        $seleccion = $this->request->getPost('permisos') ?? [];
        if (!is_string($denominacion) || trim($denominacion) === '' || mb_strlen(trim($denominacion)) > 50) {
            return $this->response->setJSON(['exito' => false, 'mensaje' => 'Ingresá una denominación de hasta 50 caracteres']);
        }
        $denominacion = mb_strtolower(trim($denominacion));
        if ($this->roles->where('denominacion', $denominacion)->first()) {
            return $this->response->setJSON(['exito' => false, 'mensaje' => 'Ya existe un rol con esa denominación']);
        }
        $validacion = $this->validarPermisos($seleccion, $this->request->getPost('actividad') ?? '');
        if (isset($validacion['error'])) {
            return $this->response->setStatusCode($validacion['estado'])->setJSON(['exito' => false, 'mensaje' => $validacion['error']]);
        }
        $datos = $validacion['datos'];
        $db = \Config\Database::connect();
        if (!$db->transBegin()) {
            return $this->response->setStatusCode(500)->setJSON(['exito' => false, 'mensaje' => 'No se pudo iniciar la creación del rol']);
        }
        try {
            $id = $this->roles->insert(['denominacion' => $denominacion], true);
            $guardado = $id && $this->permisos->insert(['id_rol' => $id] + $datos);
            if (!$guardado || $db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setStatusCode(500)->setJSON(['exito' => false, 'mensaje' => 'No se pudo guardar el rol']);
            }
            if (!$db->transCommit()) {
                return $this->response->setStatusCode(500)->setJSON(['exito' => false, 'mensaje' => 'No se pudo guardar el rol']);
            }
        } catch (\Throwable $error) {
            $db->transRollback();
            throw $error;
        }
        return $this->response->setJSON(['exito' => true, 'id' => $id, 'denominacion' => $denominacion, 'permisos' => $datos, 'mensaje' => 'Rol creado correctamente']);
    }
    // Guarda un usuario nuevo (se llama por AJAX y responde JSON)
    public function insertar(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Indicamos que la respuesta va a ser JSON
        header('Content-Type: application/json');
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'a' (agregar) respondemos error y cortamos
        if (!in_array('a', $permisos['usuario'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;    
        }
        // Tomamos los datos del POST y les quitamos espacios; denominación en minúsculas
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $email = trim($this->request->getPost('email'));
        $telefono = trim($this->request->getPost('telefono'));
        $password = trim($this->request->getPost('password'));
        $rol = $this->request->getPost('rol');
        // Denominación y email son obligatorios
        if (empty($denominacion) || empty($email)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios (Usuario o Email)']);
            exit;
        }
        // La contraseña es obligatoria al crear
        if (empty($password)) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña es obligatoria']);
            exit;
        }
        // Mínimo 8 caracteres        
        if (strlen($password) < 8) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres']);
            exit;
        }
        // Debe contener al menos un carácter especial de la lista
        if (empty(strpbrk($password, '#!*@$%&?¿'))) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos un carácter especial']);
            exit;
        }
        if (!$this->puedeAsignarRol($rol)) {
            echo json_encode(['exito' => false, 'mensaje' => 'Elegí un rol que tengas permitido asignar']);
            exit;
        }
        // Buscamos si ya existe esa denominación, incluyendo los que están en la papelera
        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->withDeleted()->first();
        // Si no existe, lo guardamos
        if (empty($datoUsuario)){
            // No existe: ciframos la contraseña y guardamos
            $hash = password_hash($password ,PASSWORD_DEFAULT);
            $this->usuarios->save([
                'denominacion' => $denominacion,
                'email'  => $email,
                'telefono'  => $telefono,
                'id_rol' => $rol,
                'password' => $hash
            ]);
            // Obtenemos el id del registro recién insertado y respondemos éxito
            $id = $this->usuarios->getInsertID();
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => 'Guardado con éxito']);
            exit;
        }else{
            // Ya existe: si fecha_baja está vacía está activo, si no, está en la papelera
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false, 'papelera' => false, 'mensaje' => 'Usuario existente']);                
            }else{
                //Está en la papelera
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Usuario existente en la papelera, recupérelo']);
            }
            exit;
        }
    }
    // Muestra el formulario de edición con los datos del usuario
    public function editar($id){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'm' (modificar) lo mandamos a objetos
        if (!in_array('m', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // Traemos los roles para el select del formulario
        $roles = $this->rolesDisponibles();
        // 1. Trae el usuarios de la BD
        $usuarios = $this->usuarios->where('id', $id)->first();
        if (empty($usuarios) || !$this->puedeGestionarUsuario($usuarios)) {
            return redirect()->to(base_url() . 'usuarios');
        }
        $permisosPersonalizados = \Config\Database::connect()->table('usuario_permiso')
            ->where('id_usuario', $id)->get()->getRowArray();
        // Datos del museo para el header
        $museos = $this->museos->first();
        // Armamos el arreglo de datos que se envía a las vistas
        $datos = [  'museos'=>$museos,
                    'usuarios'=> $usuarios, // <--- Aquí enviamos los datos
                    'titulo'=> 'Editar usuario',
                    'roles'=> $roles,
                    'permisosPersonalizados' => $permisosPersonalizados
        ];
        // Mostramos header, formulario de edición y footer
        echo view('header', $datos);
        echo view('usuarios/editar');
        echo view('footer');
    }
    // Actualiza un usuario existente (AJAX, responde JSON)
    public function actualizar($id){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'm' (modificar) respondemos error y cortamos
        if (!in_array('m', $permisos['usuario'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;    
        }
        // Tomamos los datos del POST y les quitamos espacios; denominación en minúsculas
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $email = trim($this->request->getPost('email'));
        $telefono = trim($this->request->getPost('telefono'));
        $password = trim($this->request->getPost('password'));
        $rol = $this->request->getPost('rol');
        // Denominación y email son obligatorios
        if (empty($denominacion) || empty($email)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
            exit;
        }
       // La contraseña es opcional: solo se valida si el usuario escribió una nueva
        if (!empty($password)){
            // Mínimo 8 caracteres
            if (strlen($password) < 8) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres']);
            exit;
        }
            // Debe contener al menos un carácter especial de la lista
            if (empty(strpbrk($password, '#!*@$%&?¿'))) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos un carácter especial']);
            exit;
        }}
        $usuarioActual = $this->usuarios->find($id);
        if (empty($usuarioActual) || !$this->puedeGestionarUsuario($usuarioActual) || !$this->puedeAsignarRol($rol)) {
            echo json_encode(['exito' => false, 'mensaje' => 'No tenés permiso para editar este usuario o asignar ese rol']);
            exit;
        }
        $personalizar = $this->request->getPost('personalizar_permisos');
        if ($personalizar !== null && !in_array($personalizar, ['0', '1'], true)) {
            return $this->response->setStatusCode(400)->setJSON(['exito' => false, 'mensaje' => 'La opción de permisos no es válida']);
        }
        $permisosPersonalizados = null;
        if ($personalizar === '1') {
            if ((int) $rol === 1) {
                return $this->response->setStatusCode(403)->setJSON(['exito' => false, 'mensaje' => 'No se pueden personalizar los permisos de un administrador']);
            }
            $validacion = $this->validarPermisos($this->request->getPost('permisos') ?? [], $this->request->getPost('actividad') ?? '');
            if (isset($validacion['error'])) {
                return $this->response->setStatusCode($validacion['estado'])->setJSON(['exito' => false, 'mensaje' => $validacion['error']]);
            }
            $permisosPersonalizados = $validacion['datos'];
        }
        // Buscamos si OTRO usuario (id distinto) ya usa esa denominación, incluso en la papelera   
        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        // Si nadie más la usa, actualizamos
        if (empty($datoUsuario)){
            // Nadie más la usa: armamos los datos a actualizar
            $datoActualizar = [
                'denominacion' => $denominacion,
                'email' => $email,
                'telefono' => $telefono,
                'id_rol' => $rol
            ];
            // Solo cambiamos la contraseña si se escribió una nueva
            if (!empty($password)){
                $datoActualizar['password'] = password_hash($password, PASSWORD_DEFAULT);  
            }
            $db = \Config\Database::connect();
            if (!$db->transBegin()) {
                return $this->response->setStatusCode(500)->setJSON(['exito' => false, 'mensaje' => 'No se pudo iniciar la actualización']);
            }
            try {
                $guardado = $this->usuarios->update($id, $datoActualizar);
                if ($guardado && ($personalizar !== null || (string) $usuarioActual['id_rol'] !== $rol)) {
                    $tabla = $db->table('usuario_permiso');
                    $guardado = $tabla->where('id_usuario', $id)->delete();
                    if ($guardado && $permisosPersonalizados !== null) {
                        $guardado = $db->table('usuario_permiso')->insert(['id_usuario' => $id] + $permisosPersonalizados);
                    }
                }
                if (!$guardado || !$db->transStatus()) {
                    $db->transRollback();
                    return $this->response->setStatusCode(500)->setJSON(['exito' => false, 'mensaje' => 'No se pudo actualizar el usuario']);
                }
                if (!$db->transCommit()) {
                    return $this->response->setStatusCode(500)->setJSON(['exito' => false, 'mensaje' => 'No se pudo actualizar el usuario']);
                }
            } catch (\Throwable $error) {
                $db->transRollback();
                throw $error;
            }
            echo json_encode(['exito' => true, 'mensaje' => 'Actualizado con éxito']);
            exit;
        }else{
            // Otro usuario la tiene: avisamos si está activo o en la papelera
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false, 'papelera' => false, 'mensaje' => 'Usuario existente']);                
            }else{
                //Papelera
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Usuario existente en la papelera, recupérelo']);
            }
            exit;
        }
    }
    // Lista los usuarios borrados (papelera)
    public function papelera(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'l' (listar) lo mandamos a objetos
        if (!in_array('l', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // onlyDeleted() trae solo los registros con borrado lógico
        $usuarios = $this->usuarios->onlyDeleted()->findAll();
        //Datos del museo
        $museos = $this->museos->first();
        // Armamos el arreglo de datos que se envía a las vistas
        $datos = [ 
            'museos'   => $museos,
            'usuarios' => $usuarios,
            'titulo'   => 'Usuarios borrados'
        ];
        // Mostramos header, papelera y footer
        echo view('header', $datos);
        echo view('usuarios/papelera');
        echo view('footer');
    }
    // Restaura un usuario de la papelera (AJAX, responde JSON)
    public function recuperacion($id){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Indicamos que la respuesta va a ser JSON
        header('Content-Type: application/json');
        // Traemos los permisos de la sesión
        $permisos = $this->sesion->get('permisos');
        // Si no tiene el permiso 'a' (agregar) lo mandamos a objetos
        if (!in_array('a', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // Traemos el usuario borrado (withDeleted incluye los borrados)
        $usuarios = $this->usuarios->withDeleted()->find($id);
        if (!$usuarios || !$this->puedeGestionarUsuario($usuarios)) {
            return $this->response->setStatusCode(403)->setJSON(['exito' => false, 'mensaje' => 'No tenés permiso para recuperar este usuario']);
        }
        // Buscamos si ya hay un usuario ACTIVO con la misma denominación
        $usuarioActivo = $this->usuarios->where('denominacion', $usuarios['denominacion'])->first();
        // Si hay uno activo con esa denominación hay conflicto
        if ($usuarioActivo) {
            // Hay conflicto: no se puede recuperar
            echo json_encode(['exito' => false, 'mensaje' => 'Usuario activo, No se puede recuperar']);
            exit;
        }else{
            // Sin conflicto: ponemos fecha_baja en null y queda activo de nuevo
            $this->usuarios->update($id,['fecha_baja' => null]);
            echo json_encode(['exito' => true   , 'id' => $id, 'mensaje' => 'Usuario recuperado correctamente']);
            exit;
        }
    }
    // API: devuelve en JSON los usuarios activos menos el que esta logueado, del más nuevo al más viejo
    public function api_usuarios(){
        if (!$this->puedeVer('usuario')) {
            return $this->accesoDenegado();
        }
        // Excluimos al logueado y ordenamos por id descendente
        $usuarios = $this->usuarios->select('id, denominacion, email, telefono, fecha_alta, id_rol')->where('id !=', $this->sesion->id)->orderBy('id','DESC')->findAll();
        $usuarios = $this->ocultarFechaAlta($this->marcarUsuariosGestionables($usuarios), 'usuario');
        $datos = ['usuarios'=>$usuarios];
        // Respondemos en JSON
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los usuarios borrados (papelera) menos el que esta logueado
    public function api_usuarios_borrados(){
        if (!$this->puedeVer('usuario')) {
            return $this->accesoDenegado();
        }
        // Excluimos al logueado y traemos solo los borrados
        $usuarios = $this->usuarios->select('id, denominacion, email, fecha_baja, id_rol')->where('id !=', $this->sesion->id)->onlyDeleted()->findAll();
        $usuarios = $this->marcarUsuariosGestionables($usuarios);
        $datos = ['usuarios'=>$usuarios];
        // Respondemos en JSON
        return $this->response->setJSON($datos);
    }
    // Genera y descarga un zip con la base de datos y los archivos/imágenes
    public function copia_seguridad(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if ($this->sesion->get('rol') != 1) {
            return $this->response->setStatusCode(403)->setBody('Solo un administrador puede descargar la copia de seguridad');
        }
        // Si la extensión zip no está activa (en windows a veces viene comentada en el php.ini)
        if (!class_exists('ZipArchive')) {
            return $this->response
                ->setStatusCode(500)
                ->setBody('La extensión zip de PHP no está activada');
        }
        // Carpetas reales dentro de public/objetos_multimedia/ que se van a meter en el zip
        $carpetas = [
            'file' => FCPATH . 'objetos_multimedia/files/',
            'images' => FCPATH . 'objetos_multimedia/images/'
        ];
        // Crear ZIP (se guarda temporalmente en writable con nombre único para que nunca se reutilice uno viejo)
        $rutaZip = WRITEPATH . 'copia_seguridad_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        // Abrimos el zip; si ya existe lo sobrescribimos
        if ($zip->open($rutaZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return $this->response
                ->setStatusCode(500)
                ->setBody('No se pudo crear el archivo ZIP');
        }
        // 1. Exportar base de datos
        $db = db_connect();  //https://codeigniter4.github.io/CodeIgniter4/develop/database/metadata.html funtes de referencia
        $sql = '';
        // Recorremos todas las tablas de la base
        foreach ($db->listTables() as $tabla) {
            // Traemos el create table de la tabla actual
            $crear = $db->query("show create table `$tabla`")->getRowArray();
            // Armamos el drop y el create de la tabla
            $sql .= "drop table if exists `$tabla`;\n";
            $sql .= $crear['Create Table'] . ";\n\n";
            // Traemos todas las filas de la tabla
            $filas = $db->table($tabla)->get()->getResultArray();
            // Por cada fila armamos un insert
            foreach ($filas as $fila) {
                $valores = [];
                // Escapamos cada valor para que no rompa el sql
                foreach ($fila as $valor) {
                    $valores[] = $db->escape($valor);
                }
                $sql .= "insert into `$tabla` values (" . implode(', ', $valores) . ");\n";
            }
            $sql .= "\n";
        }
        // Metemos el sql dentro del zip como un archivo
        $zip->addFromString('base_de_datos.sql', $sql);
        // 2. Incorporar archivos e imágenes
        foreach ($carpetas as $nombre => $ruta) {
            // Si la carpeta no existe la salteamos
            if (!is_dir($ruta)) {
                continue;
            }
            // Normalizo la ruta base con / (en windows viene con \ y se mezclaba con el /)
            $rutaBase = str_replace('\\', '/', realpath($ruta));
            $rutaBase = rtrim($rutaBase, '/') . '/';
            // Recorremos la carpeta y sus subcarpetas
            $archivos = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $rutaBase,
                    \FilesystemIterator::SKIP_DOTS
                )
            );
            foreach ($archivos as $archivo) {
                // Solo agregamos archivos, no carpetas
                if ($archivo->isFile()) {
                    // Ruta real del archivo, también con /
                    $rutaArchivo = str_replace('\\', '/', $archivo->getPathname());
                    // Ruta que va a tener el archivo dentro del zip
                    $rutaRelativa = $nombre . '/' . substr($rutaArchivo, strlen($rutaBase));
                    // Agregamos el archivo al zip
                    $zip->addFile(
                        $rutaArchivo,
                        $rutaRelativa
                    );
                }
            }
        }
        // Cerrar ZIP
        if (!$zip->close()) {
            return $this->response
                ->setStatusCode(500)
                ->setBody('No se pudo finalizar el archivo ZIP');
        }//https://www.php.net/manual/en/class.ziparchive.php paginas de referencia
        // Leemos el zip recién creado y lo borramos de writable para que no quede ninguno guardado
        $contenido = file_get_contents($rutaZip);
        unlink($rutaZip);
        // 3. Descargar copia de seguridad con la fecha en el nombre
        return $this->response
            ->download('copia_seguridad_' . date('Y-m-d') . '.zip', $contenido);
    }
    // Muestra un formulario vacío para crear un usuario
    public function configuracion(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Trae solo el usuario logueado
        $usuarios = $this->usuarios->find($this->sesion->id);
        // Datos del museo para el header
        $museos = $this->museos->first();
        // Armamos el arreglo de datos que se envía a las vistas
        $datos = [ 'museos'=>$museos,
                'usuarios'=>$usuarios,
                'titulo' => 'Formulario de Usuarios',];
        // Mostramos header, configuración y footer
        echo view('header', $datos);
        echo view('usuarios/configuracion');
        echo view('footer');
    }
    // Actualiza los datos del usuario logueado (AJAX, responde JSON)
    public function actualizar_configuracion(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        // El id sale de la sesión, no viene por la url
        $id = $this->sesion->id;
        // Tomamos los datos del POST y les quitamos espacios; denominación en minúsculas
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $email = trim($this->request->getPost('email'));
        $telefono = trim($this->request->getPost('telefono'));
        $password = trim($this->request->getPost('password'));
        // Denominación y email son obligatorios
        if (empty($denominacion) || empty($email)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
            exit;
        }
        // La contraseña es opcional: solo se valida si el usuario escribió una nueva
        if (!empty($password)){
            // Mínimo 8 caracteres
            if (strlen($password) < 8) {
                echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres']);
                exit;
            }
            // Debe contener al menos un carácter especial de la lista
            if (empty(strpbrk($password, '#!*@$%&?¿'))) {
                echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos un carácter especial']);
                exit;
            }
        }
        // Buscamos si OTRO usuario (id distinto) ya usa esa denominación, incluso en la papelera
        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        // Si nadie más la usa, actualizamos
        if (empty($datoUsuario)){
            // Nadie más la usa: armamos los datos a actualizar
            $datoActualizar = [
                'denominacion' => $denominacion,
                'email' => $email,
                'telefono' => $telefono
            ];
            // Solo cambiamos la contraseña si se escribió una nueva
            if (!empty($password)){
                $datoActualizar['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            //Aca se genera el update
            $this->usuarios->update($id, $datoActualizar);
            echo json_encode(['exito' => true, 'mensaje' => 'Actualizado con éxito']);
            exit;
        }else{
            // Otro usuario la tiene: avisamos si está activo o en la papelera
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false, 'papelera' => false, 'mensaje' => 'Usuario existente']);
            }else{
                //Papelera
                echo json_encode(['exito' => false, 'papelera' => true, 'mensaje' => 'Usuario existente en la papelera']);
            }
            exit;
        }
    }
}