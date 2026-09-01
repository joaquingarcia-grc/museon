<?php

  namespace App\Models;
  //manera de la  cual vamos a llamar estos modelos 
  use CodeIgniter\Model;
  //esto es para indicar que vamos a usar los modelos de codeigniter
  class ObjetosEtiquetasModel extends Model
  {
    //vamos a llamar a la tabla usando la bariable $table
    protected $table      = 'objeto_etiquetas';
    //id seria el atributo primero de nuestra tabla
    protected $primaryKey = 'id';
    //indicamos que nuestra clabe primaria es auto incrementable
    protected $useAutoIncrement = true;
    //como devuelve los modelos
    protected $returnType     = 'array';
    //esto depende si queremos hacer un hard delet(false) o un soft delet(true)
    protected $useSoftDeletes = true;
    //aca defino las columnas que quiero que sean visibles
    protected $allowedFields = ['objeto_id','etiqueta_id','fecha_baja','fecha_alta'];

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

    public function vincularEtiqueta($objeto_id, $etiqueta_id)
    {
        return $this->insert([
            'objeto_id'   => $objeto_id,
            'etiqueta_id' => $etiqueta_id,
        ]);
    }
 
    // Trae las etiquetas vinculadas a un objeto
    
    public function obtenerEtiquetasPorObjeto($objeto_id)
    {
        return $this->select('etiquetas.id, etiquetas.denominacion')
                    ->join('etiquetas', 'etiquetas.id = objeto_etiquetas.etiqueta_id')
                    ->where('objeto_etiquetas.objeto_id', $objeto_id)
                    ->findAll();
    }
    
  }
 ?>