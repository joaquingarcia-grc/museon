<?php

namespace App\Controllers;

use App\Models\ObjetosModel;
use App\Models\MuseosModel;
use App\Models\AtributosModel;
use App\Models\EtiquetasModel;
use App\Models\ObjetosAtributosModel;
use App\Models\ObjetosEtiquetasModel;
use App\Models\UsuariosModel;
use App\Models\ActividadesModel;   // <-- NUEVA
use App\Models\PiezasImagenesModel;
use App\Models\PiezasArchivosModel;
use App\Models\SalasModel;

class Objetos extends BaseController {   
    
    protected $sesion;
    protected $objetos;
    protected $museos;
    protected $etiquetas;
    protected $atributos;
    protected $objetosatributos;
    protected $objetosetiquetas;
    protected $actividades;   // <-- NUEVA
    protected $piezasimagenes;
    protected $piezasarchivos;
    protected $usuarios;
    protected $salas;

    public function __construct() {
    
        $this->sesion = session(); 
        $this->usuarios = new UsuariosModel();     
        $this->objetos = new ObjetosModel();
        $this->museos = new MuseosModel();
        $this->etiquetas = new EtiquetasModel();
        $this->atributos = new AtributosModel();
        $this->objetosatributos = new ObjetosAtributosModel();
        $this->objetosetiquetas = new ObjetosEtiquetasModel();
        $this->actividades = new ActividadesModel();   // <-- NUEVA
        $this->piezasimagenes = new PiezasImagenesModel();
        $this->piezasarchivos = new PiezasArchivosModel();
        $this->salas = new SalasModel();
    }

    public function index(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('objeto')) {
            return $this->response->setStatusCode(403)->setBody('No tenés permiso para ver piezas');
        }
        $objetos = $this->objetos->findAll();
        $museos = $this->museos->first();
        $etiquetas = $this->etiquetas
            ->orderBy('denominacion', 'ASC')
            ->findAll();
        $usuariosFiltro = $this->usuarios
            ->select('id, denominacion')
            ->withDeleted()
            ->orderBy('denominacion', 'ASC')
            ->findAll();

        $datos = [ 
            'museos'  => $museos,
            'objetos' => $objetos,
            'etiquetas' => $etiquetas,
            'usuariosFiltro' => $usuariosFiltro,
            'titulo'  => 'Listado de objetos'
        ];

        echo view('header', $datos);
        echo view('objetos/listado');
        echo view('footer');
    }

    public function borrar($id){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('b', $permisos['objeto'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;    
        }
        $this->objetos->delete($id);
        // <-- NUEVA
        $this->actividades->save([
            'id_usuario' => $this->sesion->get('id'),
            'tabla'      => 'objetos',
            'id_dato'    => $id,
            'accion'     => 'b'
        ]);
        $objetos = $this->objetos->withDeleted()->find($id); 
        return $this->response->setStatusCode(200)->setJSON([
            'exito' => true,
            'mensaje' => 'Objeto eliminado correctamente'
        ]);
    }

    public function nuevo(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['objeto'])){
            return redirect()->to(base_url() . 'objetos');    
        }

        $museos = $this->museos->first();
        
        $etiquetas = $this->etiquetas->findAll();
        $atributos = $this->atributos->findAll();

        $datos = [ 
            'museos'    => $museos,
            'etiquetas' => $etiquetas, 
            'atributos' => $atributos, 
            'salas'     => $this->salas->where('id_pertenece IS NOT NULL')->orderBy('denominacion', 'ASC')->findAll(),
            'titulo'    => 'Formulario de objetos'
        ];

        echo view('header', $datos);
        echo view('objetos/nuevo');
        echo view('footer');
    }

    public function insertar(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('a', $permisos['objeto'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;    
        }

        $id_usuario = $this->sesion->get('id');
        $codigo = trim($this->request->getPost('codigo'));
        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $descripcion = trim($this->request->getPost('descripcion'));
        
        if (empty($denominacion) || empty($codigo)){
            echo json_encode(['exito' => false, 'mensaje' => ' Campos vacíos']);
            exit;
        }

        $idUbicacion = $this->request->getPost('id_ubicacion');
        $errorUbicacion = $this->validarUbicacion($idUbicacion, null, $permisos);
        if ($errorUbicacion !== null) {
            echo json_encode(['exito' => false, 'mensaje' => $errorUbicacion]);
            exit;
        }

        $datoObjeto = $this->objetos->where('denominacion', $denominacion)->withDeleted()->first();

        // 1. Guardamos el objeto principal

        if (empty($datoObjeto)) {
            $objetoGuardar = [
                'id_usuario'   => $id_usuario,
                'denominacion' => $denominacion,
                'codigo'       => $codigo,
                'descripcion'  => $descripcion,
                'id_ubicacion' => $idUbicacion === null || $idUbicacion === '' ? null : (int) $idUbicacion
            ];
        } else{
            if (($datoObjeto['fecha_baja'])) {
                echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recupérelo']);
            }
            exit;
        }
        
        
        // Insertamos y obtenemos el id generado
        if($objeto_id = $this->objetos->insert($objetoGuardar, true));

        if (empty($objeto_id)){
            echo json_encode(['exito' => false, 'mensaje' => 'Error al cargar']);
            exit;
        }

        // 2. Si se guardó correctamente, procesamos etiquetas y atributos dinámicos
        if ($objeto_id) {
            
            // --- ETIQUETAS DINÁMICAS (Múltiples seleccionadas) ---
            $etiqueta_ids = $this->request->getPost('etiqueta_ids');
            if (!empty($etiqueta_ids) && is_array($etiqueta_ids)) {
                foreach ($etiqueta_ids as $id_etiqueta) {
                    $this->vincularEtiqueta($objeto_id, $id_etiqueta);
                }
                // <-- NUEVA
                $this->actividades->save([
                    'id_usuario' => $id_usuario,
                    'tabla'      => 'objeto_etiquetas',
                    'id_dato'    => $objeto_id,
                    'accion'     => 'a'
                ]);
            }

            // --- ATRIBUTOS DINÁMICOS
            $atributo_ids     = $this->request->getPost('atributo_ids');
            $atributo_valores = $this->request->getPost('atributo_valores');
            
            if (!empty($atributo_ids) && is_array($atributo_ids)) {
                foreach ($atributo_ids as $id_atributo) {
                    if (isset($atributo_valores[$id_atributo])) {
                        $valor = $atributo_valores[$id_atributo];
                        $this->vincularAtributo($objeto_id, $id_atributo, $valor);
                    }
                }
                // <-- NUEVA
                $this->actividades->save([
                    'id_usuario' => $id_usuario,
                    'tabla'      => 'objeto_atributos',
                    'id_dato'    => $objeto_id,
                    'accion'     => 'a'
                ]);
            }
        }

        $resultado = $this->guardarImagenesPieza($objeto_id);
        $this->definirPortadaPieza($objeto_id, $this->request->getPost('portada'), $resultado['ids']);
        $resultadoArchivos = $this->guardarArchivosPieza($objeto_id);
        
        $id = $this->objetos->getInsertID();
        // <-- NUEVA
        $this->actividades->save([
            'id_usuario' => $id_usuario,
            'tabla'      => 'objetos',
            'id_dato'    => $id,
            'accion'     => 'a'
        ]);
        $mensaje = ' Guardado con éxito';
        $fallidas = $resultado['fallidas'] + $resultadoArchivos['fallidas'];
        if ($fallidas > 0) {
            $mensaje .= ' (pero ' . $fallidas . ' archivo(s) o imagen(es) no se pudieron guardar)';
        }
        echo json_encode(['exito' => true, 'id' => $id, 'mensaje' => $mensaje]);
    }

    public function editar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['objeto'])){
            return redirect()->to(base_url() . 'objetos');    
        }
        
        $objetos = $this->objetos->where('id', $id)->first();
        $museos = $this->museos->first();
        $etiquetas = $this->etiquetas->findAll();
        $atributos = $this->atributos->findAll();
        $galeria = $this->piezasimagenes
            ->where('objeto_id', $id)
            ->orderBy('portada', 'DESC')
            ->orderBy('id', 'ASC')
            ->findAll();
        $archivos = $this->piezasarchivos->where('objeto_id', $id)->orderBy('id', 'ASC')->findAll();

        $datos = [ 
            'museos'                 => $museos,
            'objetos'                => $objetos,
            'etiquetas'              => $etiquetas,
            'atributos'              => $atributos,
            'galeria'                => $galeria,
            'archivos'               => $archivos,
            'salas'                  => $this->salas->where('id_pertenece IS NOT NULL')->orderBy('denominacion', 'ASC')->findAll(),
            'ubicacionActual'        => !empty($objetos['id_ubicacion']) ? $this->salas->withDeleted()->find($objetos['id_ubicacion']) : null,
            'etiquetasSeleccionadas' => $this->obtenerEtiquetasPorObjeto($id),
            'atributosSeleccionados' => $this->obtenerAtributosPorObjeto($id),
            'titulo'                 => 'Editar pieza'
        ];
        
        echo view('header', $datos);
        echo view('objetos/editar');
        echo view('footer');
    }

    public function actualizar($id){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');

        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('m', $permisos['objeto'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;    
        }

        $denominacion = strtolower(trim($this->request->getPost('denominacion')));
        $codigo = trim($this->request->getPost('codigo'));
        $descripcion = trim($this->request->getPost('descripcion'));

        if(empty($denominacion) || empty($codigo)){
            echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
            exit;
        }
        $idUbicacion = $this->request->getPost('id_ubicacion');
        $objetoActual = $this->objetos->find($id);
        if (!$objetoActual) {
            echo json_encode(['exito' => false, 'mensaje' => 'La pieza no existe']);
            exit;
        }
        $errorUbicacion = $this->validarUbicacion($idUbicacion, $objetoActual['id_ubicacion'], $permisos);
        if ($errorUbicacion !== null) {
            echo json_encode(['exito' => false, 'mensaje' => $errorUbicacion]);
            exit;
        }
        $datoObjeto = $this->objetos->onlyDeleted()->where('denominacion', $denominacion)->where('id !=', $id)->first();

        if (!empty($datoObjeto)){
            echo json_encode(['exito' => false,'papelera'=> true, 'mensaje'=>'Dato existente en la papelera, recupérelo']);
            exit;
        }

        $datosActualizar = [
            'codigo' => $codigo,    
            'denominacion' => $denominacion,
            'descripcion' => $descripcion     
        ];
        if ($idUbicacion !== null) {
            $datosActualizar['id_ubicacion'] = $idUbicacion === '' ? null : (int) $idUbicacion;
        }
        $this->objetos->update($id, $datosActualizar);
       

        $id_logueado = $this->sesion->get('id');
        $this->actividades->save([
            'id_usuario' => $id_logueado,
            'tabla'      => 'objetos',
            'id_dato'    => $id,
            'accion'     => 'm'
        ]);

        // 2. Damos de baja las asociaciones anteriores para volver a armarlas
        //    con lo que llegó del formulario (misma lógica que insertar())
        $this->objetosetiquetas->where('objeto_id', $id)->delete();
        $this->objetosatributos->where('objeto_id', $id)->delete();
       

        $this->actividades->save([
            'id_usuario' => $id_logueado,
            'tabla'      => 'objeto_etiquetas',
            'id_dato'    => $id,
            'accion'     => 'm'
        ]);
       
        $this->actividades->save([
            'id_usuario' => $id_logueado,
            'tabla'      => 'objeto_atributos',
            'id_dato'    => $id,
            'accion'     => 'm'
        ]);

        // --- ETIQUETAS DINÁMICAS (Múltiples seleccionadas) ---
        $etiqueta_ids = $this->request->getPost('etiqueta_ids');
        if (!empty($etiqueta_ids) && is_array($etiqueta_ids)) {
            foreach ($etiqueta_ids as $id_etiqueta) {
                $this->vincularEtiqueta($id, $id_etiqueta);
            }
        }

        // --- ATRIBUTOS DINÁMICOS ---
        $atributo_ids     = $this->request->getPost('atributo_ids');
        $atributo_valores = $this->request->getPost('atributo_valores');

        if (!empty($atributo_ids) && is_array($atributo_ids)) {
            foreach ($atributo_ids as $id_atributo) {
                if (isset($atributo_valores[$id_atributo])) {
                    $valor = $atributo_valores[$id_atributo];
                    $this->vincularAtributo($id, $id_atributo, $valor);
                }
            }
        }

        // --- IMAGENES: quitamos las tildadas, guardamos las nuevas y definimos la portada ---
        $imagenes_quitar = $this->request->getPost('imagenes_quitar');
        if (!empty($imagenes_quitar) && is_array($imagenes_quitar)) {
            foreach ($imagenes_quitar as $id_imagen) {
                $this->piezasimagenes->where('objeto_id', $id)->delete($id_imagen);
            }
        }
        $resultado = $this->guardarImagenesPieza($id);
        $this->definirPortadaPieza($id, $this->request->getPost('portada'), $resultado['ids']);

        // --- ARCHIVOS: quitamos los marcados y guardamos los nuevos ---
        $archivos_quitar = $this->request->getPost('archivos_quitar');
        if (!empty($archivos_quitar) && is_array($archivos_quitar)) {
            foreach ($archivos_quitar as $id_archivo) {
                $id_archivo = (int) $id_archivo;
                if ($id_archivo > 0) {
                    $this->piezasarchivos->where('objeto_id', $id)->delete($id_archivo);
                }
            }
        }
        $resultadoArchivos = $this->guardarArchivosPieza($id);

        $mensaje = 'Actualizado exitosamente';
        $fallidas = $resultado['fallidas'] + $resultadoArchivos['fallidas'];
        if ($fallidas > 0) {
            $mensaje .= ' (pero ' . $fallidas . ' archivo(s) o imagen(es) no se pudieron guardar)';
        }
        echo json_encode(['exito' => true, 'mensaje' => $mensaje]);
    }

    public function papelera(){
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('objeto')) {
            return $this->response->setStatusCode(403)->setBody('No tenés permiso para ver piezas');
        }
        $objetos = $this->objetos->onlyDeleted()->findAll();
        $museos = $this->museos->first();

        $datos = [ 
            'museos'  => $museos,
            'objetos' => $objetos, 
            'titulo'  => 'Objetos borrados'
        ];
        
        echo view('header', $datos);
        echo view('objetos/papelera');
        echo view('footer');
    }

    public function recuperacion($id) {
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        //esto nos indica que esperamos peticiones json
        header('Content-Type: application/json');
        $permisos = $this->sesion->get('permisos');
        
        if (!in_array('b', $permisos['objeto'])){
            echo json_encode(['exito' => false, 'mensaje' => 'Usted no tiene la autorización']);
            exit;    
        }
        $objeto = $this->objetos->withDeleted()->find($id);
        if ($objeto){
            $this->objetos->update($id, ['fecha_baja' => null]);
            // <-- NUEVA
            $this->actividades->save([
                'id_usuario' => $this->sesion->get('id'),
                'tabla'      => 'objetos',
                'id_dato'    => $id,
                'accion'     => 'r'
            ]);
            echo json_encode(['exito' => true, 'mensaje' => 'recuperado exitosamente']);
            exit;
        }
    }

    public function ver($id) {
        
        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        if (!$this->puedeVer('objeto')) {
            return $this->response->setStatusCode(403)->setBody('No tenés permiso para ver piezas');
        }
        // 1. Traemos los datos principales del objeto
        //$objeto = $this->objetos->where('id', $id)->first();
        $museos = $this->museos->first();

        $objeto =   $this->objetos
                                ->select('objetos.*, usuarios.denominacion AS cargado_por')
                                ->join('usuarios', 'usuarios.id = objetos.id_usuario')
                                ->where('objetos.id', $id)
                                ->first();

        $objetosatributos = $this->obtenerAtributosPorObjeto($id);
        $objetosetiquetas = $this->obtenerEtiquetasPorObjeto($id);
        $galeria = $this->piezasimagenes->where('objeto_id', $id)->orderBy('portada', 'DESC')->orderBy('id', 'ASC')->findAll();
        $archivos = $this->piezasarchivos->where('objeto_id', $id)->orderBy('id', 'ASC')->findAll();
        $ubicacion = !empty($objeto['id_ubicacion']) ? $this->salas->withDeleted()->find($objeto['id_ubicacion']) : null;

        $datos = [ 
            'museos'    => $museos,
            //'usaurio' => $objeto_por_usuario,
            'objeto'    => $objeto,
            'etiquetas' => $objetosetiquetas,
            'atributos' => $objetosatributos,
            'galeria'   => $galeria,
            'archivos'  => $archivos,
            'ubicacion' => $ubicacion,
            'titulo'    => 'Detalle del Objeto'
        ];
        
        echo view('header', $datos);
        echo view('objetos/ver'); // Llamamos a la nueva vista
        echo view('footer');
        
    }

    private function validarUbicacion($idUbicacion, $ubicacionActual, $permisos): ?string {
        if ($idUbicacion === null) {
            return null;
        }
        if (!is_string($idUbicacion) || ($idUbicacion !== '' && (!ctype_digit($idUbicacion) || (int) $idUbicacion < 1))) {
            return 'La ubicación seleccionada no es válida';
        }

        $nuevaUbicacion = $idUbicacion === '' ? null : (int) $idUbicacion;
        $ubicacionActual = $ubicacionActual === null ? null : (int) $ubicacionActual;
        if ($nuevaUbicacion === $ubicacionActual) {
            return null;
        }
        $permisoNecesario = $nuevaUbicacion === null ? 'b' : 'a';
        if (!in_array($permisoNecesario, $permisos['sala'] ?? [], true)) {
            return 'Usted no tiene autorización para cambiar la ubicación';
        }
        if ($nuevaUbicacion !== null) {
            $sala = $this->salas->find($nuevaUbicacion);
            if (!$sala || $sala['id_pertenece'] === null) {
                return 'La ubicación seleccionada no existe o no está disponible';
            }
        }
        return null;
    }

    // Guarda las imagenes que llegan en imagenes[] y devuelve cuantas fallaron y el id de cada una guardada
    private function guardarImagenesPieza($objeto_id) {
        $archivos = $this->request->getFileMultiple('imagenes') ?? [];

        $tiposPermitidos = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
        ];

        $directorio = FCPATH . 'objetos_multimedia/images';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $fallidas = 0;
        $ids = [];
        foreach ($archivos as $indice => $archivo) {
            if ($archivo->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if (!$archivo->isValid() || $archivo->getSize() > 5 * 1024 * 1024) {
                $fallidas++;
                continue;
            }

            $tipo = $archivo->getMimeType();
            if (!isset($tiposPermitidos[$tipo]) || @getimagesize($archivo->getTempName()) === false) {
                $fallidas++;
                continue;
            }

            // id de la pieza + fecha y hora + nombre original, por ejemplo 145_20261114131111_image.jpg
            $nombreOriginal = pathinfo($archivo->getClientName(), PATHINFO_FILENAME);
            $nombreOriginal = substr(preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreOriginal), 0, 100);
            $nombreArchivo = $objeto_id . '_' . date('YmdHis') . '_' . $nombreOriginal . '.' . $tiposPermitidos[$tipo];

            $archivo->move($directorio, $nombreArchivo);

            // getName() da el nombre final: si ya existia uno igual, CodeIgniter le agrega _1
            $ids[$indice] = $this->piezasimagenes->insert([
                'objeto_id'     => $objeto_id,
                'nombrearchivo' => $archivo->getName()
            ], true);
        }

        return ['fallidas' => $fallidas, 'ids' => $ids];
    }

    // Guarda los archivos (PDF, DOC, DOCX) que llegan en archivos[] y devuelve cuantos fallaron y el id de cada uno guardado
    private function guardarArchivosPieza($objeto_id) {
        $archivos = $this->request->getFileMultiple('archivos') ?? [];

        // extension permitida => con que bytes puede empezar el contenido real del archivo
        $firmas = [
            'pdf'  => ['%PDF'],
            'docx' => ["PK\x03\x04"],
            // un .doc puede ser un Word clasico (OLE), un docx renombrado (zip) o un RTF
            'doc'  => ["\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1", "PK\x03\x04", '{\rtf'],
        ];

        $directorio = FCPATH . 'objetos_multimedia/file';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $fallidas = 0;
        $ids = [];
        foreach ($archivos as $indice => $archivo) {
            if ($archivo->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $nombreCliente = $archivo->getClientName();

            if (!$archivo->isValid()) {
                log_message('error', 'Archivo rechazado (' . $nombreCliente . '): error de subida ' . $archivo->getError() . ' - ' . $archivo->getErrorString());
                $fallidas++;
                continue;
            }

            if ($archivo->getSize() > 10 * 1024 * 1024) {
                log_message('error', 'Archivo rechazado (' . $nombreCliente . '): supera 10 MB (' . $archivo->getSize() . ' bytes)');
                $fallidas++;
                continue;
            }

            $extension = strtolower($archivo->getClientExtension());
            if (!isset($firmas[$extension])) {
                log_message('error', 'Archivo rechazado (' . $nombreCliente . '): extension no permitida (' . $extension . ')');
                $fallidas++;
                continue;
            }

            // el contenido real tiene que coincidir con la extension
            $cabecera = (string) file_get_contents($archivo->getTempName(), false, null, 0, 8);
            $valido = false;
            foreach ($firmas[$extension] as $firma) {
                if (strncmp($cabecera, $firma, strlen($firma)) === 0) {
                    $valido = true;
                    break;
                }
            }
            if (!$valido) {
                log_message('error', 'Archivo rechazado (' . $nombreCliente . '): el contenido no coincide con .' . $extension . ', empieza con ' . bin2hex($cabecera));
                $fallidas++;
                continue;
            }

            // id de la pieza + fecha y hora + nombre original, por ejemplo 145_20261114131111_informe.pdf
            $nombreOriginal = pathinfo($nombreCliente, PATHINFO_FILENAME);
            $nombreOriginal = substr(preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreOriginal), 0, 100);
            $nombreArchivo = $objeto_id . '_' . date('YmdHis') . '_' . $nombreOriginal . '.' . $extension;

            $archivo->move($directorio, $nombreArchivo);

            $ids[$indice] = $this->piezasarchivos->insert([
                'objeto_id'      => $objeto_id,
                'nombrearchivo'  => $archivo->getName(),
                'nombreoriginal' => mb_substr($nombreCliente, 0, 255)
            ], true);
        }

        return ['fallidas' => $fallidas, 'ids' => $ids];
    }

    // Marca una sola imagen de la pieza como portada
    private function definirPortadaPieza($objeto_id, $elegida, $ids_nuevas) {
        $imagenes = $this->piezasimagenes->where('objeto_id', $objeto_id)->orderBy('id', 'ASC')->findAll();
        if (empty($imagenes)) {
            return;
        }

        // la elegida llega como actual-15 (ya estaba cargada) o nueva-0 (recien subida)
        $portada_id = 0;
        $partes = explode('-', (string) $elegida);
        if (count($partes) == 2 && $partes[0] == 'actual') {
            $portada_id = (int) $partes[1];
        }
        if (count($partes) == 2 && $partes[0] == 'nueva' && isset($ids_nuevas[$partes[1]])) {
            $portada_id = $ids_nuevas[$partes[1]];
        }

        // si no es una imagen de esta pieza, queda la primera
        if (!in_array($portada_id, array_column($imagenes, 'id'))) {
            $portada_id = $imagenes[0]['id'];
        }

        foreach ($imagenes as $imagen) {
            $this->piezasimagenes->update($imagen['id'], ['portada' => $imagen['id'] == $portada_id ? 1 : 0]);
        }
    }

    // API: devuelve en JSON los atributos activos, del más nuevo al más viejo
    public function api_objetos(){

        if (!$this->puedeVer('objeto')) {
            return $this->accesoDenegado();
        }
        $objetos = $this->objetos->select('objetos.*, usuarios.denominacion AS cargado_por')
                    ->join('usuarios', 'usuarios.id = objetos.id_usuario', 'left')
                    ->orderBy('objetos.id','DESC')
                    ->findAll();

        $datos = ['objetos'=>$this->ocultarFechaAlta($objetos, 'objeto')];
        return $this->response->setJSON($datos);
    }

    public function filtrarPorEtiquetas(){
        if (!isset($this->sesion->id)) {
            return $this->response->setStatusCode(401)->setJSON([
                'exito' => false,
                'mensaje' => 'La sesión expiró. Vuelve a iniciar sesión.'
            ]);
        }
        if (!$this->puedeVer('objeto')) {
            return $this->accesoDenegado();
        }

        $etiquetaIds = $this->request->getPost('etiqueta_ids') ?? [];
        if (!is_array($etiquetaIds)) {
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'El filtro de etiquetas no es válido.'
            ]);
        }

        $descripcion = $this->request->getPost('descripcion_filtro') ?? '';
        $tipoFecha = $this->request->getPost('tipo_fecha') ?? 'alta';
        $fechaDesde = $this->request->getPost('fecha_desde') ?? '';
        $fechaHasta = $this->request->getPost('fecha_hasta') ?? '';
        $creadoPor = $this->request->getPost('creado_por') ?? '';

        if (
            !is_string($descripcion)
            || !is_string($tipoFecha)
            || !is_string($fechaDesde)
            || !is_string($fechaHasta)
            || !is_string($creadoPor)
            || !in_array($tipoFecha, ['alta', 'edicion'], true)
        ) {
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Uno de los filtros enviados no es válido.'
            ]);
        }
        $permisos = $this->sesion->get('permisos');
        if ($tipoFecha === 'alta' && ($fechaDesde !== '' || $fechaHasta !== '') && !in_array('f', $permisos['objeto'] ?? [], true)) {
            return $this->response->setStatusCode(403)->setJSON(['exito' => false, 'mensaje' => 'No tenés permiso para filtrar por fecha de alta']);
        }

        $descripcion = trim($descripcion);
        $validarFecha = static function (string $fecha): bool {
            if ($fecha === '') {
                return true;
            }

            $fechaFormateada = \DateTime::createFromFormat('!Y-m-d', $fecha);
            return $fechaFormateada !== false && $fechaFormateada->format('Y-m-d') === $fecha;
        };

        if (!$validarFecha($fechaDesde) || !$validarFecha($fechaHasta)) {
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'Ingresa fechas válidas para el filtro.'
            ]);
        }

        if ($fechaDesde !== '' && $fechaHasta !== '' && $fechaDesde > $fechaHasta) {
            return $this->response->setStatusCode(400)->setJSON([
                'exito' => false,
                'mensaje' => 'La fecha desde no puede ser posterior a la fecha hasta.'
            ]);
        }

        $creadorId = null;
        if ($creadoPor !== '') {
            $creadorId = filter_var($creadoPor, FILTER_VALIDATE_INT);
            if ($creadorId === false || $creadorId < 1) {
                return $this->response->setStatusCode(400)->setJSON([
                    'exito' => false,
                    'mensaje' => 'Selecciona un usuario válido.'
                ]);
            }
        }

        $etiquetaIdsValidados = [];
        foreach ($etiquetaIds as $etiquetaId) {
            $idValidado = filter_var($etiquetaId, FILTER_VALIDATE_INT);
            if ($idValidado === false || $idValidado < 1) {
                return $this->response->setStatusCode(400)->setJSON([
                    'exito' => false,
                    'mensaje' => 'El filtro contiene una etiqueta no válida.'
                ]);
            }
            $etiquetaIdsValidados[] = $idValidado;
        }

        $consulta = $this->objetos
            ->select('objetos.*, usuarios.denominacion AS cargado_por')
            ->join('usuarios', 'usuarios.id = objetos.id_usuario', 'left')
            ->orderBy('objetos.id', 'DESC');

        if ($descripcion !== '') {
            $consulta->like('objetos.descripcion', $descripcion);
        }

        if ($creadorId !== null) {
            $consulta->where('objetos.id_usuario', $creadorId);
        }

        $campoFecha = $tipoFecha === 'alta' ? 'fecha_alta' : 'fecha_edicion';
        if ($fechaDesde !== '') {
            $consulta->where('objetos.' . $campoFecha . ' >=', $fechaDesde . ' 00:00:00');
        }
        if ($fechaHasta !== '') {
            $consulta->where('objetos.' . $campoFecha . ' <=', $fechaHasta . ' 23:59:59');
        }

        if ($etiquetaIdsValidados !== []) {
            $consulta
                ->join('objeto_etiquetas', 'objeto_etiquetas.objeto_id = objetos.id')
                ->join('etiquetas', 'etiquetas.id = objeto_etiquetas.etiqueta_id')
                ->whereIn('etiquetas.id', array_values(array_unique($etiquetaIdsValidados)))
                ->where('objeto_etiquetas.fecha_baja', null)
                ->where('etiquetas.fecha_baja', null)
                ->distinct();
        }

        return $this->response->setJSON([
            'exito' => true,
            'objetos' => $this->ocultarFechaAlta($consulta->findAll(), 'objeto')
        ]);
    }

    // API: devuelve en JSON los atributos borrados (papelera)
    public function api_objetos_borrados(){
        if (!$this->puedeVer('objeto')) {
            return $this->accesoDenegado();
        }
        $objetos = $this->objetos->select('objetos.*, usuarios.denominacion AS cargado_por')
                    ->join('usuarios', 'usuarios.id = objetos.id_usuario')
                    ->orderBy('objetos.id','DESC')
                    ->onlyDeleted()
                    ->findAll();
        $datos = ['objetos'=>$this->ocultarFechaAlta($objetos, 'objeto')];
        return $this->response->setJSON($datos);
    }


    public function obtenerAtributosPorObjeto($objeto_id){
        return $this->objetosatributos->select('atributos.id, atributos.denominacion, atributos.tipo_dato,atributos.unidad, objeto_atributos.valor')
                    ->join('atributos', 'atributos.id = objeto_atributos.atributo_id')
                    ->where('objeto_atributos.objeto_id', $objeto_id)
                    ->where('atributos.fecha_baja', null)
                    ->findAll();
    }
    
     // Vincula un objeto con un atributo y su valor
    public function vincularAtributo($objeto_id, $atributo_id, $valor){
        return $this->objetosatributos->insert([
            'objeto_id'   => $objeto_id,
            'atributo_id' => $atributo_id,
            'valor'       => $valor,
        ]);
    }
    public function vincularEtiqueta($objeto_id, $etiqueta_id){
        return $this->objetosetiquetas->insert([
            'objeto_id'   => $objeto_id,
            'etiqueta_id' => $etiqueta_id,
        ]);
    }
    // Trae las etiquetas vinculadas a un objeto
    
    public function obtenerEtiquetasPorObjeto($objeto_id){
        return $this->objetosetiquetas->select('etiquetas.id, etiquetas.denominacion')
                    ->join('etiquetas', 'etiquetas.id = objeto_etiquetas.etiqueta_id')
                    ->where('objeto_etiquetas.objeto_id', $objeto_id)
                    ->where('etiquetas.fecha_baja', null)
                    ->findAll();
    }
    
}