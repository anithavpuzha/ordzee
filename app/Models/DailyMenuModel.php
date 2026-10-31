<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyMenuModel extends Model
{
    protected $table            = 'daily_menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'business_id',
        'menu_date',
        'menu_title',
        'status',
    ];
}