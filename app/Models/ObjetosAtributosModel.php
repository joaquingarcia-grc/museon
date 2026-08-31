<?php

  namespace App\Models;
  //manera de la  cual vamos a llamar estos modelos 
  use CodeIgniter\Model;
  //esto es para indicar que vamos a usar los modelos de codeigniter
  class ObjetosAtributosModel extends Model
  {
    //vamos a llamar a la tabla usando la bariable $table
    protected $table      = 'objeto_atributos';
    //id seria el atributo primero de nuestra tabla
    protected $primaryKey = 'id';
    //indicamos que nuestra clabe primaria es auto incrementable
    protected $useAutoIncrement = true;
    //como devuelve los modelos
    protected $returnType     = 'array';
    //esto depende si queremos hacer un hard delet(false) o un soft delet(true)
    protected $useSoftDeletes = true;
    //aca defino las columnas que quiero que sean visibles
    protected $allowedFields = ['objeto_id','atributo_id','valor','fecha_baja','fecha_alta'];

    protected $dateFormat = 'datetime';

    protected $useTimestamps = true;
    //fecha de registro
    protected $createdField  = 'fecha_alta';
    //fecha de edicion
    protected $updatedField  = 'fecha_edicion';
    //Esto ayuda al borrado de datoss
    protected $deletedField  = 'fecha_baja';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function obtenerAtributosPorObjeto($objeto_id)
    {
        return $this->select('atributos.denominacion, objeto_atributos.valor')
                    ->join('atributos', 'atributos.id = objeto_atributos.atributo_id')
                    ->where('objeto_atributos.objeto_id', $objeto_id)
                    ->findAll();
    }
    
     // Vincula un objeto con un atributo y su valor
    public function vincularAtributo($objeto_id, $atributo_id, $valor)
    {
        return $this->insert([
            'objeto_id'   => $objeto_id,
            'atributo_id' => $atributo_id,
            'valor'       => $valor,
        ]);
    }
  }
 ?>