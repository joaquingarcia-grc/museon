<?php

namespace App\Controllers;

use App\Models\AdministracionModel;

class Panel extends BaseController
{      
    protected $veterinaria;

    public function __construct() {

        $this->veterinaria = new AdministracionModel();

    }
    public function index()
    {
        $veterinaria = $this->veterinaria->first();
        $datos = ['veterinaria'=>$veterinaria];

        echo view('header', $datos);
        echo view('content');
        echo  view('footer');
    }

}
?>