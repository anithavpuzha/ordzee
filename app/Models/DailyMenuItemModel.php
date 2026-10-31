<?php

namespace App\Models;

use CodeIgniter\Model;

class DailyMenuItemModel extends Model
{
    protected $table            = 'daily_menu_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'daily_menu_id',
        'product_id',
        'product_name',
        'unit',
        'price',
        'is_available',
        'sort_order',
    ];
}