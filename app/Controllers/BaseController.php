<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    protected function puedeVer(string $modulo): bool
    {
        $sesion = session();
        $permisos = $sesion->get('permisos') ?? [];
        return isset($sesion->id) && in_array('l', $permisos[$modulo] ?? [], true);
    }

    protected function accesoDenegado()
    {
        return $this->response->setStatusCode(403)->setJSON(['exito' => false, 'mensaje' => 'No tenés permiso para ver esta sección']);
    }

    protected function ocultarFechaAlta(array $registros, string $modulo): array
    {
        $permisos = session()->get('permisos') ?? [];
        if (!in_array('f', $permisos[$modulo] ?? [], true)) {
            foreach ($registros as &$registro) {
                unset($registro['fecha_alta']);
            }
            unset($registro);
        }
        return $registros;
    }

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $sesion = session();
        if (isset($sesion->id)) {
            $usuario = (new \App\Models\UsuariosModel())
                ->select('usuarios.denominacion, usuarios.id_rol, rol_permiso.objeto, rol_permiso.etiqueta, rol_permiso.atributo, rol_permiso.usuario, rol_permiso.sala, rol_permiso.actividad')
                ->join('rol_permiso', 'rol_permiso.id_rol = usuarios.id_rol')
                ->find($sesion->id);
            if (!$usuario) {
                $sesion->remove(['id', 'denominacion', 'rol', 'permisos']);
                $sesion->destroy();
            } else {
                $permisos = [];
                foreach (['objeto', 'etiqueta', 'atributo', 'usuario', 'sala', 'actividad'] as $modulo) {
                    $permisos[$modulo] = empty($usuario[$modulo]) ? [] : explode(',', $usuario[$modulo]);
                }
                $sesion->set(['denominacion' => $usuario['denominacion'], 'rol' => $usuario['id_rol'], 'permisos' => $permisos]);
            }
        }

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }
}
