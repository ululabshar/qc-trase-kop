<?php

namespace App\Controllers;

use App\Models\BatchModel;
use App\Models\TraseModel;

class TraseController extends BaseController
{
    protected $batchModel;
    protected $traseModel;
    protected $session;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->traseModel = new TraseModel();
        $this->session = session();
    }

    // --- 1. HALAMAN LOGIN & PROSES ---
    public function login()
    {
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectByRole($this->session->get('role'));
        }
        return view('login_page');
    }

    public function prosesLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            $this->session->set([
                'isLoggedIn' => true,
                'role'       => $user['role']
            ]);
            return $this->redirectByRole($user['role']);
        }

        return redirect()->back()->with('error', 'Username atau Password Salah!');
    }

    private function redirectByRole($role)
    {
        if ($role === 'admin') return redirect()->to('/admin');
        if ($role === 'operator') return redirect()->to('/input');
        return redirect()->to('/monitor');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    // --- 2. HALAMAN INPUT OPERATOR LAPANGAN ---
    public function inputHalaman()
    {
        if ($this->session->get('role') != 'operator') {
            return redirect()->to('/login')->with('error', 'Akses ditolak!');
        }

        $activeBatch = $this->batchModel->where('status', 'Buka')->orderBy('id', 'DESC')->first();
        $dataTrase = [];
        if ($activeBatch) {
            $dataTrase = $this->traseModel->where('batch_id', $activeBatch['id'])->orderBy('pukul', 'DESC')->findAll();
        }

        return view('operator_input', [
            'activeBatch' => $activeBatch,
            'dataTrase'   => $dataTrase
        ]);
    }

    // --- 3. PROSES SIMPAN DATA & CETAK SATUAN ---
    public function simpanTrase()
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $id = $this->request->getPost('id');
        $aksi = $this->request->getPost('aksi'); // 'simpan' atau 'cetak'

        $ap = (float)$this->request->getPost('ap');
        $cera = (float)$this->request->getPost('cera');
        $hitam = (float)$this->request->getPost('hitam');
        $busuk = (float)$this->request->getPost('busuk');
        $pecah = (float)$this->request->getPost('pecah');
        $kulit = (float)$this->request->getPost('kulit');
        $batu = (float)$this->request->getPost('batu');
        $gelondong = (float)$this->request->getPost('gelondong');

        $total = $hitam + $busuk + $pecah + $kulit + $batu + $gelondong;

        $batchInfo = $this->batchModel->find($this->request->getPost('batch_id'));
        $batchName = $batchInfo ? $batchInfo['nama_batch'] : 'SHIFT-UNKNOWN';

        $dataArray = [
            'batch_id'   => $this->request->getPost('batch_id'),
            'jenis_kopi' => $this->request->getPost('jenis_kopi'),
            'tanggal'    => date('Y-m-d'),
            'mesin_gs'   => $this->request->getPost('mesin_gs'),
            'pukul'      => date('H:i:s'),
            'ap'         => $ap,
            'cera'       => $cera,
            'hitam'      => $hitam,
            'busuk'      => $busuk,
            'pecah'      => $pecah,
            'kulit'      => $kulit,
            'batu'       => $batu,
            'gelondong'  => $gelondong,
            'total'      => $total,
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if (!empty($id)) {
            $dataArray['id'] = $id;
        }

        $this->traseModel->save($dataArray);

        // Jika Operator memilih "Simpan & Cetak Label"
        if ($aksi === 'cetak') {
            $dataCetak = [
                'batch_name' => $batchName,
                'jenis_kopi' => $dataArray['jenis_kopi'],
                'tanggal'    => date('d-m-Y'),
                'jam'        => date('H:i'),
                'mesin_gs'   => $dataArray['mesin_gs'],
                'ap'         => $ap,
                'cera'       => $cera,
                'hitam'      => $hitam,
                'busuk'      => $busuk,
                'pecah'      => $pecah,
                'kulit'      => $kulit,
                'batu'       => $batu,
                'gelondong'  => $gelondong,
                'total'      => $total
            ];
            // Menggunakan ->with() sebagai pengganti withFlashdata() yang typo kemarin
            return redirect()->to('/input')->with('cetak_data', $dataCetak);
        }

        // Jika hanya simpan biasa, langsung redirect tanpa membawa data cetak
        return redirect()->to('/input');
    }
    public function cetakUlang($id)
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        // 1. Ambil data trase berdasarkan baris ID yang diklik
        $dataArray = $this->traseModel->find($id);
        if (!$dataArray) {
            return redirect()->to('/input');
        }

        // 2. Ambil informasi nama batch-nya
        $batchInfo = $this->batchModel->find($dataArray['batch_id']);
        $batchName = $batchInfo ? $batchInfo['nama_batch'] : 'SHIFT-UNKNOWN';

        // 3. Susun data rekap lengkap komponen kotoran untuk dikirim ke printer
        $dataCetak = [
            'batch_name' => $batchName,
            'jenis_kopi' => $dataArray['jenis_kopi'],
            'tanggal'    => date('d-m-Y', strtotime($dataArray['tanggal'])),
            'jam'        => substr($dataArray['pukul'], 0, 5),
            'mesin_gs'   => $dataArray['mesin_gs'],
            'ap'         => $dataArray['ap'],
            'cera'       => $dataArray['cera'],
            'hitam'      => $dataArray['hitam'],
            'busuk'      => $dataArray['busuk'],
            'pecah'      => $dataArray['pecah'],
            'kulit'      => $dataArray['kulit'],
            'batu'       => $dataArray['batu'],
            'gelondong'  => $dataArray['gelondong'],
            'total'      => $dataArray['total']
        ];

        // 4. Lemparkan ke halaman dengan membawa data flashdata cetak
        return redirect()->to('/input')->with('cetak_data', $dataCetak);
    }

    public function hapusTrase($id)
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');
        $this->traseModel->delete($id);
        return redirect()->to('/input');
    }

    // --- 4. KENDALI BATCH ---
    public function bukaBatch()
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $this->batchModel->save([
            'nama_batch'   => $this->request->getPost('nama_batch'),
            'tanggal_buka' => date('Y-m-d'),
            'status'       => 'Buka'
        ]);
        return redirect()->to('/input');
    }

    public function tutupBatch($id)
    {
        if ($this->session->get('role') != 'operator') return redirect()->to('/login');

        $this->batchModel->update($id, ['status' => 'Tutup']);
        return redirect()->to('/input');
    }

    // --- 5. LIVE MONITOR TV BROADCAST ---
    public function monitorHalaman()
    {
        if (!$this->session->get('isLoggedIn')) return redirect()->to('/login');
        return view('dashboard_monitor');
    }

    public function getLatestData()
    {
        $activeBatch = $this->batchModel->where('status', 'Buka')->orderBy('id', 'DESC')->first();
        if (!$activeBatch) {
            return $this->response->setJSON(['nama_batch' => 'TIDAK ADA BATCH AKTIF', 'data' => []]);
        }
        $dataTrase = $this->traseModel->where('batch_id', $activeBatch['id'])->orderBy('pukul', 'DESC')->findAll();
        return $this->response->setJSON(['nama_batch' => $activeBatch['nama_batch'], 'data' => $dataTrase]);
    }
}
