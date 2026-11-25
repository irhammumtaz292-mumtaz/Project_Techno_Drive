<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestEquipment extends Model
{
    use HasFactory;

    protected $table = 'test_equipments';

    protected $fillable = [
        'name',
        'brand_model',
        'unit_count',
        'serial_number',
        'status',
    ];
}
