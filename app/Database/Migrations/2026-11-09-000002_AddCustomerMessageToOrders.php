<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerMessageToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'customer_message' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'seller_notes',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'customer_message');
    }
}