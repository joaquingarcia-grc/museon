<?php

  namespace App\Models;
  //manera de la  cual vamos a llamar estos modelos 
  use CodeIgniter\Model;
  //esto es para indicar que vamos a usar los modelos de codeigniter
  class ClientesModel extends Model
  {
    //vamos a llamar a la tabla usando la bariable $table
    protected $table      = 'administracion';
    //id seria el atributo primero de nuestra tabla
    protected $primaryKey = 'id_cliente';
    //indicamos que nuestra clabe primaria es auto incrementable
    protected $useAutoIncrement = true;
    //como devuelve los modelos
    protected $returnType     = 'array';
    //esto depende si queremos hacer un hard delet(false) o un soft delet(true)
    protected $useSoftDeletes = false;
    //aca defino las columnas que quiero que sean visibles
    protected $allowedFields = ['nombre', 'apellido','telefono', 'email','domicilio', 'cuenta'];

    protected $dateFormat = 'datetime';

    protected $useTimestamps = false;
    //fecha de registro
    protected $createdField  = 'fecha_alta';
    //fecha de edicion
    protected $updatedField  = 'fecha_edicion';
    //Esto ayuda al borrado de datoss
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
  }
 ?>