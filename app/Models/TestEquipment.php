<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestEquipment extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI UNTUK MENUNJUK NAMA TABEL YANG BENAR
    protected $table = 'test_equipments';

    protected $fillable = [
        'name',
        'serial_number',
        'status'
    ];
}