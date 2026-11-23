<?php

  namespace App\Models;
  //manera de la  cual vamos a llamar estos modelos 
  use CodeIgniter\Model;
  //esto es para indicar que vamos a usar los modelos de codeigniter
  class PermisosModel extends Model
  {
    //vamos a llamar a la tabla usando la bariable $table
    protected $table      = 'rol_permiso';
    //id seria el atributo primero de nuestra tabla
    protected $primaryKey = 'id';
    //indicamos que nuestra clabe primaria es auto incrementable
    protected $useAutoIncrement = true;
    //como devuelve los modelos
    protected $returnType     = 'array';
    //esto depende si queremos hacer un hard delet(false) o un soft delet(true)
    protected $useSoftDeletes = false;
    //aca defino las columnas que quiero que sean visibles
    protected $allowedFields = ['id_rol', 'objeto','etiqueta', 'atributo', 'usuario','sala', 'actividad'];

    protected $useTimestamps = false;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
  }
 ?>