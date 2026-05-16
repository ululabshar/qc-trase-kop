<?php
namespace App\Models;
use CodeIgniter\Model;

class BatchModel extends Model {
    protected $table            = 'batch_produksi';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_batch', 'tanggal_buka', 'status'];
}