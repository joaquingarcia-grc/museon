<?php

namespace App\Controllers;

use App\Models\ActividadesModel;
use App\Models\MuseosModel;
use App\Models\UsuariosModel;

class Actividad extends BaseController {

    protected $sesion;
    protected $actividades;
    protected $museos;
    protected $usuarios;

    public function __construct() {
        $this->sesion = session();
        $this->actividades = new ActividadesModel();
        $this->museos = new MuseosModel();
        $this->usuarios = new UsuariosModel();
    }

    public function index(){

        if(!isset($this->sesion->id)){
            return redirect()->to(base_url() . "registro/");
        }
        // Solo administradores (rol 1)
        if ($this->sesion->get('rol') != 1){
            return redirect()->to(base_url() . 'objetos');
        }

        $datos = [
            'museos' => $this->museos->first(),
            'usuarios' => $this->usuarios->select('denominacion')->withDeleted()->orderBy('denominacion', 'ASC')->findAll(),
            'titulo' => 'Actividad del sistema'
        ];

        echo view('header', $datos);
        echo view('actividad/listado');
        echo view('footer');
    }

    public function api_actividad(){

        if(!isset($this->sesion->id)){
            return $this->response->setStatusCode(401);
        }
        // Solo administradores (rol 1)
        if ($this->sesion->get('rol') != 1){
            return $this->response->setStatusCode(403);
        }

        $idPieza = $this->request->getGet('id_pieza');
        if ($idPieza !== null && (!is_string($idPieza) || !ctype_digit($idPieza) || (int) $idPieza < 1)) {
            return $this->response->setStatusCode(400)->setJSON(['mensaje' => 'Ingresá un ID de pieza válido']);
        }

        $consulta = $this->actividades
            ->select('auditoria.*, usuarios.denominacion AS usuario')
            ->join('usuarios', 'usuarios.id = auditoria.id_usuario')
            ->orderBy('auditoria.id', 'DESC');

        if ($idPieza !== null) {
            $consulta->where('auditoria.id_dato', $idPieza)
                ->whereIn('auditoria.tabla', ['objetos', 'objeto_etiquetas', 'objeto_atributos']);
            $actividad = $consulta->findAll();
        } else {
            $actividad = $consulta->findAll(300);
        }

        foreach ($actividad as $indice => $f) {
            $actividad[$indice]['fecha'] = date('d/m/Y H:i', strtotime($f['fecha']));
            $actividad[$indice]['usuario'] = esc($f['usuario']);
        }

        return $this->response->setJSON(['actividad' => $actividad]);
    }
}