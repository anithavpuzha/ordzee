<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessCustomerModel extends Model
{
    protected $table            = 'business_customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'business_id',
        'customer_name',
        'mobile',
        'email',
        'address',
        'customer_token_hash',
    ];
}