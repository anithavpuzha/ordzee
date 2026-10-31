<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
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
            'customer_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'customer_mobile' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'customer_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'delivery_address' => [
                'type' => 'TEXT',
            ],
            'order_date' => [
                'type' => 'DATETIME',
            ],
            'delivery_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'delivery_charge' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'grand_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'amount_paid' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'balance_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'order_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'pending',
            ],
            'payment_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'pending',
            ],
            'cancellation_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'seller_notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey(['business_id', 'order_number']);
        $this->forge->addKey('business_id');
        $this->forge->addKey('customer_id');
        $this->forge->addKey('order_date');
        $this->forge->addKey('order_status');
        $this->forge->addKey('payment_status');

        $this->forge->addForeignKey(
            'business_id',
            'businesses',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->forge->addForeignKey(
            'customer_id',
            'business_customers',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}