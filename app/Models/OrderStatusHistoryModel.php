<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStatusHistoryModel extends Model
{
    protected $table            = 'order_status_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = false;

    protected $allowedFields = [
        'order_id',
        'old_status',
        'new_status',
        'remarks',
        'created_at',
    ];
}