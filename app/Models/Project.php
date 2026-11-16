<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Mengizinkan semua kolom diisi (Mass Assignment)
    protected $guarded = ['id'];

    // Relasi ke Customer (1 proyek punya 1 customer)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke End User (1 proyek punya 1 end user)
    public function endUser()
    {
        return $this->belongsTo(EndUser::class);
    }

    // Relasi ke banyak Staff (Many to Many)
    public function staff()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    // Relasi ke banyak Alat Uji (Many to Many)
    public function equipments()
    {
        return $this->belongsToMany(TestEquipment::class, 'project_test_equipment');
    }

    // Relasi ke PO (1 proyek punya 1 PO)
    public function po()
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    // Relasi ke SPK (1 proyek bisa punya beberapa riwayat SPK)
    public function spks()
    {
        return $this->hasMany(Spk::class);
    }

    // Relasi ke PIC (User yang menjadi penanggung jawab dari Techno Drive)
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    // Relasi ke Final Report
    public function finalReports()
    {
        return $this->hasMany(FinalReport::class);
    }
}