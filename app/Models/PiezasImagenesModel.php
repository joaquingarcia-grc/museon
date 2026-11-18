<?php

  namespace App\Models;

  use CodeIgniter\Model;

  class PiezasImagenesModel extends Model
  {
    protected $table      = 'piezas_imagenes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['objeto_id','nombrearchivo','portada','fecha_baja','fecha_alta'];

    protected $dateFormat = 'datetime';

    protected $useTimestamps = true;
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_edicion';
    protected $deletedField  = 'fecha_baja';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
  }