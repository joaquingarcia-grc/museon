<?php

namespace App\Controllers;

use App\Models\ActividadesModel;
use App\Models\MuseosModel;

class Actividad extends BaseController {

    protected $sesion;
    protected $actividades;
    protected $museos;

    public function __construct() {
        $this->sesion = session();
        $this->actividades = new ActividadesModel();
        $this->museos = new MuseosModel();
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

        $actividad = $this->actividades
            ->select('auditoria.*, usuarios.denominacion AS usuario')
            ->join('usuarios', 'usuarios.id = auditoria.id_usuario')
            ->orderBy('auditoria.id', 'DESC')
            ->findAll(300);

        foreach ($actividad as $indice => $f) {
            $actividad[$indice]['fecha'] = date('d/m/Y H:i', strtotime($f['fecha']));
            $actividad[$indice]['usuario'] = esc($f['usuario']);
        }

        return $this->response->setJSON(['actividad' => $actividad]);
    }
}