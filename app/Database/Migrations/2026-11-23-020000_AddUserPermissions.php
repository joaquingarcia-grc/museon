<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserPermissions extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS usuario_permiso (
            id_usuario INT NOT NULL,
            objeto SET('a','b','m','l','f') NOT NULL DEFAULT '',
            etiqueta SET('a','b','m','l','f') NOT NULL DEFAULT '',
            atributo SET('a','b','m','l','f') NOT NULL DEFAULT '',
            usuario SET('a','b','m','l','f') NOT NULL DEFAULT '',
            sala SET('a','b','m','l','f') NOT NULL DEFAULT '',
            actividad SET('l') NOT NULL DEFAULT '',
            PRIMARY KEY (id_usuario),
            CONSTRAINT fk_usuario_permiso_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS usuario_permiso');
    }
}
