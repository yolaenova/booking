<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';

    protected $allowedFields = [
        'name',
        'description',
        'price',
        'duration',
        'photo',
        'status'
    ];
}