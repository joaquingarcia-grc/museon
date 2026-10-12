<?php

namespace App\Controllers;

// Importamos los modelos que usa este controlador
use App\Models\UsuariosModel;
use App\Models\MuseosModel;
use App\Models\PermisosModel;

class Usuarios extends BaseController{
    
    // Propiedades: guardan la sesión y los modelos para usarlos en todos los métodos
    protected $sesion;
    protected $usuarios;
    protected $museos;
    protected $permisos;

    public function __construct() {
        // Iniciamos la sesión y creamos las instancias de los modelos
        $this->sesion = session();
        $this->usuarios = new UsuariosModel();
        $this->museos = new MuseosModel();
        $this->permisos = new PermisosModel();
    }

    // Lista los usuarios activos
    public function index(){
        // Si no hay un usuario logueado, lo mandamos a la pantalla de registro/login
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
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
        echo  view('footer');
    }

    // Borrado lógico de un usuario (se llama por AJAX)
    public function borrar($id){
        
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');

        if (in_array('b', $permisos['usuario'])) {
            $this->usuarios->delete($id);
            echo json_encode(['exito' => true, 'mensaje' => 'Usuario borrado exitosamente']);
            exit;
        }else{
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

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }

        // Datos del museo para el header
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
                'titulo' => 'Formulario de Usuarios'];

        echo view('header',$datos);
        echo view('usuarios/nuevo');
        echo  view('footer');

    }

    // Guarda un usuario nuevo (se llama por AJAX y responde JSON)
    public function insertar(){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Indicamos que la respuesta va a ser JSON
        header('Content-Type: application/json');
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['usuario'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }
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
        // Buscamos si ya existe esa denominación, incluyendo los que están en la papelera
        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->withDeleted()->first();
        
        if (empty($datoUsuario)){
            // No existe: ciframos la contraseña y guardamos
            $hash = password_hash($password ,PASSWORD_DEFAULT);
            $this->usuarios->save([
                'denominacion' => $denominacion,
                'email'  => $email,
                'telefono'  => $telefono,
                'password' => $hash
            ]);
            // Obtenemos el id del registro recién insertado y respondemos éxito
            $id = $this->usuarios->getInsertID();
            echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => ' Guardado con éxito']);
            exit;
        }else{
            // Ya existe: si fecha_baja está vacía está activo, si no, está en la papelera
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Usuario existente']);                
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
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // 1. Trae el usuarios de la BD
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
    // Actualiza un usuario existente (AJAX, responde JSON)
    public function actualizar($id){
        // Verificación de sesión
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
                
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['usuario'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorizaciòn']);
            exit;    
        }

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
            if (strlen($password) < 8) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres']);
            exit;
        }
            if (empty(strpbrk($password, '#!*@$%&?¿'))) {
            echo json_encode(['exito' => false, 'mensaje' => 'La contraseña debe tener al menos un carácter especial']);
            exit;
        }}
        // Buscamos si OTRO usuario (id distinto) ya usa esa denominación, incluso en la papelera   
        $datoUsuario = $this->usuarios->where('denominacion', $denominacion)->where('id !=', $id)->withDeleted()->first();
        
        if (empty($datoUsuario)){
            // Nadie más la usa: armamos los datos a actualizar
            $datoActualizar = [
                'denominacion' => $denominacion,
                'email' => $email,
                'telefono' => $telefono
            ];
            // Solo cambiamos la contraseña si se escribió una nueva
            if (!empty($password)){
                $datoActualizar['password']= password_hash($password, PASSWORD_DEFAULT);  
            }
            //Aca se genera el update
            $this->usuarios->update($id, $datoActualizar);
            echo json_encode(['exito' => true, 'mensaje' => ' Actualizado con éxito']);
            exit;
        }else{
            // Otro usuario la tiene: avisamos si está activo o en la papelera
            if(empty($datoUsuario['fecha_baja'])){
                echo json_encode(['exito' => false,'papelera' => false, 'mensaje'=>'Usuario existente']);                
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
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('l', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        // onlyDeleted() trae solo los registros con borrado lógico
        $usuarios = $this->usuarios->onlyDeleted()->findAll();
        //Datos del museo
        $museos = $this->museos->first();

        $datos = [ 'museos'=>$museos,
            'usuarios'     => $usuarios, // <--- Aquí enviamos los datos
            'titulo'      => 'Usuarios borrados'
        ];
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
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['usuario'])){
            return redirect()->to(base_url() . 'objetos');    
        }

        // Traemos el usuario borrado (withDeleted incluye los borrados)
        $usuarios = $this->usuarios->withDeleted()->find($id);
        $usuarioActivo = $this->usuarios->where('denominacion', $usuarios['denominacion'])->first();
        // Buscamos si ya hay un usuario ACTIVO con la misma denominación
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
    // API: devuelve en JSON los usuarios activos, del más nuevo al más viejo
    public function api_usuarios(){
        $usuarios = $this->usuarios->orderBy('id','DESC')->findAll();
        $datos = ['usuarios'=>$usuarios];
        return $this->response->setJSON($datos);
    }
    // API: devuelve en JSON los usuarios borrados (papelera)
    public function api_usuarios_borrados(){
        $usuarios = $this->usuarios->onlyDeleted()->findAll();
        $datos = ['usuarios'=>$usuarios];
        return $this->response->setJSON($datos);
    }
}
?>