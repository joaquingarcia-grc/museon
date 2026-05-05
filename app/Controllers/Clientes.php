<?php

namespace App\Controllers;

use App\Models\ClientesModel;

class Clientes extends BaseController
{      
    public function index()
    {
        echo view('header');
        echo view('clientes/clientes');
        echo  view('footer');
    }

}

?>