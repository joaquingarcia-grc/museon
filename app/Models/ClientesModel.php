<?php

  namespace App\Models;
  //manera de la  cual vamos a llamar estos modelos 
  use CodeIgniter\Model;
  //esto es para indicar que vamos a usar los modelos de codeigniter
  class ClientesModel extends Model
  {
    //vamos a llamar a la tabla usando la bariable $table
    protected $table      = 'clientes';
    //id seria el atributo primero de nuestra tabla
    protected $primaryKey = 'id';
    //indicamos que nuestra clabe primaria es auto incrementable
    protected $useAutoIncrement = true;
    //como devuelve los modelos
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    //aca defino las columnas que quiero que sean visibles
    protected $allowedFields = ['denominacion', 'email', 'domicilio'];

    protected $useTimestamps = false;
    //fecha de registro
    protected $createdField  = '';
    //fecha de edicion
    protected $updatedField  = '';
    //Esto ayuda al borrado de datoss
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
  }
 ?>

