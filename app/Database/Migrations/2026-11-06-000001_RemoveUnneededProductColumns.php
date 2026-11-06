<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveUnneededProductColumns extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('products', [
            'description',
            'unit',
            'default_price',
        ]);
    }

    public function down()
    {
        $this->forge->addColumn('products', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'piece',
            ],
            'default_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
        ]);
    }
}