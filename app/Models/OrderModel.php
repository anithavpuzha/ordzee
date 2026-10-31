<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'business_id',
        'customer_id',
        'order_number',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'delivery_address',
        'order_date',
        'delivery_date',
        'subtotal',
        'discount',
        'delivery_charge',
        'grand_total',
        'amount_paid',
        'balance_amount',
        'order_status',
        'payment_status',
        'cancellation_reason',
        'seller_notes',
    ];
}