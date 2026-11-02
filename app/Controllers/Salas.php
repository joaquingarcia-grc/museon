<?php

namespace App\Controllers;

use App\Models\MuseosModel;
use App\Models\UsuariosModel;
use App\Models\PermisosModel;
use App\Models\SalasModel;

class Salas extends BaseController{ 
    protected $museos;
    protected $usuarios;
    protected $permisos;
    protected $salas;

    public function __construct(){
        $this->museos = new MuseosModel();
        $this->usuarios = new UsuariosModel();
        $this->permisos = new PermisosModel();
        $this->salas = new SalasModel();
    }
    public function index(){

        $salas = $this->salas->findAll();
        $museos = $this->museos->first();
        $datos = [ 'museos'=>$museos,
                  'titulo' => 'Salas',
                  'salas' => $salas];

        echo view('header',$datos);
        echo view('salas/salas');
        echo  view('footer');
    }
}

?>