<?php

namespace App\Controllers;

use App\Models\ClientesModel;

class Panel extends BaseController
{      
    public function index()
    {
        echo view('header');
        echo view('content');
        echo  view('footer');
    }

}
