<?php

namespace App\Models;

use CodeIgniter\Model;

class LotAdminModel extends Model
{
    protected $table            = 'laporan_lot_admin';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'batch_id', 'jenis_kopi', 'tanggal', 'qan', 'nama_lot', 'colly', 'kg', 
        'ap', 'cera', 'hitam', 'busuk', 'pecah', 'kulit', 'batu', 
        'gelondong', 'total', 'qan', 'robinson', 'abu_ayak', 'sni', 'prop_sni',
        'prop_ap', 'prop_cera', 'prop_trace'
    ];
}