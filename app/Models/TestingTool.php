<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestingTool extends Model
{
    use HasFactory;

    protected $table = 'testing_tools';

    protected $fillable = [
        'name',
        'brand_model',
        'unit_count',
        'status',
    ];
}
