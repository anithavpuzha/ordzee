<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDailyMenuItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'daily_menu_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'piece',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'is_available' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 10,
                'default'    => 0,
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
        $this->forge->addKey('daily_menu_id');
        $this->forge->addKey('product_id');

        $this->forge->addForeignKey(
            'daily_menu_id',
            'daily_menus',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->forge->createTable('daily_menu_items');
    }

    public function down()
    {
        $this->forge->dropTable('daily_menu_items');
    }
}