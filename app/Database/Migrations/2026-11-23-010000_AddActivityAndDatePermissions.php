<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActivityAndDatePermissions extends Migration
{
    public function up()
    {
        foreach (['objeto', 'etiqueta', 'atributo', 'usuario', 'sala'] as $modulo) {
            $columna = $this->db->query('SHOW COLUMNS FROM rol_permiso LIKE ?', [$modulo])->getRowArray();
            if (strpos($columna['Type'], "'f'") === false) {
                $this->db->query("ALTER TABLE rol_permiso MODIFY `$modulo` SET('a','b','m','l','f') NOT NULL");
                $this->db->query("UPDATE rol_permiso SET `$modulo` = CONCAT_WS(',', NULLIF(`$modulo`, ''), 'f') WHERE FIND_IN_SET('l', `$modulo`) > 0");
            }
        }

        if (!$this->db->fieldExists('actividad', 'rol_permiso')) {
            $this->db->query("ALTER TABLE rol_permiso ADD actividad SET('l') NOT NULL DEFAULT ''");
            $this->db->query("UPDATE rol_permiso SET actividad = 'l' WHERE id_rol = 1");
        }
    }

    public function down()
    {
        $this->db->query('ALTER TABLE rol_permiso DROP COLUMN actividad');
        foreach (['objeto', 'etiqueta', 'atributo', 'usuario', 'sala'] as $modulo) {
            $this->db->query("UPDATE rol_permiso SET `$modulo` = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', `$modulo`, ','), ',f,', ','))");
            $this->db->query("ALTER TABLE rol_permiso MODIFY `$modulo` SET('a','b','m','l') NOT NULL");
        }
    }
}
