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

        $actividad = $this->actividades
            ->select('auditoria.*, usuarios.denominacion AS usuario')
            ->join('usuarios', 'usuarios.id = auditoria.id_usuario')
            ->orderBy('auditoria.id', 'DESC')
            ->findAll(300);

        $datos = [
            'museos'    => $this->museos->first(),
            'actividad' => $actividad,
            'titulo'    => 'Actividad del sistema'
        ];

        echo view('header', $datos);
        echo view('actividad/listado');
        echo view('footer');
    }
}