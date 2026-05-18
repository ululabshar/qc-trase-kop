<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\LotAdminModel;

class AdminController extends BaseController
{
    protected $batchModel;
    protected $lotAdminModel;
    protected $session;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->lotAdminModel = new LotAdminModel();
        $this->session = session();
    }

    /**
     * Halaman Utama Dashboard Admin
     */
    public function index()
    {
        if ($this->session->get('role') != 'admin') {
            return redirect()->to('/login')->with('error', 'Akses Admin Ditolak!');
        }

        $data = [
            'activeBatch' => $this->batchModel->where('status', 'Buka')->orderBy('id', 'DESC')->first(),
            'allBatch'    => $this->batchModel->orderBy('id', 'DESC')->findAll(),
            'dataLot'     => $this->lotAdminModel->orderBy('id', 'DESC')->findAll()
        ];

        return view('admin_dashboard', $data);
    }

    /**
     * Proses Simpan Data LOT Berhasil Ditambahkan QAN & Robinson
     */
    public function simpanLot()
    {
        if ($this->session->get('role') != 'admin') return redirect()->to('/login');

        $id = $this->request->getPost('id');
        $kgBaris = (float)$this->request->getPost('kg');

        // Tangkap data analisa dasar murni dari form input
        $ap        = (float)$this->request->getPost('ap');
        $cera      = (float)$this->request->getPost('cera');
        $hitam     = (float)$this->request->getPost('hitam');
        $busuk     = (float)$this->request->getPost('busuk');
        $pecah     = (float)$this->request->getPost('pecah');
        $kulit     = (float)$this->request->getPost('kulit');
        $batu      = (float)$this->request->getPost('batu');
        $gelondong = (float)$this->request->getPost('gelondong');
        
        // Inputan Baru QAN & Robinson
        $qan       = $this->request->getPost('qan');
        $robinson  = (float)$this->request->getPost('robinson');
        
        $abu_ayak  = (float)$this->request->getPost('abu_ayak');
        $sni       = (float)$this->request->getPost('sni');
        
        // 1. Hitung OTOMATIS TOTAL Trash (%)
        $totalTrashPercent = $hitam + $busuk + $pecah + $kulit + $batu + $gelondong;

        // 2. HITUNG TOTAL KG KESELURUHAN (SUM) SEMUA PALLET DI GUDANG
        $totalKgSemuaQuery = $this->lotAdminModel->selectSum('kg')->first();
        $totalKgSemua = $totalKgSemuaQuery ? (float)$totalKgSemuaQuery['kg'] : 0;

        if (empty($id)) {
            $totalKgSemua += $kgBaris;
        } else {
            $dataLama = $this->lotAdminModel->find($id);
            $totalKgSemua = ($totalKgSemua - (float)$dataLama['kg']) + $kgBaris;
        }

        // 3. HITUNG OTOMATIS EMPAT FIELD PROP SESUAI RUMUS EXCEL LAB
        $propAp    = 0;
        $propCera  = 0;
        $propTrace = 0;
        $propSni   = 0;

        if ($totalKgSemua > 0) {
            $propAp    = ($ap * $kgBaris) / $totalKgSemua;
            $propCera  = ($cera * $kgBaris) / $totalKgSemua;
            $propTrace = ($totalTrashPercent * $kgBaris) / $totalKgSemua;
            $propSni   = ($sni * $kgBaris) / $totalKgSemua;
        }

        $dataArray = [
            'batch_id'   => $this->request->getPost('batch_id'),
            'jenis_kopi' => 'TRACEMAX 11%',
            'tanggal'    => $this->request->getPost('tanggal') ? $this->request->getPost('tanggal') : date('Y-m-d'),
            'qan'        => $qan,
            'nama_lot'   => $this->request->getPost('nama_lot'),
            'colly'      => (int)$this->request->getPost('colly'),
            'kg'         => $kgBaris,
            'ap'         => $ap,
            'cera'       => $cera,
            'hitam'      => $hitam,
            'busuk'      => $busuk,
            'pecah'      => $pecah,
            'kulit'      => $kulit,
            'batu'       => $batu,
            'gelondong'  => $gelondong,
            'total'      => $totalTrashPercent,
            'robinson'   => $robinson,
            'abu_ayak'   => $abu_ayak,
            'sni'        => $sni,
            
            'prop_ap'    => $propAp,
            'prop_cera'  => $propCera,
            'prop_trace' => $propTrace,
            'prop_sni'   => $propSni
        ];

        if (!empty($id)) $dataArray['id'] = $id;

        $this->lotAdminModel->save($dataArray);

        // Pemicu kalkulasi massal agar baris lain ikut menyesuaikan total KG baru secara adil
        $this->recalculateAllProp($totalKgSemua);

        return redirect()->to('/admin')->with('success', 'Data rekap LOT Tracemax Berhasil Disimpan!');
    }

    /**
     * Fungsi Otomatis Penyeimbang Nilai Proporsional Makro Seluruh Baris
     */
    private function recalculateAllProp($totalKgKeseluruhan)
    {
        if ($totalKgKeseluruhan <= 0) return;

        $allData = $this->lotAdminModel->findAll();
        foreach ($allData as $row) {
            $newPropAp    = ((float)$row['ap'] * (float)$row['kg']) / $totalKgKeseluruhan;
            $newPropCera  = ((float)$row['cera'] * (float)$row['kg']) / $totalKgKeseluruhan;
            $newPropTrace = ((float)$row['total'] * (float)$row['kg']) / $totalKgKeseluruhan;
            $newPropSni   = ((float)$row['sni'] * (float)$row['kg']) / $totalKgKeseluruhan;
            
            $this->lotAdminModel->update($row['id'], [
                'prop_ap'    => $newPropAp,
                'prop_cera'  => $newPropCera,
                'prop_trace' => $newPropTrace,
                'prop_sni'   => $newPropSni
            ]);
        }
    }

    /**
     * Proses Hapus Data LOT Berdasarkan ID
     */
    public function hapusLot($id)
    {
        if ($this->session->get('role') != 'admin') return redirect()->to('/login');
        
        $this->lotAdminModel->delete($id);
        
        $totalKgSemuaQuery = $this->lotAdminModel->selectSum('kg')->first();
        $totalKgSemua = $totalKgSemuaQuery ? (float)$totalKgSemuaQuery['kg'] : 0;
        $this->recalculateAllProp($totalKgSemua);

        return redirect()->to('/admin')->with('success', 'Data LOT berhasil dihapus!');
    }
}