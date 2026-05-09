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
        $datos = $this->veterinaria->first();

        echo view('header');
        echo view('content', $datos);
        echo  view('footer');
    }

}
?>