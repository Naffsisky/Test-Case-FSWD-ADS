<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_induk',
        'nama',
        'alamat',
        'tanggal_lahir',
        'tanggal_bergabung',
    ];
    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'nomor_induk', 'nomor_induk');
    }


}
