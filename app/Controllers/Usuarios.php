<?php
namespace App\Controllers;
// Importamos los modelos que usa este controlador
use App\Models\UsuariosModel;
use App\Models\MuseosModel;
use App\Models\PermisosModel;
use App\Models\RolesModel;
class Usuarios extends BaseController{
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
            $this->usuarios->delete($id);
            echo json_encode(['exito' => true, 'mensaje' => 'Usuario borrado exitosamente']);
            exit;
        }else{
            // No tiene permiso: respondemos error
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
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
        $roles = $this->roles->findAll();
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
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
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
        // Si no mandan rol, le ponemos el 3 por defecto
        if (empty($rol)) {
            $rol = 3;
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
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Usuario existente en la papelera, recuperelo']);
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
        $roles = $this->roles->findAll();
        // 1. Trae el usuarios de la BD
        $usuarios = $this->usuarios->where('id', $id)->first();
        // Datos del museo para el header
        $museos = $this->museos->first();
        // Armamos el arreglo de datos que se envía a las vistas
        $datos = [  'museos'=>$museos,
                    'usuarios'=> $usuarios, // <--- Aquí enviamos los datos
                    'titulo'=> 'Editar usuario',
                    'roles'=> $roles
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
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
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
        // Si no mandan rol, le ponemos el 3 por defecto
        if (empty($rol)) {
            $rol = 3;
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
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Usuario existente en la papelera, recuperelo']);
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
        // Excluimos al logueado y ordenamos por id descendente
        $usuarios = $this->usuarios->where('id !=', $this->sesion->id)->orderBy('id','DESC')->findAll();
        $datos = ['usuarios'=>$usuarios];
        // Respondemos en JSON
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los usuarios borrados (papelera) menos el que esta logueado
    public function api_usuarios_borrados(){
        // Excluimos al logueado y traemos solo los borrados
        $usuarios = $this->usuarios->where('id !=', $this->sesion->id)->onlyDeleted()->findAll();
        $datos = ['usuarios'=>$usuarios];
        // Respondemos en JSON
        return $this->response->setJSON($datos);
    }
    // Genera y descarga un zip con la base de datos y los archivos/imágenes
    public function copia_seguridad(){
        // Verificar sesión
        if (!isset($this->sesion->id)) {
            return $this->response
                ->setStatusCode(401)
                ->setBody('Sesión no válida');
        }
        // Carpetas reales dentro de public/ que se van a meter en el zip
        $carpetas = [
            'file' => FCPATH . 'file/',
            'imagenes/objetos' => FCPATH . 'imagenes/objetos/'
        ];
        // Crear ZIP (se guarda temporalmente en writable)
        $rutaZip = WRITEPATH . 'copia_seguridad.zip';
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
            $crear = $db->query("SHOW CREATE TABLE `$tabla`")->getRowArray();
            // Armamos el drop y el create de la tabla
            $sql .= "DROP TABLE IF EXISTS `$tabla`;\n";
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
                $sql .= "INSERT INTO `$tabla` VALUES (" . implode(', ', $valores) . ");\n";
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
            // Recorremos la carpeta y sus subcarpetas
            $archivos = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $ruta,
                    \FilesystemIterator::SKIP_DOTS
                )
            );
            foreach ($archivos as $archivo) {
                // Solo agregamos archivos, no carpetas
                if ($archivo->isFile()) {
                    // Ruta que va a tener el archivo dentro del zip
                    $rutaRelativa = $nombre . '/' .
                        substr($archivo->getPathname(), strlen($ruta));
                    // Agregamos el archivo al zip
                    $zip->addFile(
                        $archivo->getPathname(),
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
        // 3. Descargar copia de seguridad con la fecha en el nombre
        return $this->response
            ->download($rutaZip, null)
            ->setFileName('copia_seguridad_' . date('Y-m-d') . '.zip');
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