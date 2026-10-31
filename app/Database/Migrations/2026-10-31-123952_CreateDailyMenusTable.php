<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDailyMenusTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'business_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'menu_date' => [
                'type' => 'DATE',
            ],
            'menu_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'default'    => 'Daily Menu',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'draft',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['business_id', 'menu_date']);
        $this->forge->addKey('business_id');

        $this->forge->addForeignKey(
            'business_id',
            'businesses',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('daily_menus');
    }

    public function down()
    {
        $this->forge->dropTable('daily_menus');
    }
}