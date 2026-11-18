<?php
namespace App\Models;
use CodeIgniter\Model;

class ActividadesModel extends Model
{
    protected $table         = 'auditoria';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['tabla','accion','id_dato','id_usuario'];
}