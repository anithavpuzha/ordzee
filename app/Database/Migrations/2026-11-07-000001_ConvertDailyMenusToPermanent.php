<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConvertDailyMenusToPermanent extends Migration
{
    public function up()
    {
        // Remove the old unique key (business_id + menu_date).
        $this->forge->dropKey('daily_menus', 'business_id_menu_date');

        // Remove menu_date because menus are now permanent.
        $this->forge->dropColumn('daily_menus', 'menu_date');

        // Allow only one menu per business.
        $this->forge->addUniqueKey('business_id', 'business_id_unique');
    }

    public function down()
    {
        // Restore menu_date with today's date for existing menus.
        $this->forge->addColumn('daily_menus', [
            'menu_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ]);

        // Restore a date for existing menu records.
        $this->db->table('daily_menus')
            ->where('menu_date', null)
            ->update(['menu_date' => date('Y-m-d')]);

        // Make menu_date required again.
        $this->forge->modifyColumn('daily_menus', [
            'menu_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
        ]);

        // Remove the one-menu-per-business key.
        $this->forge->dropKey('daily_menus', 'business_id_unique');

        // Restore the old unique key.
        $this->forge->addUniqueKey(
            ['business_id', 'menu_date'],
            'business_id_menu_date'
        );
    }
}