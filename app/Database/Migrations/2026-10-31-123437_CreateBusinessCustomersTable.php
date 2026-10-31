<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBusinessCustomersTable extends Migration
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
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'customer_token_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
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
        $this->forge->addUniqueKey(
            ['business_id', 'mobile']
        );
        $this->forge->addKey('business_id');

        $this->forge->addForeignKey(
            'business_id',
            'businesses',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->createTable('business_customers');
    }

    public function down()
    {
        $this->forge->dropTable('business_customers');
    }
}