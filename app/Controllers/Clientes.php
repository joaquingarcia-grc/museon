<?php

namespace App\Controllers;

use App\Models\ClientesModel;
use App\Models\AdministracionModel;

class Clientes extends BaseController
{   
    protected $clientes;

    protected $veterinaria;


    public function __construct() {

        $this->veterinaria = new AdministracionModel();

        $this->clientes = new ClientesModel();
    }
    public function index()
    {
        $clientes = $this->clientes->findAll();
        $veterinaria = $this->veterinaria->first();
        
        $datos = ['veterinaria'=>$veterinaria, 
                  'clientes'=>$clientes,
                  'titulo' => 'Clientes'];

        echo view('header',$datos);
        echo view('clientes/clientes');
        echo  view('footer');
    }

}

?>