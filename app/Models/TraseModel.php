<?php

namespace App\Models;

use CodeIgniter\Model;

class TraseModel extends Model
{
    protected $table            = 'hasil_trase';
    protected $primaryKey       = 'id';
    
    // PENTING: Pastikan 'jenis_kopi' ADA di dalam daftar allowedFields di bawah ini!
    protected $allowedFields    = [
        'batch_id', 
        'jenis_kopi', 
        'tanggal', 
        'mesin_gs', 
        'pukul', 
        'ap', 
        'cera', 
        'hitam', 
        'busuk', 
        'pecah', 
        'kulit', 
        'batu', 
        'gelondong', 
        'total', 
        'keterangan'
    ];
}